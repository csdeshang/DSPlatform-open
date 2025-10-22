<?php

namespace app\adminapi\controller\attachment\validate;


use app\deshang\base\BaseValidate;
use app\common\enum\attachment\AttachmentEnum;

class AttachmentFileValidate extends BaseValidate
{
    protected $rule = [
        'cid' => 'require|integer',
        'name' => 'max:100', // 可选，最大长度为100
        'ids' => 'require|array|isAllNumbers', // ids 必须是数组且只能包含数字
        'attachment_name' => 'require|max:255', // 附件名称必填，最大长度为255
        'type' => 'require|checkType', // 附件类型必填
    ];

    protected $message = [
        'cid.require' => '分类ID不能为空',
        'cid.integer' => '分类ID必须是整数',
        'name.max' => '名称不能超过100个字符',
        'ids.require' => 'ID列表不能为空',
        'ids.array' => 'ID列表必须是数组',
        'ids.isAllNumbers' => 'ID列表只能包含数字',

        'name.require' => '附件名称不能为空',
        'name.max' => '附件名称不能超过255个字符',
        'type.require' => '附件类型不能为空',
    ];

    protected $scene = [
        'pages' => ['name', 'type'],
        'image' => ['cid'],
        'video' => ['cid'],
        'updateBatch' => ['ids', 'cid', 'name'],
        'deleteBatch' => ['ids'],
    ];

    protected function checkType($value, $rule, $data = [])
    {
        return array_key_exists($value, AttachmentEnum::getAttachmentTypeDict());
    }



}
