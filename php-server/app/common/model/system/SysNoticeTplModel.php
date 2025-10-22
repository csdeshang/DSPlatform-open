<?php

namespace app\common\model\system;

use app\deshang\base\BaseModel;
use app\common\enum\system\SysNoticeEnum;


class SysNoticeTplModel extends BaseModel{
    /**

     * 模型名称
     * @var string
     */
    protected $name = 'sys_notice_tpl';


    public function getTemplateTypeDescAttr($value, $data)
    {
        return SysNoticeEnum::getTemplateTypeDesc($data['template_type']);
    }

    public function getReceiverTypeDescAttr($value, $data)
    {
        return SysNoticeEnum::getReceiverTypeDesc($data['receiver_type']);
    }


    

}