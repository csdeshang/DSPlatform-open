<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\RetryCondition;

class RetryOptions {
    private $retryable;
    private $retryCondition;
    private $noRetryCondition;

    public function __construct($options) {
        $this->retryable = $options['retryable'];
        $this->retryCondition = array_map(function ($condition) {
            if($condition instanceof RetryCondition) {
                return $condition;
            }
            return new RetryCondition($condition);
        }, isset($options['retryCondition']) ? $options['retryCondition'] : []);

        $this->noRetryCondition = array_map(function ($condition) {
            if($condition instanceof RetryCondition) {
                return $condition;
            }
            return new RetryCondition($condition);
        }, isset($options['noRetryCondition']) ? $options['noRetryCondition'] : []);
    }

    /**
     * @return bool
     */
    public function getRetryable() {
        return $this->retryable;
    }

    /**
     * @return RetryCondition[]
     */
    public function getRetryCondition() {
        return $this->retryCondition;
    }

    /**
     * @return RetryCondition[]
     */
    public function getNoRetryCondition() {
        return $this->noRetryCondition;
    }
}