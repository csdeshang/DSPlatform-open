<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi\Exceptions;
use AlibabaCloud\Dara\Exception\DaraException;
class AlibabaCloudException extends DaraException {
  /**
  * @var int
  */
  public $statusCode;
  /**
  * @var string
  */
  protected $code;
  /**
  * @var string
  */
  public $message;
  /**
  * @var string
  */
  public $description;
  /**
  * @var string
  */
  protected $requestId;

  public function __construct($map)
  {
    parent::__construct($map);
    $this->statusCode = $map['statusCode'];
    $this->code = $map['code'];
    $this->message = $map['message'];
    $this->description = $map['description'];
    $this->requestId = $map['requestId'];
  }

  /**
  * @return int
  */
  public function getStatusCode()
  {
    return $this->statusCode;
  }
  /**
  * @return string
  */
  public function getCode()
  {
    return $this->code;
  }
  /**
  * @return string
  */
  public function getMessage()
  {
    return $this->message;
  }
  /**
  * @return string
  */
  public function getDescription()
  {
    return $this->description;
  }
  /**
  * @return string
  */
  public function getRequestId()
  {
    return $this->requestId;
  }
}

