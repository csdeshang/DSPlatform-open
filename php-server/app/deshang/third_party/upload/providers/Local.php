<?php

namespace app\deshang\third_party\upload\providers;
use app\deshang\third_party\upload\providers\BaseUpload;

class Local extends BaseUpload
{

    public function __construct()
    {
        parent::__construct();
    }

    // 上传文件
    public function upload($dir)
    {
        //未上传成功会报错
        $info = $this->file->move($dir, $this->randomSaveName());
        return true;
    }



    //本地删除图片
    public function delete($path_list) {

        foreach ($path_list as $path) {
            $this->delete_file($path);
        }
    }

    //删除单个文件
    private function delete_file($path) {
        $file_path = public_path(). DIRECTORY_SEPARATOR . $path;
        //非法路径
        if (strpos(realpath($file_path), realpath(public_path())) !== 0) {
            return false;
        }

        // 路径安全检查
        if (strpos($path, '..') !== false) {
            return false;
        }

        if (file_exists($file_path)) {
            return unlink($file_path);
        }

        return false;
    }


}
