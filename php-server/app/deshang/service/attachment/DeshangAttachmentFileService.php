<?php

namespace app\deshang\service\attachment;

use app\deshang\exceptions\CommonException;

use app\deshang\service\BaseDeshangService;
use app\deshang\core\ThirdPartyLoader;

use app\deshang\service\attachment\DeshangAttachmentCateService;

use app\common\dao\attachment\AttachmentFileDao;
use app\common\dao\attachment\AttachmentCateDao;

/**
 * 附件文件
 */
class DeshangAttachmentFileService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new AttachmentFileDao();
    }


    /**
     * 处理图片上传的函数
     * 初始化上传类并执行图片上传，然后显示上传的图片信息
     * 
     * @param string $dir 图片上传的目录
     */
    public function uploadFile($data,$type)
    {

        $scene = $data['user_scene'] == 0 ? 'admin' : 'user';
        $dir = 'attachment/' . $scene . '/' . $data['user_id'] . '/' . $type . '/' . date('Ym') . '/' . date('d');

        if ($data['cid'] > 0) {
            //分类是否属于此用户
            $cate_dao = new AttachmentCateDao();
            $cate_info = $cate_dao->getAttachmentCateInfo(['id' => $data['cid'], 'user_id' => $data['user_id'], 'user_scene' => $data['user_scene']]);
            if (empty($cate_info)) {
                throw new CommonException('分类不存在');
            }
        }

        //上传对于的存储类
        $StorageClass = ThirdPartyLoader::upload();

        $file_name = 'file';
        //读取上传文件 验证文件
        $StorageClass->readUpload($file_name);
 
        // 缓存文件信息，避免重复调用 getFileInfo()
        $fileInfo = $StorageClass->getFileInfo();
        $file_ext = $fileInfo['ext'];
        $fileSize = $fileInfo['size'];
        $realPath = $fileInfo['realPath'];

        // 优化验证顺序：先做快速验证（扩展名、大小），再做慢速验证（内容）
        // 1. 快速验证：扩展名白名单
        if($type == 'image'){
            $allowed_exts = ['jpg','jpeg','png','gif'];
            if(!in_array($file_ext, $allowed_exts)){
                throw new CommonException('图片格式错误，仅支持jpg、jpeg、png、gif格式');
            }
        } elseif($type == 'video'){
            $allowed_exts = ['mp4','avi','mov','wmv'];
            if(!in_array($file_ext, $allowed_exts)){
                throw new CommonException('视频格式错误，仅支持mp4、avi、mov、wmv格式');
            }
        }

        // 2. 快速验证：文件大小限制（单位：字节）
        $maxSize = $type == 'image' ? 10 * 1024 * 1024 : 100 * 1024 * 1024; // 图片10MB，视频100MB
        if($fileSize > $maxSize){
            throw new CommonException('文件大小超过限制，图片最大10MB，视频最大100MB');
        }

        // 3. 慢速验证：文件内容验证（在快速验证通过后才执行）
        if($type == 'image'){
            // 验证文件内容 - 检查是否为真实图片
            $imageInfo = @getimagesize($realPath);
            if($imageInfo === false){
                throw new CommonException('文件不是有效的图片格式，请上传真实的图片文件');
            }
            
            // 验证 MIME 类型：只要文件内容是真实图片，且 MIME 类型是允许的图片类型，就允许上传
            // 不要求扩展名与 MIME 类型严格匹配（允许用户修改扩展名）
            $allowed_mimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/x-ms-bmp'];
            $mimeType = strtolower($imageInfo['mime']);
            if(!in_array($mimeType, $allowed_mimes)){
                throw new CommonException('图片MIME类型不匹配，文件可能被篡改');
            }
        } elseif($type == 'video'){
            // 验证文件内容 - 检查文件头（Magic Bytes），只读取必要字节
            $fileHandle = @fopen($realPath, 'rb');
            if($fileHandle === false){
                throw new CommonException('无法读取文件内容');
            }
            
            // 根据扩展名只读取必要的字节数，优化性能
            $readBytes = ($file_ext == 'wmv') ? 8 : (($file_ext == 'mp4' || $file_ext == 'mov') ? 8 : 4);
            $header = fread($fileHandle, $readBytes);
            fclose($fileHandle);
            
            // 验证视频文件头（使用更高效的比较方式）
            $isValid = false;
            if($file_ext == 'mp4' || $file_ext == 'mov'){
                // MP4/MOV: 前4字节是大小，第5-8字节是 'ftyp'
                if(strlen($header) >= 8 && substr($header, 4, 4) === 'ftyp'){
                    $isValid = true;
                }
            } elseif($file_ext == 'avi'){
                // AVI: 前4字节是 'RIFF'
                if(strlen($header) >= 4 && substr($header, 0, 4) === 'RIFF'){
                    $isValid = true;
                }
            } elseif($file_ext == 'wmv'){
                // WMV: 前8字节是特定标识（使用二进制比较）
                if(strlen($header) >= 8 && $header === "\x30\x26\xB2\x75\x8E\x66\xCF\x11"){
                    $isValid = true;
                }
            }
            
            if(!$isValid){
                throw new CommonException('文件不是有效的视频格式，请上传真实的视频文件');
            }
        }

        //上传
        $StorageClass->upload($dir);

        $path = $dir . '/' . $StorageClass->getSaveName();


        $add_data = array(
            'cid' => $data['cid'],
            'user_id' => $data['user_id'],
            'user_scene' => $data['user_scene'],
            'type' => $type,
            'name' => date('YmdHis', TIMESTAMP),
            'path' => $path,
            'size' => $StorageClass->getFileInfo()['size'],
            'upload_type' => $StorageClass->getDriverName(),
            'create_at' => TIMESTAMP,
        );

        $attachment_file_id = $this->dao->createAttachmentFile($add_data);

        $result = $add_data;
        $result['id'] = $attachment_file_id;
        $result['path'] = $path;


        return $result;
    }




    public function getAttachmentFilePages($data)
    {

        $condition = array();
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];
        $condition[] = ['type', '=', $data['type']];


        if ($data['cid'] !== '') {

            //获取子分类id以及当前id数组
            $category_ids = $data['cid'] == 0
                ? [0]
                : array_merge(
                    (new DeshangAttachmentCateService)->getComAttachmentCateSubIds($data['cid']),
                    [(int)$data['cid']]
                );

            $condition[] = ['cid', 'in', $category_ids];
        }

        if (!empty($data['name'])) {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }

        $order = isset($data['attachment_sort_order']) ? $data['attachment_sort_order'] : 'create_at desc';
        
 

        $result = $this->dao->getAttachmentFilePages($condition, '*', $order);


        return $result;
    }


    public function updateBatchAttachmentFile($data)
    {
        $update = array();
        if (!empty($data['name'])) {
            $update['name'] = $data['name'];
        }

        //判断cid 是否存在
        if ($data['cid'] > 0) {

            //分类是否属于此用户
            $cate_dao = new AttachmentCateDao();
            $cate_info = $cate_dao->getAttachmentCateInfo(['id' => $data['cid'], 'user_id' => $data['user_id'], 'user_scene' => $data['user_scene']]);
            if (empty($cate_info)) {
                throw new CommonException('分类不存在');
            }

            $update['cid'] = $data['cid'];
        }

        if (empty($update)) {
            throw new CommonException('updateBatchAttachmentFile update data is empty');
        }



        $condition = array();
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];
        $condition[] = ['id', 'in', $data['ids']];

        $result = $this->dao->updateAttachmentFile($condition, $update);

        return $result;
    }

    public function deleteBatchAttachmentFile($data)
    {

        $condition = array();
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];
        $condition[] = ['id', 'in', $data['ids']];

        // 获取删除的文件路径列表
        $path_list = $this->dao->getAttachmentFileColumn($condition, 'path');

        if (!empty($path_list)) {
            //对于的存储类
            
            $StorageClass = ThirdPartyLoader::upload();

            $StorageClass->delete($path_list);
            $result = $this->dao->deleteAttachmentFile($condition);
            return $result;
        }
        return false;
    }


}
