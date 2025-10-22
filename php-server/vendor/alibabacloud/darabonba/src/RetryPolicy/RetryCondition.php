<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;
use AlibabaCloud\Dara\Exception\DaraException;

class RetryCondition {
    private $maxAttempts;
    private $backoff = null;
    private $exception = [];
    private $errorCode = [];
    private $maxDelay;

    public function __construct($condition) {
        
        if(isset($condition['maxAttempts'])) {
            $this->maxAttempts = $condition['maxAttempts'];
        }
        
        $this->backoff = isset($condition['backoff']) ? $condition['backoff'] : null;
        $this->exception = isset($condition['exception']) ? $condition['exception'] : [];
        $this->errorCode = isset($condition['errorCode']) ? $condition['errorCode'] : [];
        $this->maxDelay = isset($condition['maxDelay']) ? $condition['maxDelay'] : [];
    }


    /**
     * @return int
     */
    public function getMaxAttempts() {
        return $this->maxAttempts;
    }

    /**
     * @return BackoffPolicy
     */
    public function getBackoff() {
        return $this->backoff;
    }

    /**
     * @return string[]
     */
    public function getException() {
        return $this->exception;
    }

    /**
     * @return string[]
     */
    public function getErrorCode() {
        return $this->errorCode;
    }

    /**
     * @return int
     */
    public function getMaxDelay() {
        return $this->maxDelay;
    }
}