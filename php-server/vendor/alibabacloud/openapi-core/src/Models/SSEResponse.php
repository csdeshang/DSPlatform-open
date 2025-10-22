<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi\Models;
use AlibabaCloud\Dara\Model;
use AlibabaCloud\Dara\SSE\Event;
class SSEResponse extends Model {
  /**
   * @var string[]
   */
  public $headers;
  /**
   * @var int
   */
  public $statusCode;
  /**
   * @var Event
   */
  public $event;
  protected $_name = [
      'headers' => 'headers',
      'statusCode' => 'statusCode',
      'event' => 'event',
  ];

  public function validate()
  {
    if(is_array($this->headers)) {
      Model::validateArray($this->headers);
    }
    Model::validateRequired('headers', $this->headers, true);
    Model::validateRequired('statusCode', $this->statusCode, true);
    Model::validateRequired('event', $this->event, true);
    parent::validate();
  }

  public function toArray($noStream = false)
  {
    $res = [];
    if (null !== $this->headers) {
      if(is_array($this->headers)) {
        $res['headers'] = [];
        foreach($this->headers as $key1 => $value1) {
          $res['headers'][$key1] = $value1;
        }
      }
    }

    if (null !== $this->statusCode) {
      $res['statusCode'] = $this->statusCode;
    }

    if (null !== $this->event) {
      $res['event'] = null !== $this->event ? $this->event->toArray($noStream) : $this->event;
    }

    return $res;
  }

  public function toMap($noStream = false)
  {
    return $this->toArray($noStream);
  }

  public static function fromMap($map = [])
  {
    $model = new self();
    if (isset($map['headers'])) {
      if(!empty($map['headers'])) {
        $model->headers = [];
        foreach($map['headers'] as $key1 => $value1) {
          $model->headers[$key1] = $value1;
        }
      }
    }

    if (isset($map['statusCode'])) {
      $model->statusCode = $map['statusCode'];
    }

    if (isset($map['event'])) {
      $model->event = Event::fromMap($map['event']);
    }

    return $model;
  }


}

