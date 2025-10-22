<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryCardSmsTemplateReportResponseBody;

use AlibabaCloud\Dara\Model;

class data extends Model
{
    /**
     * @var mixed[][]
     */
    public $model;
    protected $_name = [
        'model' => 'model',
    ];

    public function validate()
    {
        if (\is_array($this->model)) {
            Model::validateArray($this->model);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->model) {
            if (\is_array($this->model)) {
                $res['model'] = [];
                $n1 = 0;
                foreach ($this->model as $item1) {
                    if (\is_array($item1)) {
                        $res['model'][$n1++] = [];
                        foreach ($item1 as $key2 => $value2) {
                            $res['model'][$n1++][$key2] = $value2;
                        }
                    }
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
        if (isset($map['model'])) {
            if (!empty($map['model'])) {
                $model->model = [];
                $n1 = 0;
                foreach ($map['model'] as $item1) {
                    if (!empty($item1)) {
                        $model->model[$n1++] = [];
                        foreach ($item1 as $key2 => $value2) {
                            $model->model[$n1++][$key2] = $value2;
                        }
                    }
                }
            }
        }

        return $model;
    }
}
