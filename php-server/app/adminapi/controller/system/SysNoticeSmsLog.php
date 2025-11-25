<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\system\SysNoticeSmsLogService;

/**
 * @OA\Tag(name="admin-api/system/SysNoticeSmsLog", description="短信发送日志管理接口")
 */
// 短信发送日志
class SysNoticeSmsLog extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-sms-logs/pages",
     *     summary="获取短信日志分页列表",
     *     tags={"admin-api/system/SysNoticeSmsLog"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=false,
     *         description="用户ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=false,
     *         description="通知模板键值",
     *         @OA\Schema(type="string", example="register_verify")
     *     ),
     *     @OA\Parameter(
     *         name="notice_channel",
     *         in="query",
     *         required=false,
     *         description="通知渠道",
     *         @OA\Schema(type="string", example="sms")
     *     ),
     *     @OA\Parameter(
     *         name="receiver",
     *         in="query",
     *         required=false,
     *         description="接收人",
     *         @OA\Schema(type="string", example="13800138000")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         description="短信标题",
     *         @OA\Schema(type="string", example="验证码短信")
     *     ),
     *     @OA\Parameter(
     *         name="is_read",
     *         in="query",
     *         required=false,
     *         description="是否已读",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="send_status",
     *         in="query",
     *         required=false,
     *         description="发送状态",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         required=false,
     *         description="用户名",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    // 获取短信日志列表
    public function getSysNoticeSmsLogPages()
    {
        $data = array(
            'user_id' => input('param.user_id'),
            'key' => input('param.key'),
            'notice_channel' => input('param.notice_channel'),
            'receiver' => input('param.receiver'),
            'title' => input('param.title'),
            'is_read' => input('param.is_read') == 1 ? 1 : 0,
            'send_status' => input('param.send_status'),
            'username' => input('param.username'),
        );

        $result = (new SysNoticeSmsLogService())->getSysNoticeSmsLogPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-sms-logs/{id}",
     *     summary="获取短信日志详情",
     *     tags={"admin-api/system/SysNoticeSmsLog"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="短信日志ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),

     * )
     */
    // 获取短信日志详情
    public function getSysNoticeSmsLogInfo($id)
    {
        $result = (new SysNoticeSmsLogService())->getSysNoticeSmsLogInfo($id);
        return ds_json_success('操作成功', $result);
    }
}
