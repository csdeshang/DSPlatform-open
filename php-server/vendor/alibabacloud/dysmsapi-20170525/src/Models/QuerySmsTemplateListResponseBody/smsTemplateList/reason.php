<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySmsTemplateListResponseBody\smsTemplateList;

use AlibabaCloud\Dara\Model;

class reason extends Model
{
    /**
     * @var string
     */
    public $rejectDate;

    /**
     * @var string
     */
    public $rejectInfo;

    /**
     * @var string
     */
    public $rejectSubInfo;
    protected $_name = [
        'rejectDate' => 'RejectDate',
        'rejectInfo' => 'RejectInfo',
        'rejectSubInfo' => 'RejectSubInfo',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->rejectDate) {
            $res['RejectDate'] = $this->rejectDate;
        }

        if (null !== $this->rejectInfo) {
            $res['RejectInfo'] = $this->rejectInfo;
        }

        if (null !== $this->rejectSubInfo) {
            $res['RejectSubInfo'] = $this->rejectSubInfo;
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
        if (isset($map['RejectDate'])) {
            $model->rejectDate = $map['RejectDate'];
        }

        if (isset($map['RejectInfo'])) {
            $model->rejectInfo = $map['RejectInfo'];
        }

        if (isset($map['RejectSubInfo'])) {
            $model->rejectSubInfo = $map['RejectSubInfo'];
        }

        return $model;
    }
}
