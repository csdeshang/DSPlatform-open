<?php

namespace app\common\enum\editable;

class EditablePageEnum
{
    // 类型
    const TYPE_HOME = 'home'; // 首页
    const TYPE_TOPIC = 'topic'; // 专题页


    /**
     * 获取可编辑页面类型列表
     * @return array
     */
    public static function getEditablePageTypeDict(): array
    {
        return [
            self::TYPE_HOME => '首页',
            self::TYPE_TOPIC => '专题页',

        ];
    }

    /**
     * 获取可编辑页面类型描述
     * @param string $value
     * @return string
     */
    public static function getEditablePageTypeDesc($value): string
    {
        $data = self::getEditablePageTypeDict();
        return $data[$value] ?? '未知类型';
    }




    
}
