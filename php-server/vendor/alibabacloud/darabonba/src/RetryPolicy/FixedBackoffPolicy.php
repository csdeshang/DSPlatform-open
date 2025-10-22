<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;


class FixedBackoffPolicy extends BackoffPolicy {
    private $period;

    public function __construct(array $option) {
        parent::__construct($option);
        if (!isset($option['period'])) {
            throw new DaraException([], "Period must be specified.");
        }
        $this->period = $option['period'];
    }

    public function getDelayTime($ctx) {
        return $this->period;
    }
}

