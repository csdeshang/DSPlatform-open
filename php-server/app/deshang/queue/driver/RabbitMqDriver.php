<?php

namespace app\deshang\queue\driver;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * RabbitMQ 驱动（简化版）
 *
 * 注意：
 * - 为契合当前消费者接口，popBatch 使用 basic_get 非阻塞读取并立即 ACK；
 *   若需失败重投/死信，请扩展为 basic_consume + ack/nack + DLX/延迟交换机。
 * - 延迟任务建议使用 TTL+DLX 或 rabbitmq_delayed_message_exchange 插件；
 *   这里未内置延迟逻辑（由上层统一控制）。
 */
class RabbitMqDriver
{
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $queue;

    public function __construct(string $host = '127.0.0.1', int $port = 5672, string $user = 'guest', string $pass = 'guest', string $queue = 'q.task')
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->queue = $queue;
    }

    public function push(array $payload, int $delaySec = 0): void
    {
        $cnn = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass);
        $ch  = $cnn->channel();
        $ch->queue_declare($this->queue, false, true, false, false);

        $raw = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $props = ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];
        // 简化延迟：可基于消息头 x-delay 或 TTL+DLX 配置，在此不直接处理 delaySec
        $msg = new AMQPMessage($raw, $props);
        $ch->basic_publish($msg, '', $this->queue);

        $ch->close();
        $cnn->close();
    }

    public function migrateDueDelay(int $max = 500): void
    {
        // 留空：建议通过 RabbitMQ 延迟插件或 TTL+DLX 实现延迟，无需在驱动内迁移
    }

    /**
     * 读取并 ACK（简化）
     * @return array<string>
     */
    public function popBatch(int $max = 200): array
    {
        $cnn = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass);
        $ch  = $cnn->channel();
        $ch->queue_declare($this->queue, false, true, false, false);

        $res = [];
        for ($i = 0; $i < $max; $i++) {
            $msg = $ch->basic_get($this->queue);
            if (!$msg) break;
            $res[] = $msg->body;
            $ch->basic_ack($msg->getDeliveryTag());
        }

        $ch->close();
        $cnn->close();
        return $res;
    }
}
