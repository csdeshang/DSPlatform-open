<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;


class RandomBackoffPolicy extends BackoffPolicy {
    private $period;
    private $cap;

    public function __construct(array $option) {
        parent::__construct($option);
        if (!isset($option['period'])) {
            throw new DaraException([], "Period must be specified.");
        }
        $this->period = $option['period'];
        $this->cap = isset($option['cap']) ? $option['cap'] : 20000;
    }

    public function getDelayTime($ctx) {
        $randomTime = mt_rand(0, $ctx->getRetryCount() * $this->period);
        return ($randomTime > $this->cap) ? $this->cap : $randomTime;
    }
}
