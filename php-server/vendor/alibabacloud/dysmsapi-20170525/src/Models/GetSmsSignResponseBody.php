<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsSignResponseBody\auditInfo;

class GetSmsSignResponseBody extends Model
{
    /**
     * @var string
     */
    public $applyScene;

    /**
     * @var auditInfo
     */
    public $auditInfo;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $createDate;

    /**
     * @var string[]
     */
    public $fileUrlList;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var int
     */
    public $qualificationId;

    /**
     * @var int
     */
    public $registerResult;

    /**
     * @var string
     */
    public $remark;

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $signCode;

    /**
     * @var string
     */
    public $signName;

    /**
     * @var int
     */
    public $signStatus;

    /**
     * @var string
     */
    public $signTag;

    /**
     * @var string
     */
    public $signUsage;

    /**
     * @var bool
     */
    public $thirdParty;
    protected $_name = [
        'applyScene' => 'ApplyScene',
        'auditInfo' => 'AuditInfo',
        'code' => 'Code',
        'createDate' => 'CreateDate',
        'fileUrlList' => 'FileUrlList',
        'message' => 'Message',
        'orderId' => 'OrderId',
        'qualificationId' => 'QualificationId',
        'registerResult' => 'RegisterResult',
        'remark' => 'Remark',
        'requestId' => 'RequestId',
        'signCode' => 'SignCode',
        'signName' => 'SignName',
        'signStatus' => 'SignStatus',
        'signTag' => 'SignTag',
        'signUsage' => 'SignUsage',
        'thirdParty' => 'ThirdParty',
    ];

    public function validate()
    {
        if (null !== $this->auditInfo) {
            $this->auditInfo->validate();
        }
        if (\is_array($this->fileUrlList)) {
            Model::validateArray($this->fileUrlList);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->applyScene) {
            $res['ApplyScene'] = $this->applyScene;
        }

        if (null !== $this->auditInfo) {
            $res['AuditInfo'] = null !== $this->auditInfo ? $this->auditInfo->toArray($noStream) : $this->auditInfo;
        }

        if (null !== $this->code) {
            $res['Code'] = $this->code;
        }

        if (null !== $this->createDate) {
            $res['CreateDate'] = $this->createDate;
        }

        if (null !== $this->fileUrlList) {
            if (\is_array($this->fileUrlList)) {
                $res['FileUrlList'] = [];
                $n1 = 0;
                foreach ($this->fileUrlList as $item1) {
                    $res['FileUrlList'][$n1++] = $item1;
                }
            }
        }

        if (null !== $this->message) {
            $res['Message'] = $this->message;
        }

        if (null !== $this->orderId) {
            $res['OrderId'] = $this->orderId;
        }

        if (null !== $this->qualificationId) {
            $res['QualificationId'] = $this->qualificationId;
        }

        if (null !== $this->registerResult) {
            $res['RegisterResult'] = $this->registerResult;
        }

        if (null !== $this->remark) {
            $res['Remark'] = $this->remark;
        }

        if (null !== $this->requestId) {
            $res['RequestId'] = $this->requestId;
        }

        if (null !== $this->signCode) {
            $res['SignCode'] = $this->signCode;
        }

        if (null !== $this->signName) {
            $res['SignName'] = $this->signName;
        }

        if (null !== $this->signStatus) {
            $res['SignStatus'] = $this->signStatus;
        }

        if (null !== $this->signTag) {
            $res['SignTag'] = $this->signTag;
        }

        if (null !== $this->signUsage) {
            $res['SignUsage'] = $this->signUsage;
        }

        if (null !== $this->thirdParty) {
            $res['ThirdParty'] = $this->thirdParty;
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
        if (isset($map['ApplyScene'])) {
            $model->applyScene = $map['ApplyScene'];
        }

        if (isset($map['AuditInfo'])) {
            $model->auditInfo = auditInfo::fromMap($map['AuditInfo']);
        }

        if (isset($map['Code'])) {
            $model->code = $map['Code'];
        }

        if (isset($map['CreateDate'])) {
            $model->createDate = $map['CreateDate'];
        }

        if (isset($map['FileUrlList'])) {
            if (!empty($map['FileUrlList'])) {
                $model->fileUrlList = [];
                $n1 = 0;
                foreach ($map['FileUrlList'] as $item1) {
                    $model->fileUrlList[$n1++] = $item1;
                }
            }
        }

        if (isset($map['Message'])) {
            $model->message = $map['Message'];
        }

        if (isset($map['OrderId'])) {
            $model->orderId = $map['OrderId'];
        }

        if (isset($map['QualificationId'])) {
            $model->qualificationId = $map['QualificationId'];
        }

        if (isset($map['RegisterResult'])) {
            $model->registerResult = $map['RegisterResult'];
        }

        if (isset($map['Remark'])) {
            $model->remark = $map['Remark'];
        }

        if (isset($map['RequestId'])) {
            $model->requestId = $map['RequestId'];
        }

        if (isset($map['SignCode'])) {
            $model->signCode = $map['SignCode'];
        }

        if (isset($map['SignName'])) {
            $model->signName = $map['SignName'];
        }

        if (isset($map['SignStatus'])) {
            $model->signStatus = $map['SignStatus'];
        }

        if (isset($map['SignTag'])) {
            $model->signTag = $map['SignTag'];
        }

        if (isset($map['SignUsage'])) {
            $model->signUsage = $map['SignUsage'];
        }

        if (isset($map['ThirdParty'])) {
            $model->thirdParty = $map['ThirdParty'];
        }

        return $model;
    }
}
