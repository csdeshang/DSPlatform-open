<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportResponseBody\data;

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
        'mobile' => 'Mobile',
        'support' => 'Support',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->mobile) {
            $res['Mobile'] = $this->mobile;
        }

        if (null !== $this->support) {
            $res['Support'] = $this->support;
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
        if (isset($map['Mobile'])) {
            $model->mobile = $map['Mobile'];
        }

        if (isset($map['Support'])) {
            $model->support = $map['Support'];
        }

        return $model;
    }
}
