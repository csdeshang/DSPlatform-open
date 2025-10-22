<?php

namespace AlibabaCloud\Dara\Exception;

/**
 * Class DaraRespException.
 */
class DaraRespException extends DaraException
{
    public $statusCode;
    protected $retryAfter;
    public $data;
    public $accessDeniedDetail;
    public $description;

    /**
     * DaraRespException constructor.
     *
     * @param array           $errorInfo
     * @param string          $message
     * @param int             $code
     * @param null|\Throwable $previous
     */
    public function __construct($errorInfo = [], $message = '', $code = 0, $previous = null)
    {
        parent::__construct($errorInfo, (string) $message, (int) $code, $previous);
        $this->name = 'ResponseError';
        if (!empty($errorInfo)) {
            $properties = ['retryAfter', 'statusCode', 'data', 'description', 'accessDeniedDetail'];
            foreach ($properties as $property) {
                if (isset($errorInfo[$property])) {
                    $this->{$property} = $errorInfo[$property];
                    if ($property === 'data' && isset($errorInfo['data']['statusCode'])) {
                        $this->statusCode = $errorInfo['data']['statusCode'];
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
    
    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return int
     */
    public function getRetryAfter()
    {
        return $this->retryAfter;
    }

    /**
     * @return array
     */
    public function getAccessDeniedDetail()
    {
        return $this->accessDeniedDetail;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
