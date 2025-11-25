<?php


namespace app\listener\order;


use app\deshang\queue\core\QueueProducer;
use app\common\enum\system\SysTaskQueueEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\deshang\service\user\DeshangUserGrowthService;
use app\common\dao\merchant\MerchantDao;

use app\common\dao\store\TblStoreDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderAddressDao;
use app\common\dao\store\TblStorePrinterDao;
use app\common\dao\store\TblStorePrinterLogDao;

use app\deshang\core\ThirdPartyLoader;



/**
 * 订单支付成功监听器
 * 
 * 订单支付成功时触发，处理积分奖励、成长值奖励、消息通知、订单打印
 */
class OrderPaySuccessListener
{
    /**
     * 事件处理方法
     * 
     * @param array $params 事件参数，包含：
     *   - order_info: array 订单信息
     * @return void
     */
    public function handle(array $params)
    {
        $order_info = $params['order_info'];
        
        // 支付获取积分
        $this->payGetPoints($order_info);

        // 支付获取成长值
        $this->payGetGrowth($order_info);

        // 发送消息通知给卖家
        $this->sendMessageToMerchant($order_info);

        // 打印订单小票
        $this->printOrder($order_info);
    }

    /**
     * 发送消息通知给卖家
     * 
     * @param array $order_info 订单信息
     * @return void
     */
    public function sendMessageToMerchant($order_info)
    {
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


    /**
     * 支付获取积分
     * 
     * 订单支付成功后，根据系统配置给予用户积分奖励（使用消息队列异步处理）
     * 
     * 说明：
     * - 配置检查在 Listener 层完成，避免不必要的任务入队
     * - 使用消息队列异步处理，提高响应速度
     * 
     * @param array $order_info 订单信息
     * @return void
     */
    public function payGetPoints($order_info)
    {

        // 检查是否开启支付获取积分功能（在入队前检查，避免不必要的操作）
        $points_pay_enabled = sysConfig('points:points_pay_enabled');
        if ($points_pay_enabled != 1) {
            return; // 未开启，不入队
        }

        // 调用服务类处理积分增加逻辑（已改为消息队列异步处理，保留代码方便后期切换）
        // (new DeshangUserPointsService())->addPointsForOrderPay($order_info);

        // 使用消息队列异步处理
        (new QueueProducer())->enqueue([
            [
                'type' => 'OrderPayUserPointsQueue',
                'data' => [
                    'order_info' => $order_info,
                ],
                'options' => [
                    'biz_key' => 'OrderPayUserPointsQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 2,
                ],
            ],
        ]);
    }


    /**
     * 支付获取成长值
     * 
     * 订单支付成功后，根据系统配置给予用户成长值奖励（使用消息队列异步处理）
     * 
     * 说明：
     * - 配置检查在 Listener 层完成，避免不必要的任务入队
     * - 使用消息队列异步处理，提高响应速度
     * 
     * @param array $order_info 订单信息
     * @return void
     */
    public function payGetGrowth($order_info)
    {
        // 检查是否开启支付获取成长值功能（在入队前检查，避免不必要的操作）
        $growth_pay_enabled = sysConfig('growth:growth_pay_enabled');
        if ($growth_pay_enabled != 1) {
            return; // 未开启，不入队
        }

        // 调用服务类处理成长值增加逻辑（已改为消息队列异步处理，保留代码方便后期切换）
        // (new DeshangUserGrowthService())->addGrowthForOrderPay($order_info);

        // 使用消息队列异步处理
        (new QueueProducer())->enqueue([
            [
                'type' => 'OrderPayUserGrowthQueue',
                'data' => [
                    'order_info' => $order_info,
                ],
                'options' => [
                    'biz_key' => 'OrderPayUserGrowthQueue_' . $order_info['id'],
                    'queue_group' => SysTaskQueueEnum::GROUP_ORDER,
                    'priority' => 2,
                ],
            ],
        ]);
    }


    /**
     * 打印订单小票
     * 
     * @param array $order_info 订单信息
     * @return bool
     */
    public function printOrder($order_info)
    {

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
