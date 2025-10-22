<?php

namespace app\adminapi\service\attachment;

use app\deshang\base\service\BaseAdminService;
use app\deshang\service\attachment\DeshangAttachmentFileService;

use app\common\enum\attachment\AttachmentEnum;

/**
 * 附件管理
 */
class AttachmentFileService extends BaseAdminService
{

    public function image($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        // ATTACHMENT_TYPE_IMAGE 图片
        return (new DeshangAttachmentFileService())->uploadFile($data,AttachmentEnum::ATTACHMENT_TYPE_IMAGE);
    }

    public function video($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        // ATTACHMENT_TYPE_VIDEO 视频
        return (new DeshangAttachmentFileService())->uploadFile($data,AttachmentEnum::ATTACHMENT_TYPE_VIDEO);
    }

    public function getAttachmentFilePages($data){
        $data['user_id'] = $this->user_id;
        
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentFileService())->getAttachmentFilePages($data);
    }


    public function updateBatchAttachmentFile($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentFileService())->updateBatchAttachmentFile($data);
    }

    public function deleteBatchAttachmentFile($ids){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        $data['ids'] = $ids;
        return (new DeshangAttachmentFileService())->deleteBatchAttachmentFile($data);
    }

}