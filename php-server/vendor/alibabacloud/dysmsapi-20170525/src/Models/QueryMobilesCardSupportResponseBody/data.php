<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportResponseBody;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QueryMobilesCardSupportResponseBody\data\queryResult;

class data extends Model
{
    /**
     * @var queryResult[]
     */
    public $queryResult;
    protected $_name = [
        'queryResult' => 'QueryResult',
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
                $res['QueryResult'] = [];
                $n1 = 0;
                foreach ($this->queryResult as $item1) {
                    $res['QueryResult'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
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
        if (isset($map['QueryResult'])) {
            if (!empty($map['QueryResult'])) {
                $model->queryResult = [];
                $n1 = 0;
                foreach ($map['QueryResult'] as $item1) {
                    $model->queryResult[$n1++] = queryResult::fromMap($item1);
                }
            }
        }

        return $model;
    }
}
