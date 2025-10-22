<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CheckMobilesCardSupportResponseBody;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\CheckMobilesCardSupportResponseBody\data\queryResult;

class data extends Model
{
    /**
     * @var queryResult[]
     */
    public $queryResult;
    protected $_name = [
        'queryResult' => 'queryResult',
    ];

    public function validate()
    {
        if (\is_array($this->queryResult)) {
            Model::validateArray($this->queryResult);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->queryResult) {
            if (\is_array($this->queryResult)) {
                $res['queryResult'] = [];
                $n1 = 0;
                foreach ($this->queryResult as $item1) {
                    $res['queryResult'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
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
        if (isset($map['queryResult'])) {
            if (!empty($map['queryResult'])) {
                $model->queryResult = [];
                $n1 = 0;
                foreach ($map['queryResult'] as $item1) {
                    $model->queryResult[$n1++] = queryResult::fromMap($item1);
                }
            }
        }

        return $model;
    }
}
