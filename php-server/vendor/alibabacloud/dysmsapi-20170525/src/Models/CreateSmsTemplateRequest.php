<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;

class CreateSmsTemplateRequest extends Model
{
    /**
     * @var string
     */
    public $applySceneContent;

    /**
     * @var int
     */
    public $intlType;

    /**
     * @var string[]
     */
    public $moreData;

    /**
     * @var int
     */
    public $ownerId;

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
    public $resourceOwnerAccount;

    /**
     * @var int
     */
    public $resourceOwnerId;

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
    public $templateRule;

    /**
     * @var int
     */
    public $templateType;
    protected $_name = [
        'applySceneContent' => 'ApplySceneContent',
        'intlType' => 'IntlType',
        'moreData' => 'MoreData',
        'ownerId' => 'OwnerId',
        'relatedSignName' => 'RelatedSignName',
        'remark' => 'Remark',
        'resourceOwnerAccount' => 'ResourceOwnerAccount',
        'resourceOwnerId' => 'ResourceOwnerId',
        'templateContent' => 'TemplateContent',
        'templateName' => 'TemplateName',
        'templateRule' => 'TemplateRule',
        'templateType' => 'TemplateType',
    ];

    public function validate()
    {
        if (\is_array($this->moreData)) {
            Model::validateArray($this->moreData);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->applySceneContent) {
            $res['ApplySceneContent'] = $this->applySceneContent;
        }

        if (null !== $this->intlType) {
            $res['IntlType'] = $this->intlType;
        }

        if (null !== $this->moreData) {
            if (\is_array($this->moreData)) {
                $res['MoreData'] = [];
                $n1 = 0;
                foreach ($this->moreData as $item1) {
                    $res['MoreData'][$n1++] = $item1;
                }
            }
        }

        if (null !== $this->ownerId) {
            $res['OwnerId'] = $this->ownerId;
        }

        if (null !== $this->relatedSignName) {
            $res['RelatedSignName'] = $this->relatedSignName;
        }

        if (null !== $this->remark) {
            $res['Remark'] = $this->remark;
        }

        if (null !== $this->resourceOwnerAccount) {
            $res['ResourceOwnerAccount'] = $this->resourceOwnerAccount;
        }

        if (null !== $this->resourceOwnerId) {
            $res['ResourceOwnerId'] = $this->resourceOwnerId;
        }

        if (null !== $this->templateContent) {
            $res['TemplateContent'] = $this->templateContent;
        }

        if (null !== $this->templateName) {
            $res['TemplateName'] = $this->templateName;
        }

        if (null !== $this->templateRule) {
            $res['TemplateRule'] = $this->templateRule;
        }

        if (null !== $this->templateType) {
            $res['TemplateType'] = $this->templateType;
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
        if (isset($map['ApplySceneContent'])) {
            $model->applySceneContent = $map['ApplySceneContent'];
        }

        if (isset($map['IntlType'])) {
            $model->intlType = $map['IntlType'];
        }

        if (isset($map['MoreData'])) {
            if (!empty($map['MoreData'])) {
                $model->moreData = [];
                $n1 = 0;
                foreach ($map['MoreData'] as $item1) {
                    $model->moreData[$n1++] = $item1;
                }
            }
        }

        if (isset($map['OwnerId'])) {
            $model->ownerId = $map['OwnerId'];
        }

        if (isset($map['RelatedSignName'])) {
            $model->relatedSignName = $map['RelatedSignName'];
        }

        if (isset($map['Remark'])) {
            $model->remark = $map['Remark'];
        }

        if (isset($map['ResourceOwnerAccount'])) {
            $model->resourceOwnerAccount = $map['ResourceOwnerAccount'];
        }

        if (isset($map['ResourceOwnerId'])) {
            $model->resourceOwnerId = $map['ResourceOwnerId'];
        }

        if (isset($map['TemplateContent'])) {
            $model->templateContent = $map['TemplateContent'];
        }

        if (isset($map['TemplateName'])) {
            $model->templateName = $map['TemplateName'];
        }

        if (isset($map['TemplateRule'])) {
            $model->templateRule = $map['TemplateRule'];
        }

        if (isset($map['TemplateType'])) {
            $model->templateType = $map['TemplateType'];
        }

        return $model;
    }
}
