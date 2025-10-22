<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryPageSmartShortUrlLogResponseBody\model;

use AlibabaCloud\Dara\Model;

class list_ extends Model
{
    /**
     * @var int
     */
    public $clickState;

    /**
     * @var int
     */
    public $clickTime;

    /**
     * @var int
     */
    public $createTime;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $shortName;

    /**
     * @var string
     */
    public $shortUrl;
    protected $_name = [
        'clickState' => 'ClickState',
        'clickTime' => 'ClickTime',
        'createTime' => 'CreateTime',
        'phoneNumber' => 'PhoneNumber',
        'shortName' => 'ShortName',
        'shortUrl' => 'ShortUrl',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->clickState) {
            $res['ClickState'] = $this->clickState;
        }

        if (null !== $this->clickTime) {
            $res['ClickTime'] = $this->clickTime;
        }

        if (null !== $this->createTime) {
            $res['CreateTime'] = $this->createTime;
        }

        if (null !== $this->phoneNumber) {
            $res['PhoneNumber'] = $this->phoneNumber;
        }

        if (null !== $this->shortName) {
            $res['ShortName'] = $this->shortName;
        }

        if (null !== $this->shortUrl) {
            $res['ShortUrl'] = $this->shortUrl;
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
        if (isset($map['ClickState'])) {
            $model->clickState = $map['ClickState'];
        }

        if (isset($map['ClickTime'])) {
            $model->clickTime = $map['ClickTime'];
        }

        if (isset($map['CreateTime'])) {
            $model->createTime = $map['CreateTime'];
        }

        if (isset($map['PhoneNumber'])) {
            $model->phoneNumber = $map['PhoneNumber'];
        }

        if (isset($map['ShortName'])) {
            $model->shortName = $map['ShortName'];
        }

        if (isset($map['ShortUrl'])) {
            $model->shortUrl = $map['ShortUrl'];
        }

        return $model;
    }
}
