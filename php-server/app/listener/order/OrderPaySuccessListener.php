<?php


namespace app\listener\order;

use think\facade\Db;
use app\deshang\exceptions\CommonException;


use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\common\enum\user\UserGrowthEnum;
use app\deshang\service\user\DeshangUserGrowthService;

use app\common\dao\merchant\MerchantDao;

use app\common\dao\store\TblStoreDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderAddressDao;
use app\common\dao\store\TblStorePrinterDao;
use app\common\dao\store\TblStorePrinterLogDao;

use app\deshang\core\ThirdPartyLoader;



class OrderPaySuccessListener
{
    public function handle(array $params)
    {
        // 支付获取积分
        $this->payGetPoints($params);

        // 支付获取成长值
        $this->payGetGrowth($params);

        // 发送消息通知给卖家
        $this->sendMessageToMerchant($params);

        // 打印订单小票
        $this->printOrder($params);
    }

    // 发送消息通知给卖家
    public function sendMessageToMerchant($params)
    {
        $order_info = $params['order_info'];
        // 获取商户关联的用户ID
        $merchant_info = (new MerchantDao())->getMerchantInfo([['id', '=', $order_info['merchant_id']]]);

        event('SysNoticeListener', [
            'key' => 'store_order_payment_success',
            'receiver_params' => [
                'user_id' => $merchant_info['user_id'],
            ],
            // 具体参数  以  sys_notice_tpl 表 为准
            'template_params' => [
                'order_sn' => $order_info['order_sn'],
                'order_id' => $order_info['id'],
                'pay_amount' => $order_info['pay_amount'],
            ]
        ]);
    }


    // 支付获取积分
    public function payGetPoints($params)
    {
        $order_info = $params['order_info'];

        // 支付获取积分
        $points_pay_enabled = sysConfig('points:points_pay_enabled');
        if ($points_pay_enabled == 1) {
            // 积分
            $points_pay_amount = sysConfig('points:points_pay_amount');

            if ($points_pay_amount > 0) {

                $change_num = $points_pay_amount;
                $change_desc = '支付获取积分';

                // 如果开启了按比例获取
                $points_payrate_enabled = sysConfig('points:points_payrate_enabled');
                if ($points_payrate_enabled == 1) {
                    $points_payrate_amount = sysConfig('points:points_payrate_amount');
                    $change_num = intval($points_payrate_amount * $order_info['pay_amount']);
                    $change_desc = '支付获取积分,支付金额' . $order_info['pay_amount'] . '元' . '*' . $points_payrate_amount;
                }


                if ($change_num > 0) {
                    $add_data = array(
                        'user_id' => $order_info['user_id'],
                        'related_id' => $order_info['id'],
                        'change_type' => UserPointsEnum::TYPE_ORDER_PAY,
                        'change_mode' => UserPointsEnum::MODE_INCREASE,
                        'change_num' => $change_num,
                        'change_desc' => $change_desc,
                    );


                    Db::startTrans();
                    try {
                        (new DeshangUserPointsService())->modifyUserPoints($add_data);
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        throw new CommonException('获取到的异常' . $e->getMessage());
                    }
                }
            }
        }
    }


    // 登录获取成长值
    public function payGetGrowth($params)
    {
        $order_info = $params['order_info'];

        // 支付获取成长值
        $growth_pay_enabled = sysConfig('growth:growth_pay_enabled');
        if ($growth_pay_enabled == 1) {
            // 成长值
            $growth_pay_amount = sysConfig('growth:growth_pay_amount');

            if ($growth_pay_amount > 0) {

                $change_num = $growth_pay_amount;
                $change_desc = '支付获取成长值';

                // 如果开启了按比例获取
                $growth_payrate_enabled = sysConfig('growth:growth_payrate_enabled');
                if ($growth_payrate_enabled == 1) {
                    $growth_payrate_amount = sysConfig('growth:growth_payrate_amount');
                    $change_num = intval($growth_payrate_amount * $order_info['pay_amount']);
                    $change_desc = '支付获取成长值,支付金额' . $order_info['pay_amount'] . '元' . '*' . $growth_payrate_amount;
                }

                if ($change_num > 0) {
                    $add_data = array(
                        'user_id' => $order_info['user_id'],
                        'related_id' => $order_info['id'],
                        'change_type' => UserGrowthEnum::TYPE_ORDER_PAY,
                        'change_mode' => UserGrowthEnum::MODE_INCREASE,
                        'change_num' => $change_num,
                        'change_desc' => $change_desc,
                    );

                    Db::startTrans();
                    try {
                        (new DeshangUserGrowthService())->modifyUserGrowth($add_data);
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        throw new CommonException('获取到的异常' . $e->getMessage());
                    }
                }
            }
        }
    }


    // 打印小票
    public function printOrder($params)
    {
        $order_info = $params['order_info'];
        // 获取订单店铺 开启的打印机列表
        $printer_list = (new TblStorePrinterDao())->getStorePrinterList([
            ['store_id', '=', $order_info['store_id']],
            ['is_enabled', '=', 1],
            ['printer_status', '=', 1]
        ]);


        // 获取订单商品
        $orderGoods = (new TblOrderGoodsDao())->getOrderGoodsList([
            ['order_id', '=', $order_info['id']]
        ]);
        // 获取订单地址
        $orderAddress = (new TblOrderAddressDao())->getOrderAddressInfo([
            ['order_id', '=', $order_info['id']]
        ]);
        // 获取店铺信息
        $storeInfo = (new TblStoreDao())->getStoreInfo([
            ['id', '=', $order_info['store_id']]
        ]);

        // 构建打印数据
        $printData = [
            'order_info' => $order_info,
            'order_goods' => $orderGoods,
            'order_address' => $orderAddress,
            'store_info' => $storeInfo,
        ];

        foreach ($printer_list as $printerInfo) {
            // 调用打印机服务
            $printerManager = ThirdPartyLoader::printer($printerInfo['printer_provider']);
            $result = $printerManager->print($printerInfo, $printData, 'order');

            // 记录打印日志
            $logData = [
                'store_id' => $order_info['store_id'],
                'printer_id' => $printerInfo['id'],
                'order_id' => $order_info['id'],
                'print_content' => json_encode($printData, JSON_UNESCAPED_UNICODE),
                'print_status' => $result['success'] ? 1 : 0,
                'print_result' => json_encode($result, JSON_UNESCAPED_UNICODE),
                'print_type' => 1, // 订单打印
            ];
            (new TblStorePrinterLogDao())->createStorePrinterLog($logData);
        }


        return true;
    }
}
