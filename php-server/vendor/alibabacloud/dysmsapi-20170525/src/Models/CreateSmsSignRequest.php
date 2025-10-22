<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;

class CreateSmsSignRequest extends Model
{
    /**
     * @var string
     */
    public $applySceneContent;

    /**
     * @var string[]
     */
    public $moreData;

    /**
     * @var int
     */
    public $ownerId;

    /**
     * @var int
     */
    public $qualificationId;

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
    public $signName;

    /**
     * @var int
     */
    public $signSource;

    /**
     * @var int
     */
    public $signType;

    /**
     * @var bool
     */
    public $thirdParty;
    protected $_name = [
        'applySceneContent' => 'ApplySceneContent',
        'moreData' => 'MoreData',
        'ownerId' => 'OwnerId',
        'qualificationId' => 'QualificationId',
        'remark' => 'Remark',
        'resourceOwnerAccount' => 'ResourceOwnerAccount',
        'resourceOwnerId' => 'ResourceOwnerId',
        'signName' => 'SignName',
        'signSource' => 'SignSource',
        'signType' => 'SignType',
        'thirdParty' => 'ThirdParty',
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

        if (null !== $this->qualificationId) {
            $res['QualificationId'] = $this->qualificationId;
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

        if (null !== $this->signName) {
            $res['SignName'] = $this->signName;
        }

        if (null !== $this->signSource) {
            $res['SignSource'] = $this->signSource;
        }

        if (null !== $this->signType) {
            $res['SignType'] = $this->signType;
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
        if (isset($map['ApplySceneContent'])) {
            $model->applySceneContent = $map['ApplySceneContent'];
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

        if (isset($map['QualificationId'])) {
            $model->qualificationId = $map['QualificationId'];
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

        if (isset($map['SignName'])) {
            $model->signName = $map['SignName'];
        }

        if (isset($map['SignSource'])) {
            $model->signSource = $map['SignSource'];
        }

        if (isset($map['SignType'])) {
            $model->signType = $map['SignType'];
        }

        if (isset($map['ThirdParty'])) {
            $model->thirdParty = $map['ThirdParty'];
        }

        return $model;
    }
}
