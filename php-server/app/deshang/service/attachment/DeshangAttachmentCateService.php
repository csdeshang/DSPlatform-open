<?php

namespace app\deshang\service\attachment;

use app\deshang\exceptions\CommonException;


use app\deshang\service\BaseDeshangService;

use app\common\dao\attachment\AttachmentCateDao;
use app\common\dao\attachment\AttachmentFileDao;

class DeshangAttachmentCateService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new AttachmentCateDao();
    }

    public function getAttachmentCateList($data){

        $condition = [];
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];
        $condition[] = ['type', '=', $data['type']];

        $order = isset($data['sort_order']) ? $data['sort_order'] : 'sort desc';

        $field = '*';
        return $this->dao->getAttachmentCateList($condition, $field, $order);
    }

    public function getAttachmentCateInfo($data)
    {
        
        $condition = [];
        $condition[] = ['id', '=', $data['id']];
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];


        $field = '*';
        return $this->dao->getAttachmentCateInfo($condition, $field);
    }
    

    public function createAttachmentCate($data){

        $result = $this->dao->createAttachmentCate($data);
        return $result;
    }

    public function editAttachmentCate($data){
        $update = array();
        $update['name'] = $data['name'];

        $condition = array();
        $condition['id'] = $data['id'];
        $condition['user_id'] = $data['user_id'];
        $condition['user_scene'] = $data['user_scene'];

        $result = $this->dao->updateAttachmentCate($condition, $update);
        return $result;

    }

    public function delAttachmentCate($data){

        //判断是否有下级
        $has_child = $this->dao->getAttachmentCateCount(['pid' => $data['id']]);
        if ($has_child > 0) {
            throw new CommonException('该分类下有子分类，不能删除');
        }

        $condition = [];
        $condition[] = ['id', '=', $data['id']];
        $condition[] = ['user_id', '=', $data['user_id']];
        $condition[] = ['user_scene', '=', $data['user_scene']];
        
        $result = $this->dao->deleteAttachmentCate($condition);

        //附件分类下的文件全部归到默认分类下
        $file_dao = new AttachmentFileDao();
        $file_dao->updateAttachmentFile(['cid' => $data['id']], ['cid' => 0]);


        return $result;
    }

    //获取分类下所有子分类id
    public function getComAttachmentCateSubIds($parentId, array $cateArr = [])
    {

        $childIds = $this->dao->getAttachmentCateColumn(['pid' => $parentId], 'id');


        if (empty($childIds)) {
            return $childIds;
        } else {
            $allChildIds = $childIds;
            foreach ($childIds as $childId) {
                $allChildIds = array_merge($allChildIds, $this->getComAttachmentCateSubIds($childId, $cateArr));
            }
            return $allChildIds;
        }
    }




}