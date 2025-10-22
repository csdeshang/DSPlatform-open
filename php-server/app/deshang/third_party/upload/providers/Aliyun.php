<?php

namespace app\deshang\third_party\upload\providers;

use app\deshang\third_party\upload\providers\BaseUpload;
use OSS\OssClient;
use OSS\Core\OssException;
use Exception;

/**
 * 阿里云 OSS 上传  
 * composer require aliyuncs/oss-sdk-php
 * 
 */


class Aliyun extends BaseUpload
{
    protected $config;

    protected $ossClient; // 阿里云 OSS 客户端
    protected $bucket; // 阿里云 OSS 桶名

    public function __construct(array $config)
    {
        parent::__construct();

        $this->config = $config;
        $this->initClient();
    }

    /**
     * 初始化阿里云 OSS 客户端
     */
    protected function initClient()
    {
        // 检查配置
        if (empty($this->config['access_key_id']) || empty($this->config['access_key_secret'])) {
            throw new \Exception('阿里云上传配置不完整，缺少AccessKey');
        }
        if (empty($this->config['endpoint']) || empty($this->config['bucket'])) {
            throw new \Exception('阿里云上传配置不完整，缺少Endpoint或Bucket');
        }


        $this->ossClient = new OssClient($this->config['access_key_id'], $this->config['access_key_secret'], $this->config['endpoint']);
        $this->bucket = $this->config['bucket'];
    }




    /**
     * 上传文件到阿里云 OSS
     * @param string $dir 保存目录
     * @return bool 是否上传成功
     * https://help.aliyun.com/zh/oss/developer-reference/simple-upload
     */
    public function upload($dir)
    {
        if (!$this->file) {
            throw new Exception("文件未上传");
        }

        $this->randomSaveName();
        $object = $dir . '/' . $this->save_name;



        try {
            $this->ossClient->uploadFile($this->bucket, $object, $this->fileInfo['realPath']);
            return true;
        } catch (OssException $e) {
            throw new Exception("文件上传失败: " . $e->getMessage());
        }
    }

    /**
     * 删除阿里云 OSS 上的文件
     * @param array $path_list 文件路径列表
     * @return bool 是否删除成功
     */
    public function delete($path_list)
    {
        foreach ($path_list as $path) {
            try {
                $this->ossClient->deleteObject($this->bucket, $path);
            } catch (OssException $e) {
                throw new Exception("文件删除失败: " . $e->getMessage());
            }
        }
        return true;
    }
}
