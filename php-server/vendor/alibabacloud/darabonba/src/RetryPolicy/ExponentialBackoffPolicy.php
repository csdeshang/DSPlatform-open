<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;


class ExponentialBackoffPolicy extends BackoffPolicy {
    private $period;
    private $cap;

    public function __construct(array $option) {
        parent::__construct($option);
        if (!isset($option['period'])) {
            throw new DaraException("Period must be specified.");
        }
        $this->period = $option['period'];
        // 默认值: 3 天
        $this->cap = isset($option['cap']) ? $option['cap'] : 3 * 24 * 60 * 60 * 1000;
    }

    public function getDelayTime($ctx) {
        $randomTime = pow(2, $ctx->getRetryCount() * $this->period);
        return ($randomTime > $this->cap) ? $this->cap : $randomTime;
    }
}
