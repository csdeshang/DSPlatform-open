<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsResponseBody;

use AlibabaCloud\Dara\Model;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\QuerySendDetailsResponseBody\smsSendDetailDTOs\smsSendDetailDTO;

class smsSendDetailDTOs extends Model
{
    /**
     * @var smsSendDetailDTO[]
     */
    public $smsSendDetailDTO;
    protected $_name = [
        'smsSendDetailDTO' => 'SmsSendDetailDTO',
    ];

    public function validate()
    {
        if (\is_array($this->smsSendDetailDTO)) {
            Model::validateArray($this->smsSendDetailDTO);
        }
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->smsSendDetailDTO) {
            if (\is_array($this->smsSendDetailDTO)) {
                $res['SmsSendDetailDTO'] = [];
                $n1 = 0;
                foreach ($this->smsSendDetailDTO as $item1) {
                    $res['SmsSendDetailDTO'][$n1++] = null !== $item1 ? $item1->toArray($noStream) : $item1;
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
        if (isset($map['SmsSendDetailDTO'])) {
            if (!empty($map['SmsSendDetailDTO'])) {
                $model->smsSendDetailDTO = [];
                $n1 = 0;
                foreach ($map['SmsSendDetailDTO'] as $item1) {
                    $model->smsSendDetailDTO[$n1++] = smsSendDetailDTO::fromMap($item1);
                }
            }
        }

        return $model;
    }
}
