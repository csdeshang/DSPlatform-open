<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ListTagResourcesResponseBody;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\ListTagResourcesResponseBody\tagResources\tagResource;

class tagResources extends Model
{
    /**
     * @var tagResource[]
     */
    public $tagResource;
    protected $_name = [
        'tagResource' => 'TagResource',
    ];

    public function validate()
    {
        if (\is_array($this->tagResource)) {
            Model::validateArray($this->tagResource);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->tagResource) {
            if (\is_array($this->tagResource)) {
                $res['TagResource'] = [];
                $n1 = 0;
                foreach ($this->tagResource as $item1) {
                    $res['TagResource'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
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
        if (isset($map['TagResource'])) {
            if (!empty($map['TagResource'])) {
                $model->tagResource = [];
                $n1 = 0;
                foreach ($map['TagResource'] as $item1) {
                    $model->tagResource[$n1++] = tagResource::fromMap($item1);
                }
            }
        }

        return $model;
    }
}
