<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\EqualJitterBackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\ExponentialBackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\FixedBackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\FullJitterBackoffPolicy;
use AlibabaCloud\Dara\RetryPolicy\RandomBackoffPolic;

interface BackoffPolicyInterface {
    public function getDelayTime($ctx);
}

abstract class BackoffPolicy implements BackoffPolicyInterface {

    protected $policy;

    public function __construct($option) {
        $this->policy = $option['policy'];
    }

    abstract public function getDelayTime($ctx);

    public static function newBackoffPolicy($option) {
        switch($option['policy']) {
            case 'Fixed': 
                return new FixedBackoffPolicy($option);
            case 'Random': 
                return new RandomBackoffPolicy($option);
            case 'Exponential': 
                return new ExponentialBackoffPolicy($option);
            case 'EqualJitter':
            case 'ExponentialWithEqualJitter':
                return new EqualJitterBackoffPolicy($option);
            case 'FullJitter':
            case 'ExponentialWithFullJitter':
                return new FullJitterBackoffPolicy($option);
            default:
                throw new DaraException([], "Invalid backoff policy");
        }
    } 
}

