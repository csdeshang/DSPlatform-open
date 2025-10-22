<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsSignRequest\signFileList;

class AddSmsSignRequest extends Model
{
    /**
     * @var int
     */
    public $ownerId;

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
     * @var signFileList[]
     */
    public $signFileList;

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
    protected $_name = [
        'ownerId' => 'OwnerId',
        'remark' => 'Remark',
        'resourceOwnerAccount' => 'ResourceOwnerAccount',
        'resourceOwnerId' => 'ResourceOwnerId',
        'signFileList' => 'SignFileList',
        'signName' => 'SignName',
        'signSource' => 'SignSource',
        'signType' => 'SignType',
    ];

    public function validate()
    {
        if (\is_array($this->signFileList)) {
            Model::validateArray($this->signFileList);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->ownerId) {
            $res['OwnerId'] = $this->ownerId;
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

        if (null !== $this->signFileList) {
            if (\is_array($this->signFileList)) {
                $res['SignFileList'] = [];
                $n1 = 0;
                foreach ($this->signFileList as $item1) {
                    $res['SignFileList'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
                }
            }
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

        return $res;
    }

    public function toMap($noStream = false)
    {
        return $this->toArray($noStream);
    }

    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['OwnerId'])) {
            $model->ownerId = $map['OwnerId'];
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

        if (isset($map['SignFileList'])) {
            if (!empty($map['SignFileList'])) {
                $model->signFileList = [];
                $n1 = 0;
                foreach ($map['SignFileList'] as $item1) {
                    $model->signFileList[$n1++] = signFileList::fromMap($item1);
                }
            }
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

        return $model;
    }
}
