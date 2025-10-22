<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;


class EqualJitterBackoffPolicy extends BackoffPolicy {
    private $period;
    private $cap;

    public function __construct(array $option) {
        parent::__construct($option);
        if (!isset($option['period'])) {
            throw new InvalidArgumentException("Period must be specified.");
        }
        $this->period = $option['period'];
        // 默认值: 3 天
        $this->cap = isset($option['cap']) ? $option['cap'] : 3 * 24 * 60 * 60 * 1000;
    }

    public function getDelayTime($ctx) {
        $ceil = min(pow(2, $ctx->getRetryCount() * $this->period), $this->cap);
        return $ceil / 2 + mt_rand(0, $ceil / 2);
    }
}
