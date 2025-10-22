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
 
        //后缀验证
        $file_ext = $StorageClass->getFileInfo()['ext'];

        if($type == 'image'){
            if(!in_array($file_ext,['jpg','jpeg','png','gif'])){
                throw new CommonException('图片格式错误');
            }
        }

        if($type == 'video'){
            if(!in_array($file_ext,['mp4','avi','mov','wmv'])){
                throw new CommonException('视频格式错误');
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
