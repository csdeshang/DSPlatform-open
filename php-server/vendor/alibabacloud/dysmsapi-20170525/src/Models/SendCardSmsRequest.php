<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendCardSmsRequest\cardObjects;

class SendCardSmsRequest extends Model
{
    /**
     * @var cardObjects[]
     */
    public $cardObjects;

    /**
     * @var string
     */
    public $cardTemplateCode;

    /**
     * @var string
     */
    public $digitalTemplateCode;

    /**
     * @var string
     */
    public $digitalTemplateParam;

    /**
     * @var string
     */
    public $fallbackType;

    /**
     * @var string
     */
    public $outId;

    /**
     * @var string
     */
    public $signName;

    /**
     * @var string
     */
    public $smsTemplateCode;

    /**
     * @var string
     */
    public $smsTemplateParam;

    /**
     * @var string
     */
    public $smsUpExtendCode;

    /**
     * @var string
     */
    public $templateCode;

    /**
     * @var string
     */
    public $templateParam;
    protected $_name = [
        'cardObjects' => 'CardObjects',
        'cardTemplateCode' => 'CardTemplateCode',
        'digitalTemplateCode' => 'DigitalTemplateCode',
        'digitalTemplateParam' => 'DigitalTemplateParam',
        'fallbackType' => 'FallbackType',
        'outId' => 'OutId',
        'signName' => 'SignName',
        'smsTemplateCode' => 'SmsTemplateCode',
        'smsTemplateParam' => 'SmsTemplateParam',
        'smsUpExtendCode' => 'SmsUpExtendCode',
        'templateCode' => 'TemplateCode',
        'templateParam' => 'TemplateParam',
    ];

    public function validate()
    {
        if (\is_array($this->cardObjects)) {
            Model::validateArray($this->cardObjects);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->cardObjects) {
            if (\is_array($this->cardObjects)) {
                $res['CardObjects'] = [];
                $n1 = 0;
                foreach ($this->cardObjects as $item1) {
                    $res['CardObjects'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
                }
            }
        }

        if (null !== $this->cardTemplateCode) {
            $res['CardTemplateCode'] = $this->cardTemplateCode;
        }

        if (null !== $this->digitalTemplateCode) {
            $res['DigitalTemplateCode'] = $this->digitalTemplateCode;
        }

        if (null !== $this->digitalTemplateParam) {
            $res['DigitalTemplateParam'] = $this->digitalTemplateParam;
        }

        if (null !== $this->fallbackType) {
            $res['FallbackType'] = $this->fallbackType;
        }

        if (null !== $this->outId) {
            $res['OutId'] = $this->outId;
        }

        if (null !== $this->signName) {
            $res['SignName'] = $this->signName;
        }

        if (null !== $this->smsTemplateCode) {
            $res['SmsTemplateCode'] = $this->smsTemplateCode;
        }

        if (null !== $this->smsTemplateParam) {
            $res['SmsTemplateParam'] = $this->smsTemplateParam;
        }

        if (null !== $this->smsUpExtendCode) {
            $res['SmsUpExtendCode'] = $this->smsUpExtendCode;
        }

        if (null !== $this->templateCode) {
            $res['TemplateCode'] = $this->templateCode;
        }

        if (null !== $this->templateParam) {
            $res['TemplateParam'] = $this->templateParam;
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
        if (isset($map['CardObjects'])) {
            if (!empty($map['CardObjects'])) {
                $model->cardObjects = [];
                $n1 = 0;
                foreach ($map['CardObjects'] as $item1) {
                    $model->cardObjects[$n1++] = cardObjects::fromMap($item1);
                }
            }
        }

        if (isset($map['CardTemplateCode'])) {
            $model->cardTemplateCode = $map['CardTemplateCode'];
        }

        if (isset($map['DigitalTemplateCode'])) {
            $model->digitalTemplateCode = $map['DigitalTemplateCode'];
        }

        if (isset($map['DigitalTemplateParam'])) {
            $model->digitalTemplateParam = $map['DigitalTemplateParam'];
        }

        if (isset($map['FallbackType'])) {
            $model->fallbackType = $map['FallbackType'];
        }

        if (isset($map['OutId'])) {
            $model->outId = $map['OutId'];
        }

        if (isset($map['SignName'])) {
            $model->signName = $map['SignName'];
        }

        if (isset($map['SmsTemplateCode'])) {
            $model->smsTemplateCode = $map['SmsTemplateCode'];
        }

        if (isset($map['SmsTemplateParam'])) {
            $model->smsTemplateParam = $map['SmsTemplateParam'];
        }

        if (isset($map['SmsUpExtendCode'])) {
            $model->smsUpExtendCode = $map['SmsUpExtendCode'];
        }

        if (isset($map['TemplateCode'])) {
            $model->templateCode = $map['TemplateCode'];
        }

        if (isset($map['TemplateParam'])) {
            $model->templateParam = $map['TemplateParam'];
        }

        return $model;
    }
}
