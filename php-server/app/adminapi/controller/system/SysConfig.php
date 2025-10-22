<?php


namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\system\SysConfigService;

/**
 * @OA\Tag(name="admin-api/system/SysConfig", description="系统配置管理接口")
 */
class SysConfig extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/system/config/getSysConfigList",
     *     summary="获取系统配置列表",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         description="配置类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysConfigList()
    {
        $data = array(
            'config_type' => input('param.type')
        );

 
        $result = (new SysConfigService)->getSysConfigList($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/config/info/{config_key}",
     *     summary="获取单个配置信息",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\Parameter(
     *         name="config_key",
     *         in="path",
     *         required=true,
     *         description="配置键",
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
    public function getSysConfigInfoByKey($config_key)
    {
        $result = (new SysConfigService)->getSysConfigInfoByKey($config_key);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/updateSysConfigInfo",
     *     summary="更新单个配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="配置信息",
     *         @OA\JsonContent(
     *             required={"config_type", "config_key", "config_value"},
     *             @OA\Property(property="config_type", type="string", example="website"),
     *             @OA\Property(property="config_key", type="string", example="site_name"),
     *             @OA\Property(property="config_value", type="string", example="我的网站")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updateSysConfigInfo()
    {
        $data = array(
            'config_type' => input('param.config_type'),
            'config_key' => input('param.config_key'),
            'config_value' => input('param.config_value'),
        );
        (new SysConfigService)->updateSysConfigInfo($data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editWebsite",
     *     summary="编辑网站配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="网站配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="api_url", type="string", example="https://api.example.com"),
     *             @OA\Property(property="pc_url", type="string", example="https://www.example.com"),
     *             @OA\Property(property="h5_url", type="string", example="https://m.example.com"),
     *             @OA\Property(property="website_phone", type="string", example="400-123-4567"),
     *             @OA\Property(property="website_address", type="string", example="北京市朝阳区"),
     *             @OA\Property(property="admin_site_name", type="string", example="管理后台"),
     *             @OA\Property(property="admin_site_logo", type="string", example="/logo.png")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function editWebsite()
    {
        $data = array(
            'api_url' => input('param.api_url'),
            'pc_url' => input('param.pc_url'),
            'pc_store_url' => input('param.pc_store_url'),
            'pc_merchant_url' => input('param.pc_merchant_url'),
            'h5_url' => input('param.h5_url'),
            'h5_store_url' => input('param.h5_store_url'),
            'h5_merchant_url' => input('param.h5_merchant_url'),

            'website_phone' => input('param.website_phone'),
            'website_address' => input('param.website_address'),
            'website_email' => input('param.website_email'),
            'website_work_hours' => input('param.website_work_hours'),
            'website_qrcode' => input('param.website_qrcode'),

            'admin_site_name' => input('param.admin_site_name'),
            'admin_site_logo' => input('param.admin_site_logo'),
            'h5_site_logo' => input('param.h5_site_logo'),
            'h5_site_name' => input('param.h5_site_name'),
            'pc_site_logo' => input('param.pc_site_logo'),
            'pc_site_name' => input('param.pc_site_name'),
            'icp_code' => input('param.icp_code'),
            'icp_url' => input('param.icp_url'),
        );



        (new SysConfigService)->batchUpdateSysConfig('website', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editLoginConfig",
     *     summary="编辑登录配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="登录配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_login_normal", type="integer", example=1, description="是否开启普通登录"),
     *             @OA\Property(property="user_login_mobile", type="integer", example=1, description="是否开启手机验证码登录"),
     *             @OA\Property(property="user_login_wechat", type="integer", example=1, description="是否开启微信登录"),
     *             @OA\Property(property="user_login_logo", type="string", example="/login_logo.png", description="登录页LOGO")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function editLoginConfig()
    {
        $data = array(
            'user_login_normal' => input('param.user_login_normal'), // 是否开启普通登录或注册
            'user_login_mobile' => input('param.user_login_mobile'), // 是否开启手机验证码登录或注册
            'user_login_wechat' => input('param.user_login_wechat'), // 是否开启微信登录或注册
            'user_login_logo' => input('param.user_login_logo'), // 用户登录显示LOGO
        );

        (new SysConfigService)->batchUpdateSysConfig('user_login', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editGrowthRules",
     *     summary="编辑成长值规则配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="成长值规则配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="growth_login_amount", type="integer", example=10, description="登录获取成长值"),
     *             @OA\Property(property="growth_login_enabled", type="integer", example=1, description="是否开启登录成长值"),
     *             @OA\Property(property="growth_register_amount", type="integer", example=100, description="注册获取成长值"),
     *             @OA\Property(property="growth_register_enabled", type="integer", example=1, description="是否开启注册成长值"),
     *             @OA\Property(property="growth_pay_amount", type="integer", example=50, description="支付获取成长值"),
     *             @OA\Property(property="growth_pay_enabled", type="integer", example=1, description="是否开启支付成长值"),
     *             @OA\Property(property="growth_payrate_amount", type="number", format="float", example=0.1, description="支付金额比例获取"),
     *             @OA\Property(property="growth_payrate_enabled", type="integer", example=1, description="是否开启金额比例成长值"),
     *             @OA\Property(property="growth_review_amount", type="integer", example=20, description="评价获取成长值"),
     *             @OA\Property(property="growth_review_enabled", type="integer", example=1, description="是否开启评价成长值"),
     *             @OA\Property(property="growth_invite_amount", type="integer", example=200, description="邀请注册获取成长值"),
     *             @OA\Property(property="growth_invite_enabled", type="integer", example=1, description="是否开启邀请成长值")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑成长值规则
    public function editGrowthRules()
    {
        $data = array(
            'growth_login_amount' => input('param.growth_login_amount'), // 登录获取
            'growth_login_enabled' => input('param.growth_login_enabled'), // 是否开启
            'growth_register_amount' => input('param.growth_register_amount'), // 注册获取成长值
            'growth_register_enabled' => input('param.growth_register_enabled'), // 是否开启
            'growth_pay_amount' => input('param.growth_pay_amount'), // 支付获取成长值
            'growth_pay_enabled' => input('param.growth_pay_enabled'), // 是否开启
            'growth_payrate_amount' => input('param.growth_payrate_amount'), // 金额比例获取
            'growth_payrate_enabled' => input('param.growth_payrate_enabled'), // 是否开启
            'growth_review_amount' => input('param.growth_review_amount'), // 评价获取
            'growth_review_enabled' => input('param.growth_review_enabled'), // 是否开启
            'growth_invite_amount' => input('param.growth_invite_amount'), // 邀请注册
            'growth_invite_enabled' => input('param.growth_invite_enabled'), // 是否开启
        );

        (new SysConfigService)->batchUpdateSysConfig('growth', $data);
        return ds_json_success('操作成功');
    }


    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editPointsRules",
     *     summary="编辑积分规则配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="积分规则配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="points_login_amount", type="integer", example=10, description="登录获取积分"),
     *             @OA\Property(property="points_login_enabled", type="integer", example=1, description="是否开启登录积分"),
     *             @OA\Property(property="points_register_amount", type="integer", example=100, description="注册获取积分"),
     *             @OA\Property(property="points_register_enabled", type="integer", example=1, description="是否开启注册积分"),
     *             @OA\Property(property="points_pay_amount", type="integer", example=50, description="支付获取积分"),
     *             @OA\Property(property="points_pay_enabled", type="integer", example=1, description="是否开启支付积分"),
     *             @OA\Property(property="points_payrate_amount", type="number", format="float", example=0.1, description="支付金额比例获取"),
     *             @OA\Property(property="points_payrate_enabled", type="integer", example=1, description="是否开启金额比例积分"),
     *             @OA\Property(property="points_review_amount", type="integer", example=20, description="评价获取积分"),
     *             @OA\Property(property="points_review_enabled", type="integer", example=1, description="是否开启评价积分"),
     *             @OA\Property(property="points_invite_amount", type="integer", example=200, description="邀请注册获取积分"),
     *             @OA\Property(property="points_invite_enabled", type="integer", example=1, description="是否开启邀请积分")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑积分规则
    public function editPointsRules()
    {
        $data = array(
            'points_login_amount' => input('param.points_login_amount'), // 登录获取
            'points_login_enabled' => input('param.points_login_enabled'), // 是否开启
            'points_register_amount' => input('param.points_register_amount'), // 注册获取积分
            'points_register_enabled' => input('param.points_register_enabled'), // 是否开启
            'points_pay_amount' => input('param.points_pay_amount'), // 支付获取积分
            'points_pay_enabled' => input('param.points_pay_enabled'), // 是否开启
            'points_payrate_amount' => input('param.points_payrate_amount'), // 金额比例获取
            'points_payrate_enabled' => input('param.points_payrate_enabled'), // 是否开启
            'points_review_amount' => input('param.points_review_amount'), // 评价获取
            'points_review_enabled' => input('param.points_review_enabled'), // 是否开启
            'points_invite_amount' => input('param.points_invite_amount'), // 邀请注册
            'points_invite_enabled' => input('param.points_invite_enabled'), // 是否开启
        );

        (new SysConfigService)->batchUpdateSysConfig('points', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editGoodsRules",
     *     summary="编辑商品规则配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="商品规则配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="goods_need_audit", type="integer", example=1, description="商品是否需要审核"),
     *             @OA\Property(property="goods_category_select_limit", type="integer", example=3, description="商品分类选择限制")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑商品规则
    public function editGoodsRules()
    {
        $data = array(
            'goods_need_audit' => (int)input('param.goods_need_audit'), // 商品是否需要审核
            'goods_category_select_limit' => (int)input('param.goods_category_select_limit'), // 商品分类选择限制
        );

        (new SysConfigService)->batchUpdateSysConfig('goods', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editOrderAutoConfig",
     *     summary="编辑订单自动处理配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="订单自动处理配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="auto_cancel_order_enabled", type="integer", example=1, description="是否开启自动取消订单"),
     *             @OA\Property(property="auto_cancel_order_hours", type="integer", example=24, description="自动取消订单小时数"),
     *             @OA\Property(property="auto_confirm_order_enabled", type="integer", example=1, description="是否开启自动确认收货"),
     *             @OA\Property(property="auto_confirm_order_hours", type="integer", example=168, description="自动确认收货小时数"),
     *             @OA\Property(property="refund_order_enabled", type="integer", example=1, description="是否开启确认收货退款"),
     *             @OA\Property(property="refund_order_days", type="integer", example=7, description="确认收货可申请退款天数")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑订单自动取消规则
    public function editOrderAutoConfig()
    {
        $data = array(
            'auto_cancel_order_enabled' => (int)input('param.auto_cancel_order_enabled'), // 是否开启自动取消订单功能
            'auto_cancel_order_hours' => (int)input('param.auto_cancel_order_hours'), // 自动取消订单的小时数
            'auto_confirm_order_enabled' => (int)input('param.auto_confirm_order_enabled'), // 是否开启自动确认收货功能
            'auto_confirm_order_hours' => (int)input('param.auto_confirm_order_hours'), // 自动确认收货的小时数
            'refund_order_enabled' => (int)input('param.refund_order_enabled'), // 是否开启确认收货退款
            'refund_order_days' => (int)input('param.refund_order_days'), // 确认收货可申请退款的天数
        );

        (new SysConfigService)->batchUpdateSysConfig('order_auto', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editUserWithdrawalRules",
     *     summary="编辑用户提现规则配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="用户提现规则配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="withdrawal_min_amount", type="number", format="float", example=100.00, description="最低提现金额"),
     *             @OA\Property(property="withdrawal_fee_rate", type="number", format="float", example=0.05, description="提现手续费率")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑提现配置
    public function editUserWithdrawalRules()
    {
        $data = array(
            'withdrawal_min_amount' => (float)input('param.withdrawal_min_amount'), // 最低提现金额
            'withdrawal_fee_rate' => (float)input('param.withdrawal_fee_rate'), // 提现手续费
        );
        (new SysConfigService)->batchUpdateSysConfig('user_withdrawal', $data);
        return ds_json_success('操作成功');
    }


    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editDistributorConfig",
     *     summary="编辑分销配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="分销配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="distributor_is_enabled", type="integer", example=1, description="是否开启分销功能"),
     *             @OA\Property(property="distributor_tier", type="integer", example=3, description="分销员层级"),
     *             @OA\Property(property="distributor_self_enabled", type="integer", example=1, description="是否开启分销员自购分佣"),
     *             @OA\Property(property="distributor_apply_type", type="string", example="audit", description="分销商申请方式: audit=审核, auto=自动, manual=手动"),
     *             @OA\Property(property="distributor_conditions", type="string", example="amount", description="分销商申请条件: none=无条件, amount=消费金额, count=消费次数"),
     *             @OA\Property(property="distributor_apply_amount", type="number", format="float", example=1000.00, description="申请条件消费金额"),
     *             @OA\Property(property="distributor_apply_count", type="integer", example=10, description="申请条件消费次数")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑分销配置
    public function editDistributorConfig()
    {
        $data = array(
            'distributor_is_enabled' => (int)input('param.distributor_is_enabled'), // 是否开启分销功能
            'distributor_tier' => (int)input('param.distributor_tier'), // 分销员层级
            'distributor_self_enabled' => (int)input('param.distributor_self_enabled'), // 是否开启分销员自购分佣
            'distributor_apply_type' => input('param.distributor_apply_type'), // 分销商申请方式 , audit 审核, auto 自动, manual 手动
            'distributor_conditions' => input('param.distributor_conditions'), // 分销商申请条件 none 无条件 amount 消费金额 count 消费次数
            'distributor_apply_amount' => (float)input('param.distributor_apply_amount'), // 分销商申请条件 amount 时，消费金额
            'distributor_apply_count' => (int)input('param.distributor_apply_count'), // 分销商申请条件 count 时，消费次数
        );

        (new SysConfigService)->batchUpdateSysConfig('distributor', $data);
        return ds_json_success('操作成功');
    }





    




    /**
     * @OA\Post(
     *     path="/adminapi/system/config/editEmailConfig",
     *     summary="编辑邮件配置",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="邮件配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="smtp_host", type="string", example="smtp.qq.com", description="SMTP服务器地址"),
     *             @OA\Property(property="smtp_port", type="integer", example=587, description="SMTP端口"),
     *             @OA\Property(property="smtp_user", type="string", example="user@example.com", description="SMTP用户名"),
     *             @OA\Property(property="smtp_pass", type="string", example="password123", description="SMTP密码"),
     *             @OA\Property(property="smtp_ssl", type="string", example="tls", description="SSL/TLS类型"),
     *             @OA\Property(property="smtp_from_email", type="string", example="noreply@example.com", description="发件人邮箱")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    // 编辑邮件配置
    public function editEmailConfig()
    {
        $data = array(
            'smtp_host' => input('param.smtp_host'),
            'smtp_port' => input('param.smtp_port'),
            'smtp_user' => input('param.smtp_user'),
            'smtp_pass' => input('param.smtp_pass'),
            'smtp_ssl' => input('param.smtp_ssl'),
            'smtp_from_email' => input('param.smtp_from_email'),
        );
        (new SysConfigService)->batchUpdateSysConfig('email', $data);
        return ds_json_success('操作成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/config/testEmailSend",
     *     summary="发送测试邮件",
     *     tags={"admin-api/system/SysConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="测试邮件发送信息",
     *         @OA\JsonContent(
     *             required={"test_to_email"},
     *             @OA\Property(property="smtp_host", type="string", example="smtp.qq.com", description="SMTP服务器地址"),
     *             @OA\Property(property="smtp_port", type="integer", example=587, description="SMTP端口"),
     *             @OA\Property(property="smtp_user", type="string", example="user@example.com", description="SMTP用户名"),
     *             @OA\Property(property="smtp_pass", type="string", example="password123", description="SMTP密码"),
     *             @OA\Property(property="smtp_ssl", type="string", example="tls", description="SSL/TLS类型"),
     *             @OA\Property(property="smtp_from_email", type="string", example="noreply@example.com", description="发件人邮箱"),
     *             @OA\Property(property="test_to_email", type="string", example="test@example.com", description="测试收件人邮箱")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="邮件发送成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="邮件发送成功")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="邮件发送失败",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="邮件发送失败: 错误信息")
     *         )
     *     )
     * )
     */
    // 发送测试邮件
    public function testEmailSend()
    {
        $data = array(
            'smtp_host' => input('param.smtp_host'),
            'smtp_port' => input('param.smtp_port'),
            'smtp_user' => input('param.smtp_user'),
            'smtp_pass' => input('param.smtp_pass'),
            'smtp_ssl' => input('param.smtp_ssl'),
            'smtp_from_email' => input('param.smtp_from_email'),
            'test_to_email' => input('param.test_to_email'),
        );


        try {
            // 创建邮件对象
            $email = new \app\deshang\utils\Email([
                'smtp_host' => $data['smtp_host'],
                'smtp_port' => $data['smtp_port'],
                'smtp_user' => $data['smtp_user'],
                'smtp_pass' => $data['smtp_pass'],
                'smtp_from_email' => $data['smtp_from_email'],
                'smtp_from_name' => '发件人'
            ]);

            // 添加收件人
            $email->addAddress($data['test_to_email'], '收件人');

            // 可选：添加附件
            // $email->addAttachment('/path/to/file.pdf', '文件名称.pdf');

            // 发送邮件
            $email->send('邮件主题', '<p>这是测试邮件</p>');

            return ds_json_success('邮件发送成功');
        } catch (\Exception $e) {
            return ds_json_error('邮件发送失败: ' . $e->getMessage());
        }
    }
}
