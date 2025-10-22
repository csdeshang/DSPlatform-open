<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\GetSmsTemplateResponseBody;

use AlibabaCloud\Dara\Model;

class moreDataFileUrlList extends Model
{
    /**
     * @var string[]
     */
    public $moreDataFileUrl;
    protected $_name = [
        'moreDataFileUrl' => 'MoreDataFileUrl',
    ];

    public function validate()
    {
        if (\is_array($this->moreDataFileUrl)) {
            Model::validateArray($this->moreDataFileUrl);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->moreDataFileUrl) {
            if (\is_array($this->moreDataFileUrl)) {
                $res['MoreDataFileUrl'] = [];
                $n1 = 0;
                foreach ($this->moreDataFileUrl as $item1) {
                    $res['MoreDataFileUrl'][$n1++] = $item1;
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
        if (isset($map['MoreDataFileUrl'])) {
            if (!empty($map['MoreDataFileUrl'])) {
                $model->moreDataFileUrl = [];
                $n1 = 0;
                foreach ($map['MoreDataFileUrl'] as $item1) {
                    $model->moreDataFileUrl[$n1++] = $item1;
                }
            }
        }

        return $model;
    }
}
