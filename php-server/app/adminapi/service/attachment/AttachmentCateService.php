<?php

namespace app\adminapi\service\attachment;

use app\deshang\base\service\BaseAdminService;
use app\deshang\service\attachment\DeshangAttachmentCateService;

use app\common\enum\attachment\AttachmentEnum;

/**
 * 附件管理
 */
class AttachmentCateService extends BaseAdminService
{

    
    public function getAttachmentCateList($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentCateService())->getAttachmentCateList($data);
    }

    public function getAttachmentCateInfo($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentCateService())->getAttachmentCateInfo($data);
    }

    public function createAttachmentCate($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentCateService())->createAttachmentCate($data);
    }

    public function updateAttachmentCate($data){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        return (new DeshangAttachmentCateService())->editAttachmentCate($data);
    }

    public function deleteAttachmentCate($id){
        $data['user_id'] = $this->user_id;
        $data['user_scene'] = AttachmentEnum::USER_SCENE_ADMIN; // 0 admin 1 user
        $data['id'] = $id;
        return (new DeshangAttachmentCateService())->delAttachmentCate($data);
    }

}