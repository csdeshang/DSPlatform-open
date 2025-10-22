<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi\Models;
use AlibabaCloud\Dara\Model;
class GlobalParameters extends Model {
  /**
   * @var string[]
   */
  public $headers;
  /**
   * @var string[]
   */
  public $queries;
  protected $_name = [
      'headers' => 'headers',
      'queries' => 'queries',
  ];

  public function validate()
  {
    if(is_array($this->headers)) {
      Model::validateArray($this->headers);
    }
    if(is_array($this->queries)) {
      Model::validateArray($this->queries);
    }
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

    if (null !== $this->queries) {
      if(is_array($this->queries)) {
        $res['queries'] = [];
        foreach($this->queries as $key1 => $value1) {
          $res['queries'][$key1] = $value1;
        }
      }
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

    if (isset($map['queries'])) {
      if(!empty($map['queries'])) {
        $model->queries = [];
        foreach($map['queries'] as $key1 => $value1) {
          $model->queries[$key1] = $value1;
        }
      }
    }

    return $model;
  }


}

