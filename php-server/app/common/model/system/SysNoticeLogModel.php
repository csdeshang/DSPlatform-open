<?php

namespace app\common\model\system;

use app\deshang\base\BaseModel;

use app\common\enum\system\SysNoticeEnum;

class SysNoticeLogModel extends BaseModel{
    /**

     * 模型名称
     * @var string
     */
    protected $name = 'sys_notice_log';




    // 通知类型描述 获取器
    public function getNoticeChannelDescAttr($value, $data)
    {
        return SysNoticeEnum::getChannelDesc($data['notice_channel']);
    }

    // 发送状态描述 获取器
    public function getSendStatusDescAttr($value, $data)
    {
        return SysNoticeEnum::getSendStatusDesc($data['send_status']);
    }


    

}