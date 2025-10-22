<?php

namespace app\deshang\third_party\trade\providers;

use app\deshang\third_party\trade\providers\BaseTrade;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Contract\HttpClientInterface;

use app\deshang\exceptions\CommonException;

use app\common\enum\trade\TradePaymentConfigEnum;




// 微信支付
class Wechat extends BaseTrade
{

    public function __construct(array $config)
    {
        $wechat_config = [
            'wechat' => [
                'default' => [
                    // 「必填」商户号，服务商模式下为服务商商户号
                    // 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
                    'mch_id' => $config['mch_id'],
                    // 「选填」v2商户私钥
                    // 'mch_secret_key_v2' => '',
                    // 「必填」v3 商户秘钥
                    // 即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
                    'mch_secret_key' => $config['mch_secret_key'],
                    // 「必填」商户私钥 字符串或路径
                    // 即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得
                    // 文件名形如：apiclient_key.pem
                    'mch_secret_cert' => $config['mch_secret_cert_path'],
                    // 「必填」商户公钥证书路径
                    // 即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得
                    // 文件名形如：apiclient_cert.pem
                    'mch_public_cert_path' => $config['mch_public_cert_path'],
                    // 「必填」微信回调url
                    // 不能有参数，如?号，空格等，否则会无法正确回调
                    'notify_url' => $config['notify_url'],
                    // 「选填」公众号 的 app_id
                    // 可在 mp.weixin.qq.com 设置与开发->基本配置->开发者ID(AppID) 查看
                    // 'mp_app_id' => '2016082000291234',
                    // 「选填」小程序 的 app_id
                    // 'mini_app_id' => '',
                    // 「选填」app 的 app_id
                    // 'app_id' => '',
                    // 「选填」服务商模式下，子公众号 的 app_id
                    // 'sub_mp_app_id' => '',
                    // 「选填」服务商模式下，子 app 的 app_id
                    // 'sub_app_id' => '',
                    // 「选填」服务商模式下，子小程序 的 app_id
                    // 'sub_mini_app_id' => '',
                    // 「选填」服务商模式下，子商户id
                    // 'sub_mch_id' => '',
                    // 「选填」（适用于 2024-11 及之前开通微信支付的老商户）微信支付平台证书序列号及证书路径，强烈建议 php-fpm 模式下配置此参数
                    // 「必填」微信支付公钥ID及证书路径，key 填写形如 PUB_KEY_ID_0000000000000024101100397200000006 的公钥id，见 https://pay.weixin.qq.com/doc/v3/merchant/4013053249
                    'wechat_public_cert_path' => [
                        // '45F59D4DABF31918AFCEC556D5D2C6E376675D57' => __DIR__ . '/Cert/wechatPublicKey.crt',
                        $config['wechat_public_cert_id'] => $config['wechat_public_cert_path'],
                    ],
                    // 「选填」默认为正常模式。可选为： MODE_NORMAL, MODE_SERVICE
                    'mode' => Pay::MODE_NORMAL,
                ]
            ],
        ];


        // 微信官方公众号支付
        if ($config['trade_scene'] == TradePaymentConfigEnum::SCENE_WECHAT_OFFICIAL) {
            $wechat_config['wechat']['default']['mp_app_id'] = $config['app_id'];
        }
        // 微信小程序支付
        if ($config['trade_scene'] == TradePaymentConfigEnum::SCENE_WECHAT_MINI) {
            $wechat_config['wechat']['default']['mini_app_id'] = $config['app_id'];
        }
        // APP支付
        if ($config['trade_scene'] == TradePaymentConfigEnum::SCENE_APP) {
            $wechat_config['wechat']['default']['app_id'] = $config['app_id'];
        }

        //PC支付  https://pay.weixin.qq.com/doc/v3/merchant/4012791877
        if ($config['trade_scene'] == TradePaymentConfigEnum::SCENE_PC) {
            $wechat_config['wechat']['default']['mp_app_id'] = $config['app_id'];
        }


        $config = array_merge($wechat_config, $this->config);



        Pay::config($config);
    }


    // 网页支付 (支付宝网页支付)
    public function web(array $data)
    {

        return true;
    }

    // mp 公众号支付
    public function mp(array $data)
    {

        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'description' => $data['subject'],
            'amount' => [
                'total' => intval($data['total_amount'] * 100),
            ],
            'payer' => [
                'openid' => $data['openid'],
            ],
        ];


        $result = Pay::wechat()->mp($order);

        return $this->parseResponse($result);
    }

    // H5 支付
    public function h5(array $data)
    {
        $result = Pay::wechat()->h5($data);
        return $this->parseResponse($result);
    }

    // app 支付
    public function app(array $data)
    {
        return true;
    }

    // mini 小程序支付 https://pay.yansongda.cn/docs/v3/wechat/pay.html
    public function mini(array $data)
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'description' => $data['subject'],
            'amount' => [
                'total' => intval($data['total_amount'] * 100),
                'currency' => 'CNY',
            ],
            'payer' => [
                'openid' => $data['openid'],
            ],
        ];


        $result = Pay::wechat()->mini($order);

        return $this->parseResponse($result);
    }

    // pos 支付
    public function pos(array $data)
    {
        return true;
    }

    // scan 扫码支付 https://pay.yansongda.cn/docs/v3/wechat/pay.html
    public function scan(array $data)
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'description' => $data['subject'],
            'amount' => [
                'total' => intval($data['total_amount'] * 100),
            ],
        ];


        try {
            $result = Pay::wechat()->scan($order);
            return $this->parseResponse($result);
        } catch (\Exception $e) {
            halt($e);
            throw new CommonException($e->getMessage());
        }
    }

    // transfer 转账
    public function transfer(array $data)
    {
        return true;
    }

    // query 查询
    public function query(array $data)
    {
        return true;
    }

    // {
    //     "amount": {
    //       "currency": "CNY",
    //       "discount_refund": 0,
    //       "from": [],
    //       "payer_refund": 1,
    //       "payer_total": 1,
    //       "refund": 1,
    //       "refund_fee": 0,
    //       "settlement_refund": 1,
    //       "settlement_total": 1,
    //       "total": 1
    //     },
    //     "channel": "ORIGINAL",
    //     "create_time": "2025-05-07T16:47:00+08:00",
    //     "funds_account": "AVAILABLE",
    //     "out_refund_no": "refund2025050712345642141212221",
    //     "out_trade_no": "wec2025050708501980c785868990955",
    //     "promotion_detail": [],
    //     "refund_id": "50300303362025050769079575891",
    //     "status": "SUCCESS",
    //     "success_time": "2025-05-07T16:47:02+08:00",
    //     "transaction_id": "4200002611202505071376201763",
    //     "user_received_account": "支付用户零钱"
    //   }

    // [
    //     'amount' => [
    //         'currency'          => 'CNY',         // 货币类型：人民币
    //         'discount_refund'   => 0,             // 优惠券退款金额：0分
    //         'from'              => [],             // 资金出资账户：空数组表示无其他出资方
    //         'payer_refund'      => 2,             // 用户实际退款金额：2分
    //         'payer_total'       => 2,             // 用户原始支付金额：2分
    //         'refund'            => 2,             // 申请退款金额：2分（全额退款）
    //         'refund_fee'        => 0,             // 退款手续费：0分
    //         'settlement_refund' => 2,             // 应结退款金额：2分
    //         'settlement_total'  => 2,             // 应结订单金额：2分
    //         'total'            => 2               // 订单总金额：2分
    //     ],
    //     'channel'               => 'ORIGINAL',    // 退款渠道：原路退回
    //     'create_time'           => '2025-05-07T21:57:00+08:00', // 退款创建时间（北京时间）
    //     'funds_account'         => 'AVAILABLE',   // 资金账户：可用余额
    //     'out_refund_no'         => 'ref20250507215700668e96068485047', // 商户退款单号
    //     'out_trade_no'          => 'wec20250507215540ea79f6096939091', // 商户原支付订单号
    //     'promotion_detail'      => [],            // 优惠详情：空数组表示无优惠
    //     'refund_id'             => '50303003232025050763936379469', // 微信退款单号
    //     'status'                => 'PROCESSING',  // 退款状态：处理中
    //     'transaction_id'        => '4200002597202505070321301790', // 微信支付订单号
    //     'user_received_account' => '支付用户零钱'  // 退款入账账户（解码后）
    // ];
    public function refund(array $data):bool
    {



        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'out_refund_no' => $data['out_refund_no'],
            'amount' => [
                'refund' => intval($data['refund_amount'] * 100),
                'total' => intval($data['total_amount'] * 100),
                'currency' => 'CNY',
            ],
            // '_action' => 'jsapi', // jsapi 退款，默认
            // '_action' => 'app', // app 退款
            // '_action' => 'combine', // 合单退款
            // '_action' => 'h5', // h5 退款
            // '_action' => 'mini', // 小程序退款
            // '_action' => 'native', // native 退款
        ];
        $result = $this->parseResponse(Pay::wechat()->refund($order));

        // 测试写入系统日志
        // writeSysAccessLog($result);


        // 发起退款成功 第一次是PROCESSING 第二次成功后是SUCCESS
        return $result['status'] == 'SUCCESS' || $result['status'] == 'PROCESSING' ? true : false;
    }

    // close 关闭
    public function close(array $data)
    {
        return true;
    }

    public function cancel(array $data)
    {
        return true;
    }

    // 接收回调 正常返回

    // [
    //     'id' => 'a554f3f7-7a39-567a-8597-3aa320e35dc1',
    //     'create_time' => '2025-05-05T23:50:11+08:00',
    //     'resource_type' => 'encrypt-resource',
    //     'event_type' => 'TRANSACTION.SUCCESS',
    //     'summary' => '支付成功',
    //     'resource' => [
    //         'original_type' => 'transaction',
    //         'algorithm' => 'AEAD_AES_256_GCM',
    //         'ciphertext' => [
    //             'mchid' => '1224930902',
    //             'appid' => 'wxc3775cf2340d95b2',
    //             'out_trade_no' => 'wec20250505235006f80814610649082',
    //             'transaction_id' => '4200002658202505056631535706',
    //             'trade_type' => 'JSAPI',
    //             'trade_state' => 'SUCCESS',
    //             'trade_state_desc' => '支付成功',
    //             'bank_type' => 'OTHERS',
    //             'attach' => '',
    //             'success_time' => '2025-05-05T23:50:11+08:00',
    //             'payer' => [
    //                 'openid' => 'od6X7t1pRxbXQkuSTPmXsowybuXM'
    //             ],
    //             'amount' => [
    //                 'total' => 1,
    //                 'payer_total' => 1,
    //                 'currency' => 'CNY',
    //                 'payer_currency' => 'CNY'
    //             ]
    //         ],
    //         'associated_data' => 'transaction',
    //         'nonce' => 'KkGmWmwW4QW1'
    //     ]
    // ]
    public function callback()
    {
        $result = Pay::wechat()->callback();
        $result = $this->parseResponse($result);

        $ciphertext = $result['resource']['ciphertext'];


        return array(
            'status' => 'success',
            'buyer_id' => $ciphertext['payer']['openid'],
            'seller_id' => $ciphertext['mchid'],
            'trade_no' => $ciphertext['transaction_id'],
            'out_trade_no' => $ciphertext['out_trade_no'],
            'total_amount' => $ciphertext['amount']['total'] / 100,
        );
    }

    // 确认回调
    public function confirm()
    {
        return Pay::wechat()->success();
    }
}
