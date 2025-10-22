<?php

namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;
use EasyWeChat\OfficialAccount\Application;
use Symfony\Component\HttpFoundation\Response;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;

//EasyWeChat 公众号服务类  https://easywechat.com/6.x/official-account/config.html

class DeshangOfficialEasyService extends BaseDeshangService
{
    /**
     * @var Application EasyWeChat公众号应用实例
     */
    protected $easy_wechat_app;

    /**
     * @var int 商户ID
     */
    protected $merchant_id;

    /**
     * @var array 配置信息
     */
    protected $config;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初始化EasyWeChat公众号应用
     * 
     * @param int $merchant_id 商户ID
     * @return $this 当前对象实例（用于链式调用）
     * @throws CommonException
     */
    public function init(int $merchant_id)
    {
        $this->merchant_id = $merchant_id;

        // 获取公众号配置信息
        $official_setting = (new DeshangOfficialSettingService())->getWechatOfficialSetting($merchant_id);
        if (empty($official_setting['app_id'])) {
            throw new CommonException('公众号配置信息错误');
        }

        // 构建配置
        // 明文模式请勿填写 EncodingAESKey    plain 明文模式  compatible 兼容模式  safe 安全模式
        $config = [
            'app_id' => $official_setting['app_id'],
            'secret' => $official_setting['app_secret'],
            'token' => $official_setting['token'] ?? '',
            'aes_key' => $official_setting['encryption_type'] == 'plain' ? '' : $official_setting['encoding_aes_key'] ?? '',

            // 接口请求相关配置
            'http' => [
                'timeout' => 5.0,
                'retry' => true,
            ],
        ];

        // 创建应用实例
        $this->easy_wechat_app = new Application($config);

        // 返回当前实例，支持链式调用
        return $this;
    }

    /**
     * 获取EasyWeChat应用实例
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
     * 处理服务器端验证和接收消息
     * 
     * @return Response
     * @throws CommonException
     */
    public function serve()
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $server = $this->easy_wechat_app->getServer();
        $server->with(function ($message, \Closure $next) {
            return (new DeshangOfficialMessageService($this, $this->merchant_id))->processMessage($message->toArray());
        });



        // 处理微信服务器推送的消息
        $response = $server->serve();

        // 将 PSR-7 Response 转换为 ThinkPHP Response   
        // https://easywechat.com/6.x/official-account/server.html#%E6%9C%8D%E5%8A%A1%E7%AB%AF%E9%AA%8C%E8%AF%81
        return response($response->getBody()->getContents())->header([
            'Content-Type' => 'text/plain;charset=utf-8'
        ]);
    }

    /**
     * 获取网页授权URL
     * 
     * @param string $redirect_url 授权后重定向URL
     * @param string $scope 授权类型：snsapi_base或snsapi_userinfo
     * @param string $state 状态参数
     * @return string 授权URL
     * @throws CommonException
     */
    public function getOAuthUrl(string $redirect_url, string $scope = 'snsapi_userinfo', string $state = '')
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        if (!in_array($scope, ['snsapi_base', 'snsapi_userinfo'])) {
            throw new CommonException('无效的授权类型');
        }

        return $this->easy_wechat_app->getOAuth()->scopes([$scope])->redirect($redirect_url, $state);
    }

    /**
     * 通过授权Code获取用户信息
     * 
     * @param string $code 授权code
     * @param string $scope 授权类型：snsapi_base或snsapi_userinfo
     * @return array 用户信息
     * @throws CommonException
     */
    public function getWechatInfoByCode(string $code, string $scope = 'snsapi_userinfo'): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        try {
            // https://easywechat.com/6.x/oauth.html
            $user = $this->easy_wechat_app->getOAuth()->scopes([$scope])->userFromCode($code);

            $original = $user->getRaw();

            return [
                'openid' => $user->getId(),
                'nickname' => $user->getNickname(),
                'avatar' => $user->getAvatar(),
                'unionid' => $original['unionid'] ?? '',
                'original' => $original,
            ];
        } catch (\Exception $e) {
            throw new CommonException('获取用户信息失败：' . $e->getMessage());
        }
    }




    /**
     * 创建自定义菜单
     * 
     * @param array $buttons 菜单按钮数组
     * @return array 创建结果
     * @throws CommonException
     */
    public function createWechatMenu(array $buttons): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        if (empty($buttons)) {
            throw new CommonException('菜单按钮数组不能为空');
        }

        // 构建菜单数据结构
        $menuData = [];

        // 如果传入的数据已经包含 button 结构，直接使用
        if (isset($buttons['button'])) {
            $menuData = $buttons;
        } else {
            // 否则将传入的数组作为 button
            $menuData = ['button' => $buttons];
        }

        // 调用微信创建菜单接口
        // 接口地址：https://developers.weixin.qq.com/doc/service/api/custommenu/api_createcustommenu.html
        $result = $this->easy_wechat_app->getClient()->postJson('/cgi-bin/menu/create', $menuData);
        return $result->toArray();
    }

    /**
     * 获取自定义菜单
     * 
     * @return array 菜单信息
     * @throws CommonException
     */
    public function getWechatMenu(): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        // 调用微信查询菜单接口
        $result = $this->easy_wechat_app->getClient()->get('/cgi-bin/menu/get');
        return $result->toArray();
    }

    /**
     * 删除自定义菜单
     * 
     * @return array 删除结果
     * @throws CommonException
     */
    public function deleteWechatMenu(): array
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        // 调用微信删除菜单接口
        $result = $this->easy_wechat_app->getClient()->get('/cgi-bin/menu/delete');
        return $result->toArray();
    }


    /**
     * 获取微信公众号用户信息
     * 
     * @param string $openid 用户openid
     * @return array 用户信息
     * @throws CommonException
     * 
     */
    public function getWechatUserInfo(string $openid)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        // https://developers.weixin.qq.com/doc/service/api/usermanage/userinfo/api_userinfo.html

        $result = $this->easy_wechat_app->getClient()->get('/cgi-bin/user/info', ['openid' => $openid]);
        return $result->toArray();
    }


    /**
     * 获取JS SDK配置
     * 
     * @param string $url 当前网页URL
     * @param bool $debug 是否开启调试模式
     * @return array JS SDK配置
     * @throws CommonException
     */
    public function getJsSdkConfig(string $url, bool $debug = false)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        // https://easywechat.com/6.x/official-account/utils.html
        $result = $this->easy_wechat_app->getUtils()->buildJsSdkConfig(
            url: $url,
            jsApiList: [],
            openTagList: [],
            debug: $debug,
        );

        return $result;
    }

    /**
     * 获取AccessToken
     * 
     * @param bool $refresh 是否强制刷新
     * @return string AccessToken
     * @throws CommonException
     */
    public function getAccessToken(bool $refresh = false)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        try {
            $accessToken = $this->easy_wechat_app->getAccessToken();
            if ($refresh) {
                $accessToken->refresh();
            }
            return $accessToken->getToken();
        } catch (\Exception $e) {
            throw new CommonException('获取AccessToken失败：' . $e->getMessage());
        }
    }



    // 获取公众号消息模板列表 
    // https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html
    public function getTemplateList()
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $result = $this->easy_wechat_app->getClient()->get('/cgi-bin/template/get_all_private_template');
        return $result->toArray();
    }


    
    /**
     * 发送模板消息
     * https://developers.weixin.qq.com/doc/service/api/notify/template/api_sendtemplatemessage.html
     * @param string $openid 用户openid
     * @param string $template_id 模板ID
     * @param array $data 模板数据
     * @param string $url 跳转链接（可选）
     * @param array $miniprogram 小程序参数（可选）
     * @return array 发送结果
     * @throws CommonException
     */
    public function sendTemplateMessage(string $openid, string $template_id, array $data, string $url = '', array $miniprogram = [])
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $message = [
            'touser' => $openid,
            'template_id' => $template_id,
            'data' => $data,
        ];

        // 添加可选参数
        if (!empty($url)) {
            $message['url'] = $url;
        }

        if (!empty($miniprogram)) {
            $message['miniprogram'] = $miniprogram;
        }

        $result = $this->easy_wechat_app->getClient()->postJson('/cgi-bin/message/template/send', $message);
        return $result->toArray();
    }

    /**
     * 添加公众号模板消息
     * https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html
     * 
     * @param string $template_id_short 模板库中模板的编号，有"TM**"和"OPENTMTM**"等形式
     * @param array $keyword_name_list 关键词名称列表，按顺序传入
     * @return array
     * @throws CommonException
     */
    public function addMessageTemplate(string $template_id_short, array $keyword_name_list = [])
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $result = $this->easy_wechat_app->getClient()->postJson('/cgi-bin/template/api_add_template', [
            'template_id_short' => $template_id_short,
            'keyword_name_list' => $keyword_name_list,
        ]);
        return $result->toArray();

    }

    /**
     * 删除公众号模板消息
     * https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html
     * 
     * @param string $template_id 公众号模板ID
     * @return array
     * @throws CommonException
     */
    public function delMessageTemplate(string $template_id)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $response = $this->easy_wechat_app->getClient()->post('/cgi-bin/template/del_private_template', [
            'json' => [
                "template_id" => $template_id
            ]
        ]);

        return $response->toArray();
    }
}
