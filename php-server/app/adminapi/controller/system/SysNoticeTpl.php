<?php


namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\system\SysNoticeTplService;

/**
 * @OA\Tag(name="admin-api/system/SysNoticeTpl", description="消息通知模板管理接口")
 */
// 消息通知模板
class SysNoticeTpl extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-tpls/list",
     *     summary="获取消息通知模板列表",
     *     tags={"admin-api/system/SysNoticeTpl"},
     *     @OA\Parameter(
     *         name="receiver_type",
     *         in="query",
     *         required=false,
     *         description="接收者类型",
     *         @OA\Schema(type="string", example="user")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    // 获取消息通知模板列表
    public function getSysNoticeTplList()
    {
        $data = array(
            'receiver_type' => input('param.receiver_type'),
        );
        $result = (new SysNoticeTplService())->getSysNoticeTplList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/notice-tpls/{id}",
     *     summary="获取消息通知模板详情",
     *     tags={"admin-api/system/SysNoticeTpl"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="通知模板ID",
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
    // 获取消息通知模板详情
    public function getSysNoticeTplInfo($id)
    {
        $result = (new SysNoticeTplService())->getSysNoticeTplInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/system/notice-tpls/{id}",
     *     summary="更新消息通知模板",
     *     tags={"admin-api/system/SysNoticeTpl"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="通知模板ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="通知模板信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="interna_template", type="string", description="站内信模板", example="您有新的订单通知"),
     *             @OA\Property(property="email_switch", type="integer", description="邮件开关", example=1),
     *             @OA\Property(property="email_template", type="string", description="邮件模板", example="您有新的订单，请及时处理"),
     *             @OA\Property(property="sms_switch", type="integer", description="短信开关", example=1),
     *             @OA\Property(property="sms_template_id", type="string", description="短信模板ID", example="SMS_123456"),
     *             @OA\Property(property="sms_template", type="string", description="短信模板", example="您有新订单，订单号：{order_no}"),
     *             @OA\Property(property="wechat_official_switch", type="integer", description="微信公众号开关", example=1),
     *             @OA\Property(property="wechat_official_template_id", type="string", description="微信公众号模板ID", example="WX_123456"),
     *             @OA\Property(property="wechat_mini_switch", type="integer", description="微信小程序开关", example=1),
     *             @OA\Property(property="wechat_mini_template_id", type="string", description="微信小程序模板ID", example="WX_MINI_123456")
     *         )
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
    // 更新消息通知模板
    public function updateSysNoticeTpl($id)
    {
        $data = array(
            'interna_template' => input('param.interna_template'),
            'email_switch' => input('param.email_switch'),
            'email_template' => input('param.email_template'),
            'sms_switch' => input('param.sms_switch'),
            'sms_template_id' => input('param.sms_template_id'),
            'sms_template' => input('param.sms_template'),
            'wechat_official_switch' => input('param.wechat_official_switch'),
            'wechat_official_template_id' => input('param.wechat_official_template_id'),
            'wechat_mini_switch' => input('param.wechat_mini_switch'),
            'wechat_mini_template_id' => input('param.wechat_mini_template_id'),
        );

        $this->validate($data, 'app\adminapi\controller\system\validate\SysNoticeTplValidate.update');

        $result = (new SysNoticeTplService())->updateSysNoticeTpl($id, $data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/notice-tpls/test",
     *     summary="测试消息通知模板",
     *     tags={"admin-api/system/SysNoticeTpl"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="测试通知信息",
     *         @OA\JsonContent(
     *             required={"user_id", "template_key"},
     *             @OA\Property(property="user_id", type="integer", description="测试用户ID", example=1),
     *             @OA\Property(property="template_key", type="string", description="模板标识", example="user_register"),
     *             @OA\Property(property="template_params", type="object", description="模板参数", example={"code": "123456", "order_no": "DS202412345"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="测试发送成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="测试发送成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="参数错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="参数不完整")
     *         )
     *     )
     * )
     */
    // 测试消息通知模板
    public function testSysNoticeTpl()
    {
        $data = array(
            'user_id' => input('param.user_id'),
            'template_key' => input('param.template_key'),
            'template_params' => input('param.template_params', []),
        );


        $this->validate($data, 'app\adminapi\controller\system\validate\SysNoticeTplValidate.test');


        // 通知商户， 实际是发送到 用户 消息中
        event('SysNoticeListener', [
            'key' => $data['template_key'],
            'receiver_params' => [
                'user_id' => $data['user_id'],
            ],
            // 具体参数  以  sys_notice_tpl 表 为准
            'template_params' => $data['template_params'],
        ]);

        return ds_json_success('测试发送成功');
    }

    /**
     * @OA\Patch(
     *     path="/adminapi/system/notice-tpls/{id}/toggle-field",
     *     summary="切换消息通知模板字段状态",
     *     tags={"admin-api/system/SysNoticeTpl"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="通知模板ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="切换数据",
     *         @OA\JsonContent(
     *             required={"field"},
     *             @OA\Property(property="field", type="string", example="email_switch", description="字段名")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="切换成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="切换成功")
     *         )
     *     )
     * )
     */
    public function toggleSysNoticeTplField($id)
    {
        $data = [
            'id' => (int) $id,
            'field' => input('param.field')
        ];

        $this->validate($data, 'app\adminapi\controller\system\validate\SysNoticeTplValidate.toggle');

        $result = (new SysNoticeTplService())->toggleSysNoticeTplField($data);
        return ds_json_success('切换成功', $result);
    }
}
