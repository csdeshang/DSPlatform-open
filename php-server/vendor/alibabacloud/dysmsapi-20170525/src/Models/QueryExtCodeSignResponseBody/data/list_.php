<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryExtCodeSignResponseBody\data;

use AlibabaCloud\Dara\Model;

class list_ extends Model
{
    /**
     * @var int
     */
    public $active;

    /**
     * @var string
     */
    public $extCode;

    /**
     * @var int
     */
    public $sendCount;

    /**
     * @var string
     */
    public $signName;

    /**
     * @var string
     */
    public $source;
    protected $_name = [
        'active' => 'Active',
        'extCode' => 'ExtCode',
        'sendCount' => 'SendCount',
        'signName' => 'SignName',
        'source' => 'Source',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->active) {
            $res['Active'] = $this->active;
        }

        if (null !== $this->extCode) {
            $res['ExtCode'] = $this->extCode;
        }

        if (null !== $this->sendCount) {
            $res['SendCount'] = $this->sendCount;
        }

        if (null !== $this->signName) {
            $res['SignName'] = $this->signName;
        }

        if (null !== $this->source) {
            $res['Source'] = $this->source;
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
        if (isset($map['Active'])) {
            $model->active = $map['Active'];
        }

        if (isset($map['ExtCode'])) {
            $model->extCode = $map['ExtCode'];
        }

        if (isset($map['SendCount'])) {
            $model->sendCount = $map['SendCount'];
        }

        if (isset($map['SignName'])) {
            $model->signName = $map['SignName'];
        }

        if (isset($map['Source'])) {
            $model->source = $map['Source'];
        }

        return $model;
    }
}
