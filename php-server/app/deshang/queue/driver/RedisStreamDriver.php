<?php

namespace app\deshang\queue\driver;

use think\facade\Cache;

/**
 * Redis Streams 驱动（简化版）
 *
 * 说明：
 * - 使用 XADD 写入，XREAD 读取；为简化与现有消费者契合，读取后直接 XDEL（等价于立即确认）
 * - 延迟任务仍复用 zset（与 RedisListDriver 一致）
 *
 * 注意：
 * - 若需消费组、ACK、pending 重领，请将本驱动扩展为 XREADGROUP + XACK 模式；
 * - 实际生产建议：创建消费组，使用 XREADGROUP 并按 idle 超时结合 XPENDING/XCLAIM 做超时夺回。
 */
class RedisStreamDriver
{
    /** @var string stream 名称 */
    private string $stream = 'stream:task';
    /** @var string 延迟队列键名（zset） */
    private string $delayKey = 'queue:task:delay';

    public function push(array $payload, int $delaySec = 0): void
    {
        $redis = Cache::store('redis')->connection();
        $raw = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($delaySec > 0) {
            Cache::store('redis')->zadd($this->delayKey, time() + $delaySec, $raw);
            return;
        }
        $redis->xAdd($this->stream, '*', ['body' => $raw]);
    }

    public function migrateDueDelay(int $max = 500): void
    {
        $redis = Cache::store('redis');
        $now = time();
        $items = $redis->zrangebyscore($this->delayKey, '-inf', $now, ['limit' => [0, $max]]);
        foreach ($items as $raw) {
            if ($redis->zrem($this->delayKey, $raw)) {
                Cache::store('redis')->connection()->xAdd($this->stream, '*', ['body' => $raw]);
            }
        }
    }

    /**
     * 读取并删除（简化：等同于立即确认）
     * @return array<string>
     */
    public function popBatch(int $max = 200): array
    {
        $redis = Cache::store('redis')->connection();
        // XREAD 不删除消息，这里采用 XRANGE 批量读取后 XDEL 删除（不阻塞）
        $res = [];
        $ids = $redis->xRange($this->stream, '-', '+', $max);
        if (!$ids) return $res;
        foreach ($ids as $id => $fields) {
            $raw = $fields['body'] ?? '';
            if ($raw !== '') {
                $res[] = $raw;
            }
            // 读取后直接删除，避免重复（简化）
            $redis->xDel($this->stream, [$id]);
        }
        return $res;
    }
}
