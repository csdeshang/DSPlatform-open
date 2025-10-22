<?php

namespace app\deshang\third_party\trade\providers;

use app\deshang\third_party\trade\providers\BaseTrade;
use app\deshang\exceptions\CommonException;

use Yansongda\Pay\Pay;
use Yansongda\Pay\Contract\HttpClientInterface;





// 支付宝支付  https://pay.yansongda.cn/docs/v3/alipay/refund.html
class Alipay extends BaseTrade
{

    public function __construct(array $config)
    {
        $alipay_config = [
            'alipay' => [
                'default' => [
                    // 「必填」支付宝分配的 app_id
                    'app_id' => $config['app_id'],
                    // 「必填」应用私钥 字符串或路径
                    // 在 https://open.alipay.com/develop/manage 《应用详情->开发设置->接口加签方式》中设置
                    'app_secret_cert' => $config['app_secret_cert'],
                    // 「必填」应用公钥证书 路径
                    // 设置应用私钥后，即可下载得到以下3个证书
                    'app_public_cert_path' => $config['app_public_cert_path'],
                    // 「必填」支付宝公钥证书 路径
                    'alipay_public_cert_path' => $config['alipay_public_cert_path'],
                    // 「必填」支付宝根证书 路径
                    'alipay_root_cert_path' => $config['alipay_root_cert_path'],
                    'return_url' => $config['return_url'],
                    'notify_url' => $config['notify_url'],
                    // 「选填」第三方应用授权token
                    'app_auth_token' => '',
                    // 「选填」服务商模式下的服务商 id，当 mode 为 Pay::MODE_SERVICE 时使用该参数
                    'service_provider_id' => '',
                    // 「选填」默认为正常模式。可选为： MODE_NORMAL, MODE_SANDBOX, MODE_SERVICE
                    'mode' => Pay::MODE_NORMAL,
                ]
            ],
        ];
        $config = array_merge($alipay_config, $this->config);

        Pay::config($config);
    }


    // 网页支付
    public function web(array $data)
    {

        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'total_amount' => $data['total_amount'],
            'subject' => $data['subject'],
        ];

        $result = Pay::alipay()->web($order);
        return $this->parseResponse($result);
    }

    // mp 公众号支付
    public function mp(array $data)
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'total_amount' => $data['total_amount'],
            'subject' => $data['subject'],
        ];

        $result = Pay::alipay()->mp($order);
        return $this->parseResponse($result);
    }

    // H5 支付
    public function h5(array $data)
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'total_amount' => $data['total_amount'],
            'subject' => $data['subject'],
        ];
        $result = Pay::alipay()->h5($order);
        return $this->parseResponse($result);
    }

    // app 支付
    public function app(array $data)
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'total_amount' => $data['total_amount'],
            'subject' => $data['subject'],
        ];
        $result = Pay::alipay()->app($order);
        return $this->parseResponse($result);
    }

    // mini 小程序支付
    public function mini(array $data)
    {
        return true;
    }

    // pos 支付
    public function pos(array $data)
    {
        return true;
    }

    // scan 扫码支付
    public function scan(array $data)
    {
        return true;
    }

    // transfer 转账
    // 文档 https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.uni.transfer?scene=common

    // 错误返回
    // array(
    //     "_sign" => "h/AQbZRYodBRAjYgm0+ADe+eWDn2d81YM6eKpb2vyvIRgDPTKSSq7qe6MTmqg1X60RCZAw2oAykGQMdcfF4FpqVcldw6nWqSrA2cTYNXi4MAu454rTaN0bhyzjU0R9wuJjESINOUHs+CKbYMXALzIzJMlCkL6oX6GlVeV3nyc8H4ueoR7yhYs+UA7GklxVoSOgr1Tkf6nW80vzfb/6r+XvngsvoNArHjVLN8ObmkzkQyH4zaCkj/KS+cq8gP/9ZaU0lQRGJF/nPPBGuFfMz6vOV+Y5TRXSuBCEgcSasbBBM5fuYKlsP7ppVbXPTC5CbWQrSAzI6SbL1o96P2os/SJw==",
    //     "msg" => "Business Failed",
    //     "code" => "40004",
    //     "sub_msg" => "收款账号不存在或姓名有误，建议核实账号和姓名是否准确",
    //     "sub_code" => "PAYEE_NOT_EXIST"
    // );

    // 成功
    // array(
    //     "_sign" => "N7E6rI0uqzYgAot4jE03Z05w2pFmBetY3bmFefc10MkrxKn511V5MVJj9AtNA8tr91eR0hIPc0N3COjmLkHAj1Ws3IHOIMBt4BC6tSmgsivcmf4dulHpp3CPNoyOv3QDMCZ/gVdQCkwyyisclxz09M1+ngr6SuMro9Fst8DBcf7GiWdw6hUGFcoWW35XbQ6BWkEbOIuK56CIeTAcBm16/J+FmSjBFbGzIKyFDXTiirjmdbdVKbSEJgjE3BIll3DnJJpyRYwpKFIg1BLRo1hPT4UkSO28Z3MQ0ZdE14u7NR7TS65W9iGUxvQJmKGJQ5tC/0KTagplyq1Ee6Fk0seMvw==",
    //     "code" => "10000",
    //     "msg" => "Success",
    //     "order_id" => "20250508020070011550930023266489",
    //     "out_biz_no" => "tra20250508152442ed3260445911330",
    //     "pay_fund_order_id" => "20250508020070011550930023266489",
    //     "status" => "SUCCESS",
    //     "trans_date" => "2025-05-08 15:24:43"
    // );



    public function transfer(array $data)
    {


        // 转账到银行卡功能暂未全面开放，详情可以点击单笔转账到银行卡了解更多 
        // https://opendocs.alipay.com/apis/api_28/alipay.fund.trans.uni.transfer?scene=66dd06f5a923403393b85de68d3c0055

        $order = [
            'out_biz_no' => $data['out_transfer_no'],
            'trans_amount' => $data['transfer_amount'],
            'product_code' => $data['account_type'] == 'alipay' ? 'TRANS_ACCOUNT_NO_PWD' : 'TRANS_BANKCARD_NO_PWD',
            'biz_scene' => 'DIRECT_TRANSFER',
            'payee_info' => [
                'identity' => $data['account_number'],
                'identity_type' => $data['account_type'] == 'alipay' ? 'ALIPAY_LOGON_ID' : 'BANKCARD_ACCOUNT',
                'name' => $data['account_holder']
            ],
        ];


        $result = Pay::alipay()->transfer($order);
        $result = $this->parseResponse($result);

        // 测试写入系统日志
        // writeSysAccessLog($result);


        return [
            'status' => $result['code'] == '10000' ? 'success' : 'fail',
            // 支付宝返回的转账单号
            'transfer_no' => $result['order_id'] ?? '',
            'message' => $result['sub_msg'] ?? $result['msg'],
            'response' => $result,
        ];

    }

    // query 查询
    public function query(array $data)
    {
        return true;
    }



    // 退款
    // [
    //     '_sign' => 'Z7IfCEA7bUJOA8uH7bVMla/zCxBfer+1soLaWQScHGH5GbwHZElZwit3/IGs0nQckE3ExVnpsbrPXFZT6ueY/NScaipIfueOGIUM66NVlkevQEhNDGVtt89d+LEgz+9cti4qddCjc6fJwclXhINSqh/xJloMTcKixAI/geVytJ47oY0fv4goXl61UDm0yszp+TAI7TNUT3/oM6pZ/X+BTyCJdqt6Wp2nPplf3J9Jkrt1f9Bu1IMn42gcV3I57sKpfFRL6E477FPpC1CJTeGsdAi7XoJwKBO5g5kV938GXw1soLv0A9sema1JcNNuH39yfMU2y80mSgtrNwXdW6ACQw==',
    //     'code' => '10000',
    //     'msg' => 'Success',
    //     'buyer_logon_id' => '121212***@126.com',
    //     'buyer_user_id' => '121212122112',
    //     'fund_change' => 'N',
    //     'gmt_refund_pay' => '2025-05-07 18:14:04',
    //     'out_trade_no' => 'ali2025050718114074ac67503770690',
    //     'refund_detail_item_list' => [
    //         [
    //             'amount' => '0.02',
    //             'fund_channel' => 'ALIPAYACCOUNT'
    //         ]
    //     ],
    //     'refund_fee' => '0.02',
    //     'send_back_fee' => '0.02',
    //     'trade_no' => '2025050722001481931412575747'
    // ]

    public function refund(array $data):bool
    {
        $order = [
            'out_trade_no' => $data['out_trade_no'],
            'refund_amount' => $data['refund_amount'],
            // '_action' => 'web', // 默认值，退款网页订单
            // '_action' => 'app', // 退款 APP 订单
            // '_action' => 'mini', // 退款小程序订单
            // '_action' => 'pos', // 退款刷卡订单
            // '_action' => 'scan', // 退款扫码订单
            // '_action' => 'h5', // 退款 H5 订单
        ];

        $result = $this->parseResponse(Pay::alipay()->refund($order));

        //         // 测试写入系统日志
        // writeSysAccessLog($result);
        
        return $result['code'] == '10000' ? true : false;
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
    //     {"merchant_id": "0"},
    //     {"trade_channel": "ali_pay"},
    //     {"trade_scene": "pc"},
    //     {"trade_type": "pay"},
    //     {"gmt_create": "2025-05-01 01:49:57"},
    //     {"charset": "utf-8"},
    //     {"gmt_payment": "2025-05-01 01:50:01"},
    //     {"notify_time": "2025-05-01 02:15:19"},
    //     {"subject": "支付订单"},
    //     {"sign": "m2V3tchZ2mI2F8juqVLcJY5G6mRS1wMMmtAXKEy/NuOPdHXx9wjvrrlsPJPQtD7I7EBQz8C57A2slkOpjAajT1qnoEl3b2/VgrZKVcc10kTJ7ZFNB2IizT8cUIIW9uNCMweBAHvvq0sYywV6rGQ0aUyb7N0e8el1/hlPLq3+HRBdDm8CXayteqnX+KhY/fSjXIWgeRXKuZShz4U4p0jsNEKQOfSvjqq0viv5PbznzD6X/hKdS0VQq72weaTgX5TYvp/pb1rP3gCc0GOLmsSv+3cv7aYAgPN6wehezZSYDTYsrR9r07Sxtef8REVbwajVcPZ56d/hikfX7Yr11jq7Gg=="},
    //     {"buyer_id": "2088002237181934"},
    //     {"invoice_amount": "0.01"},
    //     {"version": "1.0"},
    //     {"notify_id": "2025050101222015001081931432357509"},
    //     {"fund_bill_list": "[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]"},
    //     {"notify_type": "trade_status_sync"},
    //     {"out_trade_no": "ali20250501014952c9d417354974254"},
    //     {"total_amount": "0.01"},
    //     {"trade_status": "TRADE_SUCCESS"},
    //     {"trade_no": "2025050122001481931435690657"},
    //     {"auth_app_id": "2017042506954908"},
    //     {"receipt_amount": "0.01"},
    //     {"point_amount": "0.00"},
    //     {"buyer_pay_amount": "0.01"},
    //     {"app_id": "2017042506954908"},
    //     {"sign_type": "RSA2"},
    //     {"seller_id": "2088511418965280"}
    // ]
    
    public function callback()
    {
        $result = Pay::alipay()->callback();
        $result = $this->parseResponse($result);

        return array(
            'status' => 'success',
            'buyer_id' => $result['buyer_id'],
            'seller_id' => $result['seller_id'],
            'trade_no' => $result['trade_no'],
            'out_trade_no' => $result['out_trade_no'],
            'total_amount' => $result['total_amount'],
        );
    }

    // 确认回调
    public function confirm()
    {
        $result = Pay::alipay()->success();
        return $this->parseResponse($result);
    }
    
}
