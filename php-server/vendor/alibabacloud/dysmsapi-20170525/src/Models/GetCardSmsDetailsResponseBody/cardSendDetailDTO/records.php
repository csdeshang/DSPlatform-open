<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetCardSmsDetailsResponseBody\cardSendDetailDTO;

use AlibabaCloud\Dara\Model;

class records extends Model
{
    /**
     * @var string
     */
    public $errCode;

    /**
     * @var string
     */
    public $outId;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $receiveDate;

    /**
     * @var string
     */
    public $receiveType;

    /**
     * @var string
     */
    public $renderDate;

    /**
     * @var int
     */
    public $renderStatus;

    /**
     * @var string
     */
    public $sendDate;

    /**
     * @var int
     */
    public $sendStatus;

    /**
     * @var string
     */
    public $smsContent;

    /**
     * @var string
     */
    public $templateCode;
    protected $_name = [
        'errCode' => 'ErrCode',
        'outId' => 'OutId',
        'phoneNumber' => 'PhoneNumber',
        'receiveDate' => 'ReceiveDate',
        'receiveType' => 'ReceiveType',
        'renderDate' => 'RenderDate',
        'renderStatus' => 'RenderStatus',
        'sendDate' => 'SendDate',
        'sendStatus' => 'SendStatus',
        'smsContent' => 'SmsContent',
        'templateCode' => 'TemplateCode',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->errCode) {
            $res['ErrCode'] = $this->errCode;
        }

        if (null !== $this->outId) {
            $res['OutId'] = $this->outId;
        }

        if (null !== $this->phoneNumber) {
            $res['PhoneNumber'] = $this->phoneNumber;
        }

        if (null !== $this->receiveDate) {
            $res['ReceiveDate'] = $this->receiveDate;
        }

        if (null !== $this->receiveType) {
            $res['ReceiveType'] = $this->receiveType;
        }

        if (null !== $this->renderDate) {
            $res['RenderDate'] = $this->renderDate;
        }

        if (null !== $this->renderStatus) {
            $res['RenderStatus'] = $this->renderStatus;
        }

        if (null !== $this->sendDate) {
            $res['SendDate'] = $this->sendDate;
        }

        if (null !== $this->sendStatus) {
            $res['SendStatus'] = $this->sendStatus;
        }

        if (null !== $this->smsContent) {
            $res['SmsContent'] = $this->smsContent;
        }

        if (null !== $this->templateCode) {
            $res['TemplateCode'] = $this->templateCode;
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
        if (isset($map['ErrCode'])) {
            $model->errCode = $map['ErrCode'];
        }

        if (isset($map['OutId'])) {
            $model->outId = $map['OutId'];
        }

        if (isset($map['PhoneNumber'])) {
            $model->phoneNumber = $map['PhoneNumber'];
        }

        if (isset($map['ReceiveDate'])) {
            $model->receiveDate = $map['ReceiveDate'];
        }

        if (isset($map['ReceiveType'])) {
            $model->receiveType = $map['ReceiveType'];
        }

        if (isset($map['RenderDate'])) {
            $model->renderDate = $map['RenderDate'];
        }

        if (isset($map['RenderStatus'])) {
            $model->renderStatus = $map['RenderStatus'];
        }

        if (isset($map['SendDate'])) {
            $model->sendDate = $map['SendDate'];
        }

        if (isset($map['SendStatus'])) {
            $model->sendStatus = $map['SendStatus'];
        }

        if (isset($map['SmsContent'])) {
            $model->smsContent = $map['SmsContent'];
        }

        if (isset($map['TemplateCode'])) {
            $model->templateCode = $map['TemplateCode'];
        }

        return $model;
    }
}
