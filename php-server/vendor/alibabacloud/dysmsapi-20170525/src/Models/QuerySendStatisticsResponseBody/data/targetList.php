<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendStatisticsResponseBody\data;

use AlibabaCloud\Dara\Model;

class targetList extends Model
{
    /**
     * @var int
     */
    public $noRespondedCount;

    /**
     * @var int
     */
    public $respondedFailCount;

    /**
     * @var int
     */
    public $respondedSuccessCount;

    /**
     * @var string
     */
    public $sendDate;

    /**
     * @var int
     */
    public $totalCount;
    protected $_name = [
        'noRespondedCount' => 'NoRespondedCount',
        'respondedFailCount' => 'RespondedFailCount',
        'respondedSuccessCount' => 'RespondedSuccessCount',
        'sendDate' => 'SendDate',
        'totalCount' => 'TotalCount',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->noRespondedCount) {
            $res['NoRespondedCount'] = $this->noRespondedCount;
        }

        if (null !== $this->respondedFailCount) {
            $res['RespondedFailCount'] = $this->respondedFailCount;
        }

        if (null !== $this->respondedSuccessCount) {
            $res['RespondedSuccessCount'] = $this->respondedSuccessCount;
        }

        if (null !== $this->sendDate) {
            $res['SendDate'] = $this->sendDate;
        }

        if (null !== $this->totalCount) {
            $res['TotalCount'] = $this->totalCount;
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
        if (isset($map['NoRespondedCount'])) {
            $model->noRespondedCount = $map['NoRespondedCount'];
        }

        if (isset($map['RespondedFailCount'])) {
            $model->respondedFailCount = $map['RespondedFailCount'];
        }

        if (isset($map['RespondedSuccessCount'])) {
            $model->respondedSuccessCount = $map['RespondedSuccessCount'];
        }

        if (isset($map['SendDate'])) {
            $model->sendDate = $map['SendDate'];
        }

        if (isset($map['TotalCount'])) {
            $model->totalCount = $map['TotalCount'];
        }

        return $model;
    }
}
