<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;

class CheckMobilesCardSupportRequest extends Model
{
    /**
     * @var mixed[][]
     */
    public $mobiles;

    /**
     * @var string
     */
    public $templateCode;
    protected $_name = [
        'mobiles' => 'Mobiles',
        'templateCode' => 'TemplateCode',
    ];

    public function validate()
    {
        if (\is_array($this->mobiles)) {
            Model::validateArray($this->mobiles);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->mobiles) {
            if (\is_array($this->mobiles)) {
                $res['Mobiles'] = [];
                $n1 = 0;
                foreach ($this->mobiles as $item1) {
                    if (\is_array($item1)) {
                        $res['Mobiles'][$n1++] = [];
                        foreach ($item1 as $key2 => $value2) {
                            $res['Mobiles'][$n1++][$key2] = $value2;
                        }
                    }
                }
            }
        }

        if (null !== $this->templateCode) {
            $res['TemplateCode'] = $this->templateCode;
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
        if (isset($map['Mobiles'])) {
            if (!empty($map['Mobiles'])) {
                $model->mobiles = [];
                $n1 = 0;
                foreach ($map['Mobiles'] as $item1) {
                    if (!empty($item1)) {
                        $model->mobiles[$n1++] = [];
                        foreach ($item1 as $key2 => $value2) {
                            $model->mobiles[$n1++][$key2] = $value2;
                        }
                    }
                }
            }
        }

        if (isset($map['TemplateCode'])) {
            $model->templateCode = $map['TemplateCode'];
        }

        return $model;
    }
}
