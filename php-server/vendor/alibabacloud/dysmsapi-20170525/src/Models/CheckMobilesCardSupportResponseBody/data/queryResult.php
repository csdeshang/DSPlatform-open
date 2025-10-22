<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CheckMobilesCardSupportResponseBody\data;

use AlibabaCloud\Dara\Model;

class queryResult extends Model
{
    /**
     * @var string
     */
    public $mobile;

    /**
     * @var bool
     */
    public $support;
    protected $_name = [
        'mobile' => 'mobile',
        'support' => 'support',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->mobile) {
            $res['mobile'] = $this->mobile;
        }

        if (null !== $this->support) {
            $res['support'] = $this->support;
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
        if (isset($map['mobile'])) {
            $model->mobile = $map['mobile'];
        }

        if (isset($map['support'])) {
            $model->support = $map['support'];
        }

        return $model;
    }
}
