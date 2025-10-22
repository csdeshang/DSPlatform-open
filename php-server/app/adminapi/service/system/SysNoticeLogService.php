<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\system\SysNoticeLogDao;

use app\common\enum\system\SysNoticeEnum;
use app\deshang\utils\SearchHelper;


class SysNoticeLogService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
    }

    public function getSysNoticeLogPages($data)
    {
        $condition = [];
        if(!empty($data['user_id'])){
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        if(!empty($data['key'])){
            $condition[] = ['key', '=', $data['key']];
        }
        if(!empty($data['notice_channel'])){
            $condition[] = ['notice_channel', '=', $data['notice_channel']];
        }
        if(!empty($data['receiver'])){
            $condition[] = ['receiver', '=', $data['receiver']];
        }
        if(!empty($data['title'])){
            $condition[] = ['title', 'like', '%'.$data['title'].'%'];
        }
        if(!empty($data['is_read'])){
            $condition[] = ['is_read', '=', $data['is_read']];
        }
        if(array_key_exists($data['send_status'], SysNoticeEnum::getSendStatusDict())){
            $condition[] = ['send_status', '=', $data['send_status']];
        }




        $result = (new SysNoticeLogDao())->getSysNoticeLogPages($condition);
        return $result;
    }


    public function getSysNoticeLogInfo($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysNoticeLogDao())->getSysNoticeLogInfo($condition);
        return $result;
    }
}
