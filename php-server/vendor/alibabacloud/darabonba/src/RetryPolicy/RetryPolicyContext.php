<?php

namespace AlibabaCloud\Dara\RetryPolicy;

use AlibabaCloud\Dara\RetryPolicy\BackoffPolicy;
use AlibabaCloud\Dara\Request;
use AlibabaCloud\Dara\Response;
use AlibabaCloud\Dara\Exception\DaraException;

class RetryPolicyContext {
    private $key;
    private $retriesAttempted;
    private $httpRequest;
    private $httpResponse;
    private $exception;

    public function __construct($options) {
        $this->key = isset($options['key']) ? $options['key'] : '';
        $this->retriesAttempted = isset($options['retriesAttempted']) ? $options['retriesAttempted'] : 0;
        $this->httpRequest = isset($options['httpRequest']) ? $options['httpRequest'] : null;
        $this->httpResponse = isset($options['httpResponse']) ? $options['httpResponse'] : null;
        $this->exception = isset($options['exception']) ? $options['exception'] : null;
    }

    /**
     *
     * @return int
     */
    public function getRetryCount(){
        return $this->retriesAttempted;
    }

    /**
     *
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     *
     * @return Request
     */
    public function getHttpRequest() {
        return $this->httpRequest;
    }

    /**
     *
     * @return Response
     */
    public function getHttpResponse() {
        return $this->httpResponse;
    }

    /**
     *
     * @return DaraException
     */
    public function getException() {
        return $this->exception;
    }
}