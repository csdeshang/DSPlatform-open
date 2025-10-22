<?php

namespace AlibabaCloud\Dara\Exception;

use AlibabaCloud\Dara\Request;
use AlibabaCloud\Dara\RetryPolicy\RetryPolicyContext;

/**
 * Class DaraUnableRetryException.
 */
class DaraUnableRetryException extends DaraException
{
    private $lastRequest;
    private $lastException;

    /**
     * DaraUnableRetryException constructor.
     *
     * @param Request         $lastRequest
     * @param null|\Exception $lastException
     */
    public function __construct($lastRequest, $lastException = null)
    {
        if($lastRequest instanceof RetryPolicyContext) {
            $lastException = $lastRequest->getException();
            $lastRequest = $lastRequest->getHttpRequest();
        }
        $error_info = [];
        if (null !== $lastException && $lastException instanceof DaraException) {
            $error_info = $lastException->getErrorInfo();
        }
        parent::__construct($error_info, $lastException->getMessage(), $lastException->getCode(), $lastException);
        $this->lastRequest   = $lastRequest;
        $this->lastException = $lastException;
    }

    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    public function getLastException()
    {
        return $this->lastException;
    }
}
