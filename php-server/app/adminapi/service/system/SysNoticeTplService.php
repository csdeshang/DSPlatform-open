<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\system\SysNoticeTplDao;
use app\deshang\exceptions\CommonException;


// 消息通知模板
class SysNoticeTplService extends BaseAdminService
{

    public function getSysNoticeTplInfo(int $id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new SysNoticeTplDao())->getSysNoticeTplInfo($condition);
        return $result;
    }



    public function getSysNoticeTplList($data)
    {
        $condition = array();
        if (isset($data['receiver_type']) && !empty($data['receiver_type'])) {
            $condition[] = ['receiver_type', '=', $data['receiver_type']];
        }
        $result = (new SysNoticeTplDao())->getSysNoticeTplList($condition);
        return $result;
    }


    public function updateSysNoticeTpl(int $id,array $data):bool
    {

        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new SysNoticeTplDao())->updateSysNoticeTpl($condition,$data);
        return $result;
    }

    /**
     * 切换字段状态（专门用于布尔字段）
     */
    public function toggleSysNoticeTplField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['email_switch', 'sms_switch', 'wechat_official_switch', 'wechat_mini_switch'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new SysNoticeTplDao())->getSysNoticeTplInfo([['id', '=', $id]]);
        if (empty($currentInfo)) {
            throw new CommonException('通知模板不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new SysNoticeTplDao())->updateSysNoticeTpl($condition, $updateData);
        return $result;
    }
}