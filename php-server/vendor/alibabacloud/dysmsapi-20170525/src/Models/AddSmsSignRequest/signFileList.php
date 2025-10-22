<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddSmsSignRequest;

use AlibabaCloud\Dara\Model;

class signFileList extends Model
{
    /**
     * @var string
     */
    public $fileContents;

    /**
     * @var string
     */
    public $fileSuffix;
    protected $_name = [
        'fileContents' => 'FileContents',
        'fileSuffix' => 'FileSuffix',
    ];

    public function validate()
    {
        parent::validate();
    }

    public function toArray($noStream = false)
    {
        $res = [];
        if (null !== $this->fileContents) {
            $res['FileContents'] = $this->fileContents;
        }

        if (null !== $this->fileSuffix) {
            $res['FileSuffix'] = $this->fileSuffix;
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
        if (isset($map['FileContents'])) {
            $model->fileContents = $map['FileContents'];
        }

        if (isset($map['FileSuffix'])) {
            $model->fileSuffix = $map['FileSuffix'];
        }

        return $model;
    }
}
