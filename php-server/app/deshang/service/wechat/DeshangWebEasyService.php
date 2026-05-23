<?php

namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;
use EasyWeChat\OfficialAccount\Application;

/**
 * 微信开放平台网站应用服务类（与 DeshangOfficialEasyService 同风格，使用 EasyWeChat Application + OAuth）
 *
 * 用于处理PC端扫码登录（网站应用授权）。通过 EasyWeChat 公众号 Application 配置为网站应用 app_id/secret，
 * 并设置 oauth.scopes = ['snsapi_login']，底层走 qrconnect 接口。
 *
 * 与公众号授权的区别：
 * - 授权平台：微信开放平台（open.weixin.qq.com）vs 微信公众平台（mp.weixin.qq.com）
 * - 授权URL：qrconnect接口 vs oauth2/authorize接口
 * - AppID来源：网站应用配置（wechat_web_setting）vs 公众号配置（wechat_official_setting）
 * - 授权范围：固定为snsapi_login vs snsapi_base/snsapi_userinfo
 * - OpenID字段：wx_web_openid vs wx_oauth_openid
 * - 使用场景：PC端扫码登录 vs 微信浏览器内授权
 *
 * 重要说明：
 * - 网站应用和公众号使用不同的AppID，同一用户的openid也不同
 * - 但通过unionid可以识别为同一用户（需绑定到同一开放平台账号）
 *
 * 参考文档：
 * - https://easywechat.com/6.x/oauth.html（网页授权，开放平台网页登录）
 * - https://developers.weixin.qq.com/doc/oplatform/Website_App/WeChat_Login/Wechat_Login.html
 */
class DeshangWebEasyService extends BaseDeshangService
{
    /**
     * @var Application EasyWeChat 应用实例（仅用于 OAuth，配置为网站应用 + snsapi_login）
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
     * 初始化网站应用配置并创建 EasyWeChat 应用（与 DeshangOfficialEasyService::init 同风格）
     *
     * @param int $merchant_id 商户ID
     * @return $this 当前对象实例（用于链式调用）
     * @throws CommonException
     */
    public function init(int $merchant_id)
    {
        $this->merchant_id = $merchant_id;

        $web_setting = (new DeshangWebSettingService())->getWechatWebSetting($merchant_id);
        if (empty($web_setting['app_id'])) {
            throw new CommonException('网站应用配置信息错误');
        }

        // 与公众号服务同结构：构建 config，创建 Application；网站应用仅用 OAuth，token/aes_key 可为空
        $config = [
            'app_id' => $web_setting['app_id'],
            'secret' => $web_setting['app_secret'],
            'token' => '',
            'aes_key' => '',

            // 网站应用固定为 snsapi_login，走 qrconnect；与公众号 snsapi_userinfo 不同
            'oauth' => [
                'redirect_url' => '',
                'scopes' => ['snsapi_login'],
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
     * 获取网站应用授权URL（PC扫码登录）
     *
     * 实现方式与 DeshangOfficialEasyService::getOAuthUrl 一致：getOAuth()->scopes()->redirect()
     * 因 config 已设 oauth.scopes = ['snsapi_login']，底层走 qrconnect。
     *
     * @param string $redirect_url 授权后重定向URL
     * @param string $state 状态参数
     * @return string 授权URL
     * @throws CommonException
     */
    public function getQrConnectUrl(string $redirect_url, string $state = ''): string
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        return $this->easy_wechat_app->getOAuth()->scopes(['snsapi_login'])->redirect($redirect_url, $state);
    }

    /**
     * 通过授权Code获取用户信息（仅用于 wechat_web 场景）
     *
     * 实现方式与 DeshangOfficialEasyService::getWechatInfoByCode 一致：getOAuth()->scopes()->userFromCode()
     *
     * @param string $code 授权code（从网站应用授权URL回调获取）
     * @return array 用户信息：openid、unionid、nickname、avatar、original
     * @throws CommonException
     */
    public function getWechatInfoByCode(string $code): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        try {
            $user = $this->easy_wechat_app->getOAuth()->scopes(['snsapi_login'])->userFromCode($code);
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
