<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponseBody\auditInfo;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponseBody\fileUrlList;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponseBody\moreDataFileUrlList;

class GetSmsTemplateResponseBody extends Model
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
     * @var fileUrlList
     */
    public $fileUrlList;

    /**
     * @var int
     */
    public $intlType;

    /**
     * @var string
     */
    public $message;

    /**
     * @var moreDataFileUrlList
     */
    public $moreDataFileUrlList;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $relatedSignName;

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
    public $templateCode;

    /**
     * @var string
     */
    public $templateContent;

    /**
     * @var string
     */
    public $templateName;

    /**
     * @var string
     */
    public $templateStatus;

    /**
     * @var int
     */
    public $templateTag;

    /**
     * @var string
     */
    public $templateType;

    /**
     * @var string
     */
    public $variableAttribute;

    /**
     * @var mixed[]
     */
    public $vendorAuditStatus;
    protected $_name = [
        'applyScene' => 'ApplyScene',
        'auditInfo' => 'AuditInfo',
        'code' => 'Code',
        'createDate' => 'CreateDate',
        'fileUrlList' => 'FileUrlList',
        'intlType' => 'IntlType',
        'message' => 'Message',
        'moreDataFileUrlList' => 'MoreDataFileUrlList',
        'orderId' => 'OrderId',
        'relatedSignName' => 'RelatedSignName',
        'remark' => 'Remark',
        'requestId' => 'RequestId',
        'templateCode' => 'TemplateCode',
        'templateContent' => 'TemplateContent',
        'templateName' => 'TemplateName',
        'templateStatus' => 'TemplateStatus',
        'templateTag' => 'TemplateTag',
        'templateType' => 'TemplateType',
        'variableAttribute' => 'VariableAttribute',
        'vendorAuditStatus' => 'VendorAuditStatus',
    ];

    public function validate()
    {
        if (null !== $this->auditInfo) {
            $this->auditInfo->validate();
        }
        if (null !== $this->fileUrlList) {
            $this->fileUrlList->validate();
        }
        if (null !== $this->moreDataFileUrlList) {
            $this->moreDataFileUrlList->validate();
        }
        if (\is_array($this->vendorAuditStatus)) {
            Model::validateArray($this->vendorAuditStatus);
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
            $res['FileUrlList'] = null !== $this->fileUrlList ? $this->fileUrlList->toArray($noStream) : $this->fileUrlList;
        }

        if (null !== $this->intlType) {
            $res['IntlType'] = $this->intlType;
        }

        if (null !== $this->message) {
            $res['Message'] = $this->message;
        }

        if (null !== $this->moreDataFileUrlList) {
            $res['MoreDataFileUrlList'] = null !== $this->moreDataFileUrlList ? $this->moreDataFileUrlList->toArray($noStream) : $this->moreDataFileUrlList;
        }

        if (null !== $this->orderId) {
            $res['OrderId'] = $this->orderId;
        }

        if (null !== $this->relatedSignName) {
            $res['RelatedSignName'] = $this->relatedSignName;
        }

        if (null !== $this->remark) {
            $res['Remark'] = $this->remark;
        }

        if (null !== $this->requestId) {
            $res['RequestId'] = $this->requestId;
        }

        if (null !== $this->templateCode) {
            $res['TemplateCode'] = $this->templateCode;
        }

        if (null !== $this->templateContent) {
            $res['TemplateContent'] = $this->templateContent;
        }

        if (null !== $this->templateName) {
            $res['TemplateName'] = $this->templateName;
        }

        if (null !== $this->templateStatus) {
            $res['TemplateStatus'] = $this->templateStatus;
        }

        if (null !== $this->templateTag) {
            $res['TemplateTag'] = $this->templateTag;
        }

        if (null !== $this->templateType) {
            $res['TemplateType'] = $this->templateType;
        }

        if (null !== $this->variableAttribute) {
            $res['VariableAttribute'] = $this->variableAttribute;
        }

        if (null !== $this->vendorAuditStatus) {
            if (\is_array($this->vendorAuditStatus)) {
                $res['VendorAuditStatus'] = [];
                foreach ($this->vendorAuditStatus as $key1 => $value1) {
                    $res['VendorAuditStatus'][$key1] = $value1;
                }
            }
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
            $model->fileUrlList = fileUrlList::fromMap($map['FileUrlList']);
        }

        if (isset($map['IntlType'])) {
            $model->intlType = $map['IntlType'];
        }

        if (isset($map['Message'])) {
            $model->message = $map['Message'];
        }

        if (isset($map['MoreDataFileUrlList'])) {
            $model->moreDataFileUrlList = moreDataFileUrlList::fromMap($map['MoreDataFileUrlList']);
        }

        if (isset($map['OrderId'])) {
            $model->orderId = $map['OrderId'];
        }

        if (isset($map['RelatedSignName'])) {
            $model->relatedSignName = $map['RelatedSignName'];
        }

        if (isset($map['Remark'])) {
            $model->remark = $map['Remark'];
        }

        if (isset($map['RequestId'])) {
            $model->requestId = $map['RequestId'];
        }

        if (isset($map['TemplateCode'])) {
            $model->templateCode = $map['TemplateCode'];
        }

        if (isset($map['TemplateContent'])) {
            $model->templateContent = $map['TemplateContent'];
        }

        if (isset($map['TemplateName'])) {
            $model->templateName = $map['TemplateName'];
        }

        if (isset($map['TemplateStatus'])) {
            $model->templateStatus = $map['TemplateStatus'];
        }

        if (isset($map['TemplateTag'])) {
            $model->templateTag = $map['TemplateTag'];
        }

        if (isset($map['TemplateType'])) {
            $model->templateType = $map['TemplateType'];
        }

        if (isset($map['VariableAttribute'])) {
            $model->variableAttribute = $map['VariableAttribute'];
        }

        if (isset($map['VendorAuditStatus'])) {
            if (!empty($map['VendorAuditStatus'])) {
                $model->vendorAuditStatus = [];
                foreach ($map['VendorAuditStatus'] as $key1 => $value1) {
                    $model->vendorAuditStatus[$key1] = $value1;
                }
            }
        }

        return $model;
    }
}
