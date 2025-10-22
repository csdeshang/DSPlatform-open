<?php

namespace app\common\model\editable;

use app\deshang\base\BaseModel;
use app\common\enum\editable\EditablePageEnum;
use app\common\model\system\SysPlatformModel;

class EditablePageModel extends BaseModel
{
    /**
     * 模型名称
     * @var string
     */
    protected $name = 'editable_page';


    // 关联平台
    public function platform()
    {
        return $this->hasOne(SysPlatformModel::class, 'platform', 'platform');
    }


    // 类型获取器
    public function getTypeDescAttr($value, $data)
    {
        return EditablePageEnum::getEditablePageTypeDesc($data['type']);
    }

}