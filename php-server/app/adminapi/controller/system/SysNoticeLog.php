<?php


namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\system\SysNoticeLogService;

/**
 * @OA\Tag(name="admin-api/system/SysNoticeLog", description="消息通知日志管理接口")
 */
// 消息通知模板
class SysNoticeLog extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-log/pages",
     *     summary="获取消息通知日志分页列表",
     *     tags={"admin-api/system/SysNoticeLog"},
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
     *         @OA\Schema(type="string", example="order_status_change")
     *     ),
     *     @OA\Parameter(
     *         name="notice_channel",
     *         in="query",
     *         required=false,
     *         description="通知渠道",
     *         @OA\Schema(type="string", example="internal")
     *     ),
     *     @OA\Parameter(
     *         name="receiver",
     *         in="query",
     *         required=false,
     *         description="接收人",
     *         @OA\Schema(type="string", example="user@example.com")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         description="通知标题",
     *         @OA\Schema(type="string", example="订单状态变更")
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
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    // 获取消息通知模板列表
    public function getSysNoticeLogPages()
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
        $result = (new SysNoticeLogService())->getSysNoticeLogPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-log/{id}",
     *     summary="获取消息通知日志详情",
     *     tags={"admin-api/system/SysNoticeLog"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="通知日志ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),

     * )
     */
    // 获取消息通知模板详情
    public function getSysNoticeLogInfo($id)
    {
        $result = (new SysNoticeLogService())->getSysNoticeLogInfo($id);
        return ds_json_success('操作成功', $result);
    }



}
