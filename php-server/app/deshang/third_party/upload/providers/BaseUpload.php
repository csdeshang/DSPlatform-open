<?php

namespace app\deshang\third_party\upload\providers;

use app\deshang\exceptions\CommonException;

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
        // 文件信息 - 统一扩展名为小写，防止大小写绕过
        $this->fileInfo = [
            'ext'      => strtolower($this->file->extension()),
            'size'     => $this->file->getSize(),
            'name'     => $this->file->getOriginalName(),
            'realPath' => $this->file->getRealPath(),
        ];

    }


    public function randomSaveName()
    {
        // 确保扩展名在允许列表中，防止恶意扩展名
        $ext = strtolower($this->fileInfo['ext']);
        $allowed = ['jpg','jpeg','png','gif','mp4','avi','mov','wmv'];
        
        if(!in_array($ext, $allowed)){
            throw new CommonException('不允许的文件扩展名');
        }
        
        $this->save_name = date('YmdHis') . uniqid() . '.' . $ext;
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
