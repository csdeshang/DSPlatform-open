<?php

namespace AlibabaCloud\Dara\Models;

use AlibabaCloud\Dara\Model;

class FileField extends Model
{
    public $filename;
    public $contentType;
    public $content;

    public function __construct($config = [])
    {
        $this->_required = [
            'filename'    => true,
            'contentType' => true,
            'content'     => true,
        ];
        parent::__construct($config);
    }
}