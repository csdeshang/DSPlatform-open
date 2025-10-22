<?php

namespace app\deshang\third_party\trade\providers;



use Yansongda\Artful\Exception\InvalidResponseException;


// 交易驱动接口
// https://pay.yansongda.cn/docs/v3/quick-start/alipay.html

abstract class BaseTrade
{


    // 配置
    protected $config = [
        'logger' => [
            'enable' => false,
            'file' => './logs/pay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [ // optional
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
    ];







    // 网页支付
    abstract public function web(array $data);


    // mp 公众号支付
    abstract public function mp(array $data);


    // H5 支付
    abstract public function h5(array $data);


    // app 支付
    abstract public function app(array $data);

    // mini 小程序支付
    abstract public function mini(array $data);


    // pos 支付
    abstract public function pos(array $data);


    // scan 扫码支付
    abstract public function scan(array $data);


    // transfer 转账
    abstract public function transfer(array $data);


    // query 查询
    abstract public function query(array $data);


    // refund 退款
    abstract public function refund(array $data);

    // close 关闭
    abstract public function close(array $data);


    // cancel 取消
    abstract public function cancel(array $data);

    // 接收回调 https://pay.yansongda.cn/docs/v3/alipay/callback.html
    abstract public function callback();

    // 确认回调 https://pay.yansongda.cn/docs/v3/alipay/response.html
    abstract public function confirm();


    // 解析响应
    // https://pay.yansongda.cn/docs/v3/quick-start/return-format.html
    public function parseResponse($response)
    {
        if ($response instanceof \Psr\Http\Message\MessageInterface) {
            // 获取正文内容并返回
            return $response->getBody()->getContents();
        }
        // 如果 $response 是 Collection 类型
        else if ($response instanceof \Yansongda\Supports\Collection) {
            // 将集合转换为数组并返回
            return $response->toArray();
        }
        // 如果 $response 是其他类型，直接返回原始值
        else {
            return $response;
        }
    }
}
