<?php

namespace app\adminapi\controller\video\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\video\VideoShortEnum;

class VideoShortValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'title' => 'require|max:200',
        'description' => 'max:500',
        'type' => 'checkType',
        'cid' => 'integer|egt:0',
        'is_recommend' => 'integer|in:0,1',
        'is_top' => 'integer|in:0,1',
        'is_hot' => 'integer|in:0,1',
        'field' => 'require|max:50',
        'audit_status' => 'require|checkAuditStatus',
        'audit_remark' => 'max:255',
    ];

    protected $message = [
        'id.require' => '短视频ID不能为空',
        'id.integer' => '短视频ID必须为整数',
        'id.gt' => '短视频ID必须大于0',
        'title.require' => '标题不能为空',
        'title.max' => '标题长度不能超过200个字符',
        'description.max' => '描述长度不能超过500个字符',
        'type.checkType' => '内容类型值无效（1:视频 2:图片）',
        'cid.integer' => '分类ID必须为整数',
        'cid.egt' => '分类ID不能小于0',
        'is_recommend.integer' => '推荐状态必须为整数',
        'is_recommend.in' => '推荐状态值无效',
        'is_top.integer' => '置顶状态必须为整数',
        'is_top.in' => '置顶状态值无效',
        'is_hot.integer' => '热门状态必须为整数',
        'is_hot.in' => '热门状态值无效',
        'field.require' => '字段名不能为空',
        'field.max' => '字段名长度不能超过50个字符',
        'audit_status.require' => '审核状态不能为空',
        'audit_status.checkAuditStatus' => '审核状态值无效（0:待审核 1:审核通过 2:审核拒绝）',
        'audit_remark.max' => '审核备注长度不能超过255个字符',
    ];

    protected $scene = [
        'update' => ['title', 'description', 'type', 'cid', 'is_recommend', 'is_top', 'is_hot'],
        'toggle' => ['id', 'field'],
        'audit' => ['id', 'audit_status', 'audit_remark'],
    ];

    // 验证内容类型
    public function checkType($value, $rule, $data)
    {
        return array_key_exists($value, VideoShortEnum::getTypeDict());
    }

    // 验证审核状态
    public function checkAuditStatus($value, $rule, $data)
    {
        return array_key_exists($value, VideoShortEnum::getAuditStatusDict());
    }
} 