<?php

namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;
use EasyWeChat\OfficialAccount\Application;

/**
 * 微信开放平台移动应用服务类（与 DeshangOfficialEasyService 同风格，使用 EasyWeChat Application + OAuth）
 *
 * 用于处理 APP 内微信登录（移动应用授权）。通过 EasyWeChat 公众号 Application 配置为移动应用 app_id/secret，
 * 后端用 APP 端微信 SDK 传来的 code 换取 access_token 和用户信息。
 *
 * 与公众号授权的区别：
 * - 授权平台：微信开放平台（open.weixin.qq.com）vs 微信公众平台（mp.weixin.qq.com）
 * - AppID 来源：移动应用配置（wechat_app_setting）vs 公众号配置（wechat_official_setting）
 * - 授权方式：APP 端通过微信 SDK 获取 code，后端通过 code 换取 access_token 和 openid
 * - OpenID 字段：wx_app_openid vs wx_oauth_openid
 * - 使用场景：APP 内微信登录 vs 微信浏览器内授权
 *
 * 重要说明：
 * - 移动应用和公众号使用不同的 AppID，同一用户的 openid 也不同
 * - 但通过 unionid 可以识别为同一用户（需绑定到同一开放平台账号）
 *
 * 参考文档：
 * - https://easywechat.com/6.x/oauth.html
 * - https://developers.weixin.qq.com/doc/oplatform/Mobile_App/WeChat_Login/Authorized_API_call_UnionID.html
 */
class DeshangAppEasyService extends BaseDeshangService
{
    /**
     * @var Application EasyWeChat 应用实例（仅用于 OAuth，配置为移动应用）
     */
    protected $easy_wechat_app;

    /**
     * @var int 商户ID
     */
    protected $merchant_id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初始化移动应用配置并创建 EasyWeChat 应用（与 DeshangOfficialEasyService::init 同风格）
     *
     * @param int $merchant_id 商户ID
     * @return $this 当前对象实例（用于链式调用）
     * @throws CommonException
     */
    public function init(int $merchant_id)
    {
        $this->merchant_id = $merchant_id;

        $app_setting = (new DeshangAppSettingService())->getWechatAppSetting($merchant_id);
        if (empty($app_setting['app_id'])) {
            throw new CommonException('移动应用配置信息错误');
        }

        // 与公众号/网站应用服务同结构：构建 config，创建 Application；移动应用仅用 OAuth，token/aes_key 可为空
        $config = [
            'app_id' => $app_setting['app_id'],
            'secret' => $app_setting['app_secret'],
            'token' => '',
            'aes_key' => '',

            // 移动应用通过 code 换用户信息，使用 snsapi_userinfo 拉取昵称、头像
            'oauth' => [
                'redirect_url' => '',
                'scopes' => ['snsapi_userinfo'],
            ],

            'http' => [
                'timeout' => 5.0,
                'retry' => true,
            ],
        ];

        $this->easy_wechat_app = new Application($config);

        return $this;
    }

    /**
     * 获取 EasyWeChat 应用实例（与 DeshangOfficialEasyService::getApp 一致）
     *
     * @return Application
     * @throws CommonException
     */
    public function getApp()
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        return $this->easy_wechat_app;
    }

    /**
     * 通过授权 Code 获取用户信息（仅用于 wechat_app 场景）
     *
     * 实现方式与 DeshangOfficialEasyService::getWechatInfoByCode 一致：getOAuth()->scopes()->userFromCode()
     * Code 由 APP 端微信 SDK 获取后传给后端。
     *
     * @param string $code 授权 code（从 APP 端微信 SDK 获取）
     * @return array 用户信息：openid、unionid、nickname、avatar、original
     * @throws CommonException
     */
    public function getWechatInfoByCode(string $code): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        try {
            $user = $this->easy_wechat_app->getOAuth()->scopes(['snsapi_userinfo'])->userFromCode($code);
            $original = $user->getRaw();

            return [
                'openid' => $user->getId(),
                'unionid' => $original['unionid'] ?? '',
                'nickname' => $user->getNickname(),
                'avatar' => $user->getAvatar(),
                'original' => $original,
            ];
        } catch (\Exception $e) {
            throw new CommonException('获取用户信息失败：' . $e->getMessage());
        }
    }
}
