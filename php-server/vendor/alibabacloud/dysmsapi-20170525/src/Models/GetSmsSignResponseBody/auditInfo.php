<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsSignResponseBody;

use AlibabaCloud\Dara\Model;

class auditInfo extends Model
{
    /**
     * @var string
     */
    public $auditDate;

    /**
     * @var string
     */
    public $rejectInfo;
    protected $_name = [
        'auditDate' => 'AuditDate',
        'rejectInfo' => 'RejectInfo',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->auditDate) {
            $res['AuditDate'] = $this->auditDate;
        }

        if (null !== $this->rejectInfo) {
            $res['RejectInfo'] = $this->rejectInfo;
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
        if (isset($map['AuditDate'])) {
            $model->auditDate = $map['AuditDate'];
        }

        if (isset($map['RejectInfo'])) {
            $model->rejectInfo = $map['RejectInfo'];
        }

        return $model;
    }
}
