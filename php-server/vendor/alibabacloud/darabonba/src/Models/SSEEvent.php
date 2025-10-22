<?php

namespace AlibabaCloud\Dara\Models;

use AlibabaCloud\Dara\Model;

class SSEEvent extends Model {
    public $data;
    public $id;
    public $event;
    public $retry;

    public function __construct($data = array()) {
        $this->data = isset($data['data']) ? $data['data'] : null;
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->event = isset($data['event']) ? $data['event'] : null;
        $this->retry = isset($data['retry']) ? $data['retry'] : null;
    }

    public function validate() { }

    public function toArray()
    {
        $res = [];
        if (null !== $this->data) {
            $res['data'] = $this->data;
        }

        if (null !== $this->id) {
            $res['id'] = $this->id;
        }

        if (null !== $this->event) {
            $res['event'] = $this->event;
        }

        if (null !== $this->retry) {
            $res['retry'] = $this->retry;
        }

        return $res;
    }

    public function toMap()
    {
        return $this->toArray();
    }

    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['data'])) {
            if(!empty($map['data'])){
                $model->data = [];
                foreach($map['data'] as $key => $value) {
                    $model->data[$key] = $value;
                }
            }
        }

        if (isset($map['id'])) {
            $model->id = $map['id'];
        }

        if (isset($map['event'])) {
            $model->event = $map['event'];
        }

        if (isset($map['retry'])) {
            $model->retry = $map['retry'];
        }

        return $res;
    }
    
}