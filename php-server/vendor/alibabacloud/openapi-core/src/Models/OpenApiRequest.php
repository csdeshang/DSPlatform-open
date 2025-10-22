<?php

// This file is auto-generated, don't edit it. Thanks.
 
namespace Darabonba\OpenApi\Models;
use AlibabaCloud\Dara\Model;
use GuzzleHttp\Psr7\Stream;
class OpenApiRequest extends Model {
  /**
   * @var string[]
   */
  public $headers;
  /**
   * @var string[]
   */
  public $query;
  /**
   * @var mixed
   */
  public $body;
  /**
   * @var Stream
   */
  public $stream;
  /**
   * @var string[]
   */
  public $hostMap;
  /**
   * @var string
   */
  public $endpointOverride;
  protected $_name = [
      'headers' => 'headers',
      'query' => 'query',
      'body' => 'body',
      'stream' => 'stream',
      'hostMap' => 'hostMap',
      'endpointOverride' => 'endpointOverride',
  ];

  public function validate()
  {
    if(is_array($this->headers)) {
      Model::validateArray($this->headers);
    }
    if(is_array($this->query)) {
      Model::validateArray($this->query);
    }
    if(is_array($this->hostMap)) {
      Model::validateArray($this->hostMap);
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

    if (null !== $this->query) {
      if(is_array($this->query)) {
        $res['query'] = [];
        foreach($this->query as $key1 => $value1) {
          $res['query'][$key1] = $value1;
        }
      }
    }

    if (null !== $this->body) {
      $res['body'] = $this->body;
    }

    if (null !== $this->stream) {
      $res['stream'] = $this->stream;
    }

    if (null !== $this->hostMap) {
      if(is_array($this->hostMap)) {
        $res['hostMap'] = [];
        foreach($this->hostMap as $key1 => $value1) {
          $res['hostMap'][$key1] = $value1;
        }
      }
    }

    if (null !== $this->endpointOverride) {
      $res['endpointOverride'] = $this->endpointOverride;
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

    if (isset($map['query'])) {
      if(!empty($map['query'])) {
        $model->query = [];
        foreach($map['query'] as $key1 => $value1) {
          $model->query[$key1] = $value1;
        }
      }
    }

    if (isset($map['body'])) {
      $model->body = $map['body'];
    }

    if (isset($map['stream'])) {
      $model->stream = $map['stream'];
    }

    if (isset($map['hostMap'])) {
      if(!empty($map['hostMap'])) {
        $model->hostMap = [];
        foreach($map['hostMap'] as $key1 => $value1) {
          $model->hostMap[$key1] = $value1;
        }
      }
    }

    if (isset($map['endpointOverride'])) {
      $model->endpointOverride = $map['endpointOverride'];
    }

    return $model;
  }


}

