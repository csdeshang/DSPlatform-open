<?php

namespace app\deshang\third_party\upload\providers;



abstract class BaseUpload
{


    protected $file;
    // 上传文件信息
    public $fileInfo;
    public $save_name;


    protected function __construct() {}


    public function readUpload($file_name)
    {
        $this->file = request()->file($file_name);
        // 文件信息
        $this->fileInfo = [
            'ext'      => $this->file->extension(),
            'size'     => $this->file->getSize(),
            'name'     => $this->file->getOriginalName(),
            'realPath' => $this->file->getRealPath(),
        ];

    }


    public function randomSaveName()
    {
        $this->save_name = date('YmdHis') . uniqid() . '.' . $this->fileInfo['ext'];
        return $this->save_name;
    }


    public function getSaveName()
    {
        return $this->save_name;
    }

    public function getFileInfo()
    {
        return $this->fileInfo;
    }
}
