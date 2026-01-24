<?php

namespace app\deshang\queue\driver;

use think\facade\Cache;

/**
 * Redis 列表队列驱动（LPUSH/RPOP）
 *
 * 说明：
 * - 即时队列：list 结构（lpush 入队，rpop 出队，先进先出）
 * - 延迟队列：zset 结构（score=触发时间戳），到期后迁移到即时队列
 *
 * 注意：
 * - 此实现适合“定时任务批处理”唤起消费的场景；
 * - 若需消费确认/消费组/重投递，请考虑 Redis Streams 或 RabbitMQ；
 * - 如需将“延迟迁移+出队”合并为原子，可后续使用 Lua 脚本优化。
 */
class RedisListDriver
{
    /** @var string 即时队列键名 */
    private string $immediateKey = 'queue:task:immediate';
    /** @var string 延迟队列键名（zset，score=到期时间） */
    private string $delayKey     = 'queue:task:delay';

    /**
     * 入队
     *
     * @param array $payload 任务载荷（将 json_encode 存储）
     * @param int   $delaySec 延迟秒数（>0 则进入延迟 zset）
     * @return void
     */
    public function push(array $payload, int $delaySec = 0): void
    {
        $redis = Cache::store('redis');
        $raw = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($delaySec > 0) {
            $redis->zadd($this->delayKey, time() + $delaySec, $raw);
        } else {
            $redis->lpush($this->immediateKey, $raw);
        }
    }

    /**
     * 批量出队（最多 $max 条）
     *
     * @param int $max 最大条数
     * @return array<string> 原始 JSON 字符串数组
     */
    public function popBatch(int $max = 200): array
    {
        $redis = Cache::store('redis');
        $res = [];
        for ($i = 0; $i < $max; $i++) {
            $raw = $redis->rpop($this->immediateKey);
            if (!$raw) break;
            $res[] = $raw;
        }
        return $res;
    }

    /**
     * 将到期的延迟任务迁移到即时队列
     *
     * @param int $max 一次最多迁移条数
     * @return void
     */
    public function migrateDueDelay(int $max = 500): void
    {
        $redis = Cache::store('redis');
        $now = time();
        $items = $redis->zrangebyscore($this->delayKey, '-inf', $now, ['limit' => [0, $max]]);
        foreach ($items as $raw) {
            if ($redis->zrem($this->delayKey, $raw)) {
                $redis->lpush($this->immediateKey, $raw);
            }
        }
    }
}
