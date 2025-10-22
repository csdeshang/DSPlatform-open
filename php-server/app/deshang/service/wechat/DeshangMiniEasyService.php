<?php

namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;
use EasyWeChat\MiniApp\Application;
use Symfony\Component\HttpFoundation\Response;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;

//EasyWeChat 小程序服务类  https://easywechat.com/6.x/mini-app/config.html

class DeshangMiniEasyService extends BaseDeshangService
{
    /**
     * @var Application EasyWeChat小程序应用实例
     */
    protected $easy_wechat_app;

    /**
     * @var array 配置信息
     */
    protected $config;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 初始化EasyWeChat小程序应用
     * 
     * @param int $merchant_id 商户ID
     * @return $this 当前对象实例（用于链式调用）
     * @throws CommonException
     */
    public function init(int $merchant_id)
    {
        // 获取小程序配置信息
        $mini_setting = (new DeshangMiniSettingService())->getWechatMiniSetting($merchant_id);
        if (empty($mini_setting['app_id'])) {
            throw new CommonException('小程序配置信息错误');
        }

        // 构建配置
        $config = [
            'app_id' => $mini_setting['app_id'],
            'secret' => $mini_setting['app_secret'],


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
     * 通过授权Code获取用户信息
     * 
     * @param string $code 授权code
     * @return array 用户信息
     * @throws CommonException
     */
    public function getWechatInfoByCode(string $code)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        try {

            // https://easywechat.com/6.x/mini-app/utils.html
            
            $wechat_info = $this->easy_wechat_app->getUtils()->codeToSession($code);

            return [
                'openid' => $wechat_info['openid'] ?? '',
                'unionid' => $wechat_info['unionid'] ?? '',
            ];
        } catch (\Exception $e) {
            throw new CommonException('获取用户信息失败：' . $e->getMessage());
        }
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



    // 获取微信小程序消息模板列表 
    // https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/getMessageTemplateList.html
    public function  getTemplateList()
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }
        $response = $this->easy_wechat_app->getClient()->get('/wxaapi/newtmpl/gettemplate');

        return $response->toArray();
    }


    // 添加微信小程序消息模板
    // https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/addMessageTemplate.html
    public function addMessageTemplate(string $tid, array $kidList , string $sceneDesc)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $response = $this->easy_wechat_app->getClient()->post('/wxaapi/newtmpl/addtemplate', [
            'json' => [
                "tid" => $tid,
                "kidList" => $kidList,
                "sceneDesc" => $sceneDesc
            ]
        ]);
        return $response->toArray();
    }


    // 删除微信小程序消息模板
    // https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/deleteMessageTemplate.html
    public function delMessageTemplate(string $priTmplId)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $response = $this->easy_wechat_app->getClient()->post('/wxaapi/newtmpl/deltemplate', [
            'json' => [
                "priTmplId" => $priTmplId
            ]
        ]);

        return $response->toArray();
    }





    // 发送微信小程序订阅消息
    // https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/mp-message-management/subscribe-message/sendMessage.html
    public function sendSubscribeMessage(string $template_id, string $page, string $openid, array $data)
    {
        if (empty($this->easy_wechat_app)) {
            throw new CommonException('请先调用init方法初始化');
        }

        $result = $this->easy_wechat_app->getClient()->postJson('/cgi-bin/message/subscribe/send', [
            'template_id' => $template_id,
            'page' => $page,
            'touser' => $openid,
            'data' => $data,
        ]);
        return $result->toArray();
    }


}
