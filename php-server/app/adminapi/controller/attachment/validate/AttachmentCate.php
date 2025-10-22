<?php



namespace app\adminapi\controller\attachment\validate;
use app\deshang\base\BaseValidate;
use app\common\enum\attachment\AttachmentEnum;




class AttachmentCate extends BaseValidate
{

    protected $rule = [
        'id'          => 'require|integer|gt:0',
        'pid'   => 'require|integer|egt:0',
        'type'        => 'require|checkType',
        'name'        => 'require|max:20',
        'sort'        => 'integer',
    ];

    protected $message = [
        'id.require'          => '附件分类ID不能为空',
        'id.integer'          => '附件分类ID必须是整数',
        'id.gt'              => '附件分类ID不能小于0',
        'pid.require'   => '上级ID不能为空',
        'pid.integer'   => '上级ID必须是整数',
        'pid.egt'       => '上级ID不能小于0',
        'type.require'        => '附件类型不能为空',
        'type.in'             => '附件类型必须是image,video,audio,file',
        'name.require'        => '名称不能为空',
        'name.max'            => '名称不能超过20个字符',
        'sort.integer'        => '排序必须是整数',
        'sort.egt'            => '排序不能小于0',
    ];

    protected $scene = [
        'create' => ['pid','type','name','sort',],
        'update' => ['id','name'],
        'list' => ['type'],
    ];


    protected function checkType($value, $rule, $data = [])
    {
        return array_key_exists($value, AttachmentEnum::getAttachmentTypeDict());
    }

}
