<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;

class CreateCardSmsTemplateRequest extends Model
{
    /**
     * @var string
     */
    public $factorys;

    /**
     * @var string
     */
    public $memo;

    /**
     * @var mixed[]
     */
    public $template;

    /**
     * @var string
     */
    public $templateName;
    protected $_name = [
        'factorys' => 'Factorys',
        'memo' => 'Memo',
        'template' => 'Template',
        'templateName' => 'TemplateName',
    ];

    public function validate()
    {
        if (\is_array($this->template)) {
            Model::validateArray($this->template);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->factorys) {
            $res['Factorys'] = $this->factorys;
        }

        if (null !== $this->memo) {
            $res['Memo'] = $this->memo;
        }

        if (null !== $this->template) {
            if (\is_array($this->template)) {
                $res['Template'] = [];
                foreach ($this->template as $key1 => $value1) {
                    $res['Template'][$key1] = $value1;
                }
            }
        }

        if (null !== $this->templateName) {
            $res['TemplateName'] = $this->templateName;
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
        if (isset($map['Factorys'])) {
            $model->factorys = $map['Factorys'];
        }

        if (isset($map['Memo'])) {
            $model->memo = $map['Memo'];
        }

        if (isset($map['Template'])) {
            if (!empty($map['Template'])) {
                $model->template = [];
                foreach ($map['Template'] as $key1 => $value1) {
                    $model->template[$key1] = $value1;
                }
            }
        }

        if (isset($map['TemplateName'])) {
            $model->templateName = $map['TemplateName'];
        }

        return $model;
    }
}
