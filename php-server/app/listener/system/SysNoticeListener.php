<?php


namespace app\listener\system;

use think\facade\Db;

use app\deshang\core\ThirdPartyLoader;

use app\common\enum\system\SysNoticeEnum;
use app\common\dao\system\SysNoticeTplDao;
use app\common\dao\system\SysNoticeLogDao;
use app\common\dao\system\SysNoticeSmsLogDao;

use app\deshang\service\system\DeshangSysConfigService;
use app\deshang\service\wechat\DeshangMiniEasyService;
use app\deshang\service\wechat\DeshangOfficialEasyService;

use app\common\dao\user\UserDao;
use app\common\dao\user\UserIdentityDao;
use app\common\dao\wechat\WechatSubscribeRecordDao;
use app\common\enum\wechat\WechatSubscribeEnum;

use app\deshang\exceptions\CommonException;

// 系统通知
class SysNoticeListener
{
    public function handle(array $params)
    {


        // sys_notice_tpl表 中的 key 字段
        $key = $params['key'];
        // 消息的参数  (参考 sys_notice_tpl表 的 description 模板变量)
        $template_params = $params['template_params'];
        // 接收方的参数  一般  参数 user_id  拓展可以根据  角色ID 进行拓展  比如骑手ID  商户ID（现在只支持user_id）
        $receiver_params = $params['receiver_params'];


        // 发送需要的数据
        $mobile = '';
        $email = '';
        $wx_event_openid = '';
        $wx_mini_openid = '';



        // 获取用户信息
        if (isset($receiver_params['user_id']) && $receiver_params['user_id'] > 0) {
            // 根据user_id 获取 用户信息
            $user_info = (new UserDao())->getUserInfo([['id', '=', $receiver_params['user_id']]], 'id,email,email_bind,mobile,mobile_bind');

            if (!empty($user_info['mobile']) && $user_info['mobile_bind'] == 1) {
                $mobile = $user_info['mobile'];
            }

            if (!empty($user_info['email']) && $user_info['email_bind'] == 1) {
                $email = $user_info['email'];
            }

            // 获取用户的微信openid   merchant_id 为 0 为平台用户
            $user_identity_info = (new UserIdentityDao())->getUserIdentityInfo([
                ['user_id', '=', $receiver_params['user_id']],
                ['merchant_id', '=', 0]
            ]);

            if (!empty($user_identity_info['wx_event_openid'])) {
                $wx_event_openid = $user_identity_info['wx_event_openid'];
            }

            if (!empty($user_identity_info['wx_mini_openid'])) {
                $wx_mini_openid = $user_identity_info['wx_mini_openid'];
            }
        }

        // template_params 参数包含了  mobile   email ， 则强制使用 template_params 的参数   主要用于 注册 登录 绑定 等特殊场景
        if (isset($template_params['mobile']) && !empty($template_params['mobile'])) {
            $mobile = $template_params['mobile'];
        }

        if (isset($template_params['email']) && !empty($template_params['email'])) {
            $email = $template_params['email'];
        }



        // 根据key 获取 模板
        $condition = [];
        $condition[] = ['key', '=', $key];
        $tpl = (new SysNoticeTplDao())->getSysNoticeTplInfo($condition);
        if (empty($tpl)) {
            throw new CommonException('模板不存在');
        }


        // 基础数据
        $base_log_data = [
            'user_id' => $receiver_params['user_id'],
            'key' => $key,
        ];

        // 日志数据
        $log_data = [];

        // 支持的通知渠道
        $supported_channels = $tpl['supported_channels'];

        // 事务处理
        Db::startTrans();
        try {


            // 如果是业务通知则默认 进行站内信发送  ， 如果是验证码则 没有站内信发送，验证码用于 登录 注册  找回密码
            if ($tpl['template_type'] == SysNoticeEnum::TEMPLATE_TYPE_BUSINESS && strpos($supported_channels, 'interna') !== false) {
                // 插入数据库
                $base_log_data['notice_channel'] = SysNoticeEnum::CHANNEL_INTERNAL;
                $base_log_data['receiver'] = '';
                $base_log_data['title'] = $tpl['title'];
                $base_log_data['content'] = $this->formatContent($tpl['interna_template'], $template_params);
                $base_log_data['send_status'] = 1;
                $log_data[] = $base_log_data;
            }

            // 邮箱是否开启
            $email_is_enabled = sysConfig('email:email_is_enabled');

            // 如果支持邮箱发送则进行邮箱发送   是否绑定了邮箱
            if ($tpl['email_switch'] == SysNoticeEnum::SWITCH_ON && strpos($supported_channels, 'email') !== false && !empty($email) && $email_is_enabled == 1) {

                // 发送邮箱


                // 插入数据库
                $base_log_data['notice_channel'] = SysNoticeEnum::CHANNEL_EMAIL;
                $base_log_data['receiver'] = $email;
                $base_log_data['title'] = $tpl['title'];
                $base_log_data['content'] = $this->formatContent($tpl['email_template'], $template_params);
                $base_log_data['send_status'] = 2;
                $log_data[] = $base_log_data;
            }


            // 短信是否开启
            $sms_config = (new DeshangSysConfigService())->getSysConfigByType('sms');


            // 如果支持短信发送则进行短信发送
            if ($tpl['sms_switch'] == SysNoticeEnum::SWITCH_ON && strpos($supported_channels, 'sms') !== false && !empty($mobile) && $sms_config['sms_is_enabled'] == 1) {
                // 发送短信
                $send_status = SysNoticeEnum::SEND_STATUS_SENDING;

                //短信数据插入数据库
                $sms_log_data = [
                    'user_id' => $receiver_params['user_id'],
                    'key' => $key,
                    'sms_provider' => $sms_config['sms_default_provider'],
                    'sms_template_id' => $tpl['sms_template_id'],
                    'mobile' => $mobile,
                    'content' => $this->formatContent($tpl['sms_template'], $template_params),
                    'code' => isset($template_params['code']) ? $template_params['code'] : '',
                    'is_verify' => 0,
                    'send_status' => $send_status,
                ];

                $sms_log_id = (new SysNoticeSmsLogDao())->createSysNoticeSmsLog($sms_log_data);

                // 发送短信
                $sms = ThirdPartyLoader::sms();
                $result = $sms->send($mobile, $tpl['sms_template_id'], $template_params);



                // 如果发送失败则更新发送状态
                if ($result === true) {
                    $send_status = SysNoticeEnum::SEND_STATUS_SUCCESS;
                } else {
                    $send_status = SysNoticeEnum::SEND_STATUS_FAILED;
                }

                // 更新短信发送结果
                $sms_log_update_data = [
                    'send_status' => $send_status,
                    'send_result' => $result,
                ];
                (new SysNoticeSmsLogDao())->updateSysNoticeSmsLog([['id', '=', $sms_log_id]], $sms_log_update_data);



                // 插入数据库
                $base_log_data['notice_channel'] = SysNoticeEnum::CHANNEL_SMS;
                $base_log_data['receiver'] = $mobile;
                $base_log_data['title'] = '短信通知';
                $base_log_data['content'] = $this->formatContent($tpl['sms_template'], $template_params);
                $base_log_data['send_status'] = $send_status;
                $log_data[] = $base_log_data;
            }


            // 如果支持微信公众号发送则进行微信公众号发送 （公众号模板消息）
            if ($tpl['wechat_official_switch'] == SysNoticeEnum::SWITCH_ON && strpos($supported_channels, 'wechat_official') !== false && !empty($wx_event_openid)) {
                // 发送微信公众号消息
                $official_data = $this->buildOfficialData($key, $template_params);
                $send_status = SysNoticeEnum::SEND_STATUS_FAILED;


                $official_result = (new DeshangOfficialEasyService())->init(0)->sendTemplateMessage(
                    $wx_event_openid,
                    $tpl['wechat_official_template_id'],
                    $official_data,
                    $template_params['h5_page'] ?? '',
                    // 小程序参数 默认不传
                    []
                );

                if (isset($official_result['errcode']) && $official_result['errcode'] == 0) {
                    $send_status = SysNoticeEnum::SEND_STATUS_SUCCESS;
                }


                // 插入数据库
                $base_log_data['notice_channel'] = SysNoticeEnum::CHANNEL_WECHAT_OFFICIAL;
                $base_log_data['receiver'] = $wx_event_openid;
                $base_log_data['title'] = '微信公众号通知';
                $base_log_data['content'] = '微信公众号通知';
                $base_log_data['send_status'] = $send_status;
                $base_log_data['send_params'] = json_encode([
                    'template_id' => $tpl['wechat_official_template_id'],
                    'openid' => $wx_event_openid,
                    'data' => $official_data,
                    'url' => $template_params['h5_page'] ?? '',
                ]);
                $base_log_data['send_result'] = json_encode($official_result);
                $log_data[] = $base_log_data;
            }

            // 如果支持微信小程序发送则进行微信小程序发送
            if ($tpl['wechat_mini_switch'] == SysNoticeEnum::SWITCH_ON && strpos($supported_channels, 'wechat_mini') !== false && !empty($wx_mini_openid)) {
                $mini_result = null;
                $send_status = SysNoticeEnum::SEND_STATUS_FAILED;

                // 先检查用户是否有有效的订阅记录
                if (isset($receiver_params['user_id']) && $receiver_params['user_id'] > 0) {
                    // 构建查询条件：用户ID、模板key、订阅类型、已同意、未发送、未过期
                    $condition = [
                        ['user_id', '=', $receiver_params['user_id']],
                        ['template_key', '=', $key],
                        ['subscribe_type', '=', WechatSubscribeEnum::SUBSCRIBE_TYPE_MINI],
                        ['subscribe_status', '=', WechatSubscribeEnum::SUBSCRIBE_STATUS_ACCEPT],
                        ['send_status', '=', WechatSubscribeEnum::SEND_STATUS_PENDING],
                        ['expire_time', '>', time()], // 未过期
                    ];
                    $subscribeRecord = (new WechatSubscribeRecordDao())->getWechatSubscribeRecordInfo($condition);

                    // 如果订阅记录存在，则发送微信小程序订阅消息,小程序订阅消息 需要用户同意订阅后才能发送
                    if (!empty($subscribeRecord)) {
                        // 构建数据
                        $mini_data = $this->buildMiniData($key, $template_params);

                        // 发送微信小程序订阅消息
                        $mini_result = (new DeshangMiniEasyService())->init(0)->sendSubscribeMessage(
                            $tpl['wechat_mini_template_id'],
                            $template_params['h5_page'] ?? '',
                            $wx_mini_openid,
                            $mini_data
                        );

                        // 更新订阅记录状态为已发送
                        $updateStatus = $mini_result['errcode'] == 0 ? WechatSubscribeEnum::SEND_STATUS_SENT : WechatSubscribeEnum::SEND_STATUS_FAILED;
                        (new WechatSubscribeRecordDao())->updateWechatSubscribeRecord([
                            ['id', '=', $subscribeRecord['id']]
                        ], [
                            'send_status' => $updateStatus,
                            'send_time' => time(),
                            'send_params' => json_encode([
                                'template_id' => $tpl['wechat_mini_template_id'],
                                'page' => $template_params['h5_page'] ?? '',
                                'openid' => $wx_mini_openid,
                                'data' => isset($mini_data) ? $mini_data : [],
                            ]),
                            'send_result' => json_encode($mini_result),
                            'update_at' => time()
                        ]);

                        $send_status = $mini_result['errcode'] == 0 ? SysNoticeEnum::SEND_STATUS_SUCCESS : SysNoticeEnum::SEND_STATUS_FAILED;
                    } else {
                        // 用户未订阅或订阅已过期
                        $mini_result = ['errcode' => -1, 'errmsg' => '用户未订阅或订阅已过期，跳过发送'];
                    }
                }

                // 插入发送日志
                $base_log_data['notice_channel'] = SysNoticeEnum::CHANNEL_WECHAT_MINI;
                $base_log_data['receiver'] = $wx_mini_openid;
                $base_log_data['title'] = '微信小程序通知';
                $base_log_data['content'] = '微信小程序通知';
                $base_log_data['send_status'] = $send_status;
                $base_log_data['send_params'] = json_encode([
                    'template_id' => $tpl['wechat_mini_template_id'],
                    'page' => $template_params['h5_page'] ?? '',
                    'openid' => $wx_mini_openid,
                    'data' => isset($mini_data) ? $mini_data : [],
                ]);
                $base_log_data['send_result'] = json_encode($mini_result);
                $log_data[] = $base_log_data;
            }



            // 批量插入数据库
            (new SysNoticeLogDao())->createSysNoticeLogAll($log_data);

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new CommonException('获取到的异常' . $e->getMessage());
        }
    }

    // 格式化内容 短信 站内信  邮件 
    private function formatContent($content, $template_params)
    {
        foreach ($template_params as $k => $v) {
            $search = '{' . $k . '}';
            $content = str_replace($search, $v, $content);
        }
        return $content;
    }

    /**
     * 构建微信小程序数据（简化版本）
     * @param string $key 模板key
     * @param array $template_params 模板参数
     * @return array
     */
    private function buildMiniData(string $key, array $template_params): array
    {
        // 获取预设配置
        $config_file = config_path() . 'wechat_mini_template_presets.php';
        if (!file_exists($config_file)) {
            return [];
        }

        $presets = include($config_file);

        if (!isset($presets[$key]) || !isset($presets[$key]['template_fields'])) {
            return [];
        }

        $template_fields = $presets[$key]['template_fields'];
        $mini_data = [];

        // 遍历字段配置
        foreach ($template_fields as $field_config) {
            $param_key = $field_config['param_key'];        // 系统字段名
            $wechat_field = $field_config['wechat_field'];  // 微信字段名

            if (isset($template_params[$param_key])) {
                $mini_data[$wechat_field] = [
                    'value' => $this->formatMiniWechatValue($template_params[$param_key], $wechat_field)
                ];
            }
        }

        return $mini_data;
    }


    /**
     * 格式化微信公众号模板消息字段值
     * https://developers.weixin.qq.com/doc/service/api/notify/template/api_sendtemplatemessage.html
     * @param mixed $value
     * @param string $wechat_field
     * @return string
     */
    private function formatOfficialWechatValue($value, string $wechat_field): string
    {
        $str_value = (string)$value;

        // 根据微信公众号模板消息API文档处理字段
        switch (true) {
            // thing.DATA 类型：事物，可汉字、数字、字母或符号组合，20个以内字符
            case strpos($wechat_field, 'thing') === 0:
                return mb_substr($str_value, 0, 20);

            // character_string.DATA 类型：字符串，可数字、字母或符号组合，32位以内
            case strpos($wechat_field, 'character_string') === 0:
                return mb_substr($str_value, 0, 32);

            // time.DATA 类型：时间，24小时制时间格式
            case strpos($wechat_field, 'time') === 0:
                if (is_numeric($value) && $value > 1000000000) {
                    return date('H:i:s', $value);
                }
                return $str_value;

            // amount.DATA 类型：金额，1个币种符号+10位以内纯数字，可带小数，结尾可带"元"
            case strpos($wechat_field, 'amount') === 0:
                return number_format((float)$value, 2, '.', '');

            // phone_number.DATA 类型：电话号码，17位以内，数字、符号
            case strpos($wechat_field, 'phone_number') === 0:
                return mb_substr($str_value, 0, 17);

            // car_number.DATA 类型：车牌号码，8位以内
            case strpos($wechat_field, 'car_number') === 0:
                return mb_substr($str_value, 0, 8);

            // const.DATA 类型：常量，20位以内字符
            case strpos($wechat_field, 'const') === 0:
                return mb_substr($str_value, 0, 20);

            default:
                return $str_value;
        }
    }

    /**
     * 格式化微信小程序订阅消息字段值
     * https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/sendMessage.html
     * @param mixed $value
     * @param string $wechat_field
     * @return string
     */
    private function formatMiniWechatValue($value, string $wechat_field): string
    {
        $str_value = (string)$value;

        // 根据微信小程序订阅消息API文档处理字段
        switch (true) {
            // thing.DATA 类型：事物，20个以内字符，可汉字、数字、字母或符号组合
            case strpos($wechat_field, 'thing') === 0:
                return mb_substr($str_value, 0, 20);

            // number.DATA 类型：数字，32位以内数字，只能数字，可带小数
            case strpos($wechat_field, 'number') === 0:
                return (string)(float)$value;

            // letter.DATA 类型：字母，32位以内字母，只能字母
            case strpos($wechat_field, 'letter') === 0:
                return mb_substr($str_value, 0, 32);

            // symbol.DATA 类型：符号，5位以内符号，只能符号
            case strpos($wechat_field, 'symbol') === 0:
                return mb_substr($str_value, 0, 5);

            // character_string.DATA 类型：字符串，32位以内数字、字母或符号
            case strpos($wechat_field, 'character_string') === 0:
                return mb_substr($str_value, 0, 32);

            // time.DATA 类型：时间，24小时制时间格式（支持+年月日）
            case strpos($wechat_field, 'time') === 0:
                if (is_numeric($value) && $value > 1000000000) {
                    return date('H:i:s', $value);
                }
                return $str_value;

            // date.DATA 类型：日期，年月日格式（支持+24小时制时间）
            case strpos($wechat_field, 'date') === 0:
                if (is_numeric($value) && $value > 1000000000) {
                    return date('Y年m月d日', $value);
                }
                return $str_value;

            // amount.DATA 类型：金额，1个币种符号+10位以内纯数字，可带小数，结尾可带"元"
            case strpos($wechat_field, 'amount') === 0:
                return number_format((float)$value, 2, '.', '');

            // phone_number.DATA 类型：电话，17位以内，数字、符号
            case strpos($wechat_field, 'phone_number') === 0:
                return mb_substr($str_value, 0, 17);

            // car_number.DATA 类型：车牌，8位以内，第一位与最后一位可为汉字，其余为字母或数字
            case strpos($wechat_field, 'car_number') === 0:
                return mb_substr($str_value, 0, 8);

            // name.DATA 类型：姓名，10个以内纯汉字或20个以内纯字母或符号
            case strpos($wechat_field, 'name') === 0:
                // 判断是否为纯汉字
                if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str_value)) {
                    return mb_substr($str_value, 0, 10); // 纯汉字限制10个
                } else {
                    return mb_substr($str_value, 0, 20); // 其他情况限制20个
                }

            // phrase.DATA 类型：汉字，5个以内汉字，5个以内纯汉字
            case strpos($wechat_field, 'phrase') === 0:
                return mb_substr($str_value, 0, 5);

            default:
                return $str_value;
        }
    }

    /**
     * 构建微信公众号数据
     * @param string $key 模板key
     * @param array $template_params 模板参数
     * @return array
     */
    private function buildOfficialData(string $key, array $template_params): array
    {
        // 获取预设配置
        $config_file = config_path() . 'wechat_official_template_presets.php';
        if (!file_exists($config_file)) {
            return [];
        }

        $presets = include($config_file);

        if (!isset($presets[$key]) || !isset($presets[$key]['template_fields'])) {
            return [];
        }

        $template_fields = $presets[$key]['template_fields'];
        $official_data = [];

        // 遍历字段配置
        foreach ($template_fields as $field_config) {
            $param_key = $field_config['param_key'];        // 系统字段名
            $wechat_field = $field_config['wechat_field'];  // 微信字段名

            if (isset($template_params[$param_key])) {
                $official_data[$wechat_field] = [
                    'value' => $this->formatOfficialWechatValue($template_params[$param_key], $wechat_field)
                ];
            }
        }

        return $official_data;
    }
}
