<?php

namespace AlibabaCloud\Dara\Exception;

use AlibabaCloud\Tea\Exception\TeaError;

/**
 * Class DaraException.
 */
class DaraException extends TeaError
{
    public $message = '';
    public $errCode = '';
    public $data;
    public $name    = '';
    public $statusCode;
    public $description;
    public $accessDeniedDetail;
    public $errorInfo;

    /**
     * DaraError DaraException.
     *
     * @param array           $errorInfo
     * @param string          $message
     * @param int             $code
     * @param null|\Throwable $previous
     */
    public function __construct($errorInfo = [], $message = '', $code = '', $previous = null)
    {
        parent::__construct($errorInfo, $message, $code, $previous);
        $this->errorInfo = $errorInfo;
        $this->name = 'BaseError';
        if (!empty($errorInfo)) {
            $properties = ['name', 'message', 'errCode', 'data', 'description', 'accessDeniedDetail'];
            foreach ($properties as $property) {
                if (isset($errorInfo[$property])) {
                    $this->{$property} = $errorInfo[$property];
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @return array
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }
}
