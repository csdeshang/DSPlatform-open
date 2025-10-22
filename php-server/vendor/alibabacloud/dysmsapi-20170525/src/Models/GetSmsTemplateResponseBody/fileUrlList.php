<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponseBody;

use AlibabaCloud\Dara\Model;

class fileUrlList extends Model
{
    /**
     * @var string[]
     */
    public $fileUrl;
    protected $_name = [
        'fileUrl' => 'FileUrl',
    ];

    public function validate()
    {
        if (\is_array($this->fileUrl)) {
            Model::validateArray($this->fileUrl);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->fileUrl) {
            if (\is_array($this->fileUrl)) {
                $res['FileUrl'] = [];
                $n1 = 0;
                foreach ($this->fileUrl as $item1) {
                    $res['FileUrl'][$n1++] = $item1;
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
        if (isset($map['FileUrl'])) {
            if (!empty($map['FileUrl'])) {
                $model->fileUrl = [];
                $n1 = 0;
                foreach ($map['FileUrl'] as $item1) {
                    $model->fileUrl[$n1++] = $item1;
                }
            }
        }

        return $model;
    }
}
