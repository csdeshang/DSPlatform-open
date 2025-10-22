<?php

namespace app\deshang\third_party\sms\providers;
use app\deshang\third_party\sms\providers\BaseSms;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use AlibabaCloud\Tea\Utils\Utils;
use AlibabaCloud\Tea\Tea;
use AlibabaCloud\Tea\Exception\TeaError;

class Aliyun extends BaseSms
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 初始化阿里云客户端
     */
    protected function initClient()
    {
        // 检查配置
        if (empty($this->config['access_key_id']) || empty($this->config['access_key_secret'])) {
            throw new \Exception('阿里云短信配置不完整，缺少AccessKey');
        }

        // 创建配置对象
        $config = new Config([
            'type' => 'access_key',
            'accessKeyId' => $this->config['access_key_id'],
            'accessKeySecret' => $this->config['access_key_secret'],
        ]);
        
        // 设置区域ID
        $config->regionId = isset($this->config['region_id']) ? $this->config['region_id'] : 'cn-hangzhou';
        
        // 设置接入点
        $config->endpoint = 'dysmsapi.aliyuncs.com';
        
        // 创建客户端
        return new Dysmsapi($config);
    }


    // https://help.aliyun.com/zh/sdk/product-overview/alibaba-cloud-sdk
    // https://help.aliyun.com/zh/sms/developer-reference/api-dysmsapi-2017-05-25-sendsms
    // 发送短信
    public function send(string $to, string $templateCode, array|string $templateParam = []): bool
    {
        $client = $this->initClient();
        try {
            // 检查签名
            if (empty($this->config['sign_name'])) {
                throw new \Exception('阿里云短信配置不完整，缺少短信签名');
            }
            
            // 创建请求对象
            $sendSmsRequest = new SendSmsRequest([
                'phoneNumbers' => $to,
                'signName' => $this->config['sign_name'],
                'templateCode' => $templateCode,
            ]);

            
            // 如果有模板参数，则添加
            if (!empty($templateParam)) {
                // 是否为 json 格式
                if (is_array($templateParam)) {
                    $sendSmsRequest->templateParam = json_encode($templateParam, JSON_UNESCAPED_UNICODE);
                } else {
                    $sendSmsRequest->templateParam = $templateParam;
                }
            }

            
            // 发送请求
            $response = $client->sendSms($sendSmsRequest);

            
            // 判断是否发送成功
            if (isset($response->body->Code) && $response->body->Code == 'OK') {
                return true;
            } else {
                $error_msg = isset($response->body->Message) ? $response->body->Message : '未知错误';
                return '阿里云短信发送失败：' . $error_msg;
                // throw new \Exception('短信发送失败：' . $error_msg);
            }
            
        } catch (TeaError $e) {
            // Tea错误
            $error_msg = $e->getMessage();
            return  '阿里云短信发送错误：' . $error_msg;
            // throw new \Exception('阿里云短信发送错误：' . $error_msg);
        } catch (\Exception $e) {
            // 其他错误
            throw $e;
        }
    }
}