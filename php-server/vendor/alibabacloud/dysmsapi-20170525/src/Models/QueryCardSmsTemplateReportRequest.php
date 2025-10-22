<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models;

use AlibabaCloud\Dara\Model;

class QueryCardSmsTemplateReportRequest extends Model
{
    /**
     * @var string
     */
    public $endDate;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string[]
     */
    public $templateCodes;
    protected $_name = [
        'endDate' => 'EndDate',
        'startDate' => 'StartDate',
        'templateCodes' => 'TemplateCodes',
    ];

    public function validate()
    {
        if (\is_array($this->templateCodes)) {
            Model::validateArray($this->templateCodes);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->endDate) {
            $res['EndDate'] = $this->endDate;
        }

        if (null !== $this->startDate) {
            $res['StartDate'] = $this->startDate;
        }

        if (null !== $this->templateCodes) {
            if (\is_array($this->templateCodes)) {
                $res['TemplateCodes'] = [];
                $n1 = 0;
                foreach ($this->templateCodes as $item1) {
                    $res['TemplateCodes'][$n1++] = $item1;
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
        if (isset($map['EndDate'])) {
            $model->endDate = $map['EndDate'];
        }

        if (isset($map['StartDate'])) {
            $model->startDate = $map['StartDate'];
        }

        if (isset($map['TemplateCodes'])) {
            if (!empty($map['TemplateCodes'])) {
                $model->templateCodes = [];
                $n1 = 0;
                foreach ($map['TemplateCodes'] as $item1) {
                    $model->templateCodes[$n1++] = $item1;
                }
            }
        }

        return $model;
    }
}
