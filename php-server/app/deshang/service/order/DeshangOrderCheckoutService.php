<?php

namespace app\deshang\service\order;

use app\deshang\service\BaseDeshangService;
use app\common\dao\user\UserAddressDao;
use app\common\dao\user\UserDao;
use app\common\dao\cart\TblCartDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\merchant\MerchantDao;
use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderAddressDao;
use app\common\dao\order\TblOrderGoodsDao;
use app\common\dao\order\TblOrderLogDao;
use app\common\dao\order\TblOrderMergeDao;
use app\common\dao\order\TblOrderDeliveryDao;
use app\common\dao\technician\TechnicianDao;

use app\deshang\exceptions\CommonException;
use app\common\enum\order\TblOrderEnum;
use app\common\enum\order\TblOrderMergeEnum;
use app\common\enum\goods\TblGoodsEnum;
use app\common\enum\order\TblOrderDeliveryEnum;
use app\common\enum\technician\TechnicianEnum;

use app\deshang\service\distributor\DeshangDistributorOrderService;
use app\deshang\service\rider\DeshangRiderFeeRuleService;
use app\deshang\service\store\DeshangTblStoreCouponService;
use app\deshang\service\order\DeshangMallExpressService;

// 下单服务
class DeshangOrderCheckoutService  extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }

    // 获取订单信息 用于显示以及创建订单   单独平台的促销活动可以单独获取
    public function checkTblOrderCheckout(array $data, array $cartItems)
    {


        if ($data['address_id'] > 0) {
            // 获取用户指定地址
            $address = (new UserAddressDao())->getAddressInfo([['user_id', '=', $data['user_id']], ['id', '=', $data['address_id']]]);
        } else {
            // 获取用户默认地址
            $address = (new UserAddressDao())->getAddressInfo([['user_id', '=', $data['user_id']], ['is_default', '=', 1]]);
        }


        // 获取用户信息
        $user = (new UserDao())->getUserInfoById($data['user_id'], '*');
        unset($user['password']);
        unset($user['pay_password']);



        //商品总件数
        $total_goods_num = 0;

        // 按 store_id 归类购物车商品
        $groupedItems = [];
        foreach ($cartItems as $item) {
            $total_goods_num += $item['quantity'];
            $store_id = $item['store_id'];
            if (!isset($groupedItems[$store_id])) {
                $groupedItems[$store_id] = [
                    'store_info' => (new TblStoreDao())->getStoreInfoById($store_id,'id,platform,store_logo,store_name,store_business_status,service_fee_rate,contact_name,contact_phone,merchant_id,address,store_longitude,store_latitude,store_introduction,avg_describe_score,avg_logistics_score,avg_service_score,sales_num,collect_num,is_enabled,apply_status,is_recommend,area_info,area_id,category_id'), // 查询店铺信息
                    'goods_list' => [],
                    // 原价  购物车SKU 的价格之和
                    'original_amount' => 0,
                    // 商品活动价之和，对应购物车 promotion_price (促销的一些活动价格)
                    'goods_amount' => 0,
                    // 运费
                    'shipping_amount' => 0,
                    // 优惠金额(店铺的优惠活动，不含单个商品的促销活动减免,单个活动减免在购物车中体现)
                    'discount_amount' => 0,
                    // 订单总价  用户最终需要支付的总金额
                    'order_amount' => 0,
                    // 实际支付   用户实际支付的金额，通常与订单总金额一致
                    'pay_amount' => 0,
                    // 店铺优惠券信息
                    'store_coupon_info' => null,
                    // 当前店铺可用的优惠券列表
                    'available_store_coupons' => [],

                ];
            }
            // item 为购物车数据
            $groupedItems[$store_id]['goods_list'][] = $item;
        }


        $total_original_amount = 0;
        $total_goods_amount = 0;
        $total_shipping_amount = 0;
        $total_discount_amount = 0;
        $total_order_amount = 0;
        $total_pay_amount = 0;


        // 已选店铺优惠券
        $store_coupon_ids = $data['store_coupon_ids'];

        // 计算订单总价
        foreach ($groupedItems as $store_id => $store) {

            //  当前店铺 下单原价  sku_price * quantity
            $original_amount = 0;
            //  当前店铺 下单活动价  promotion_price * quantity
            $goods_amount = 0;
            //  当前店铺 下单运费
            $shipping_amount = 0;
            //  当前店铺 下单优惠金额
            $discount_amount = 0;
            //  当前店铺  商品活动价格之和  - 运费 - 优惠
            $order_amount = 0;
            //  当前店铺 下单实际支付金额(用户支付金额)
            $pay_amount = 0;

            // 获取交付信息
            $order_delivery_info = array();
            // 计算 shipping_amount 
            switch ($data['platform']) {
                case 'food':
                    // 外卖   骑手配送

                    // 插入表的数据
                    $order_delivery_info['insert_data'] = (new DeshangRiderFeeRuleService())->calculateRiderFee($store['store_info'], $address, 0);
                    $order_delivery_info['insert_data']['delivery_method'] = TblOrderEnum::DELIVERY_RIDER;
                    // 待店铺确认
                    $order_delivery_info['insert_data']['delivery_status'] = TblOrderDeliveryEnum::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION;
                    $shipping_amount = $order_delivery_info['insert_data']['rider_total_fee'];

                    // 外卖 堂食

                    break;
                case 'mall':
                    // 商城 运费
                    $mallExpressService = new DeshangMallExpressService();
                    $expressResult = $mallExpressService->calculateMallExpressFee(
                        $store['store_info'], 
                        $store['goods_list'], 
                        $address
                    );
                    $shipping_amount = $expressResult['shipping_amount'];
                    
                    // 保存运费计算详情到订单交付信息中
                    $order_delivery_info['mall_express'] = $expressResult;
                    break;
                case 'kms':
                    // 视频教育
                    $shipping_amount = 0;
                    break;
                case 'house':
                    // 上门服务
                    // 插入表的数据
                    $order_delivery_info['insert_data']['delivery_method'] = TblOrderEnum::DELIVERY_TECHNICIAN;
                    

                    // 如果选择了师傅
                    $technician_id = $data['technician_id'] ?? 0;
                    
                    if ($technician_id > 0) {
                        // 获取师傅信息
                        $technician_info = (new TechnicianDao())->getTechnicianInfo([['id', '=', $technician_id]]);
                        if (empty($technician_info)) {
                            throw new CommonException('师傅ID错误');
                        }
                        if ($technician_info['technician_status'] != TechnicianEnum::TECHNICIAN_STATUS_AVAILABLE) {
                            throw new CommonException('师傅不在线');
                        }
                        if ($technician_info['is_enabled'] != 1) {
                            throw new CommonException('师傅已禁用');
                        }
                        if ($technician_info['apply_status'] != TechnicianEnum::APPLY_STATUS_APPROVED) {
                            throw new CommonException('师傅审核未通过');    
                        }
                        $order_delivery_info['house']['technician_info'] = $technician_info;

                        // 插入数据
                        $order_delivery_info['insert_data']['technician_id'] = $technician_info['id'];
                        // 待师傅确认
                        $order_delivery_info['insert_data']['delivery_status'] = TblOrderDeliveryEnum::DELIVERY_STATUS_COURIER_ACCEPTANCE;

                    }else{
                        // 没有选择师傅
                        $order_delivery_info['insert_data']['technician_id'] = 0;
                        // 待店铺确认
                        $order_delivery_info['insert_data']['delivery_status'] = TblOrderDeliveryEnum::DELIVERY_STATUS_PENDING_STORE_CONFIRMATION;
                    }



                    $shipping_amount = 0;
                    break;
                default:
                    $shipping_amount = 0;
                    break;
            }






            // 计算商品总额
            foreach ($store['goods_list'] as $key => $item) {
                // 当前店铺下 商品原价之和 order表的 original_amount
                $original_amount += $item['goodsSku']['sku_price'] * $item['quantity'];
                // 当前店铺下 独立商品活动价之和[促销价、价格阶梯、会员价] order表的 goods_amount
                $goods_amount += $item['promotion_price'] * $item['quantity'];
            }

            // 店铺下的可用店铺优惠券
            $available_store_coupons = (new DeshangTblStoreCouponService())->getAvailableStoreCoupons($store['store_info']['id'], $data['user_id'], $goods_amount);

            // 处理店铺优惠券
            $store_discount_amount = 0;
            if (!empty($store_coupon_ids) && isset($store_coupon_ids[$store['store_info']['id']])) {
                // 已选店铺优惠券
                $selected_store_coupon_id = $store_coupon_ids[$store['store_info']['id']];

                // 在可用优惠券中查找已选优惠券
                foreach ($available_store_coupons as $coupon) {
                    if ($coupon['id'] == $selected_store_coupon_id) {
                        // 计算优惠金额
                        switch ($coupon['storeCoupon']['coupon_type']) {
                            case 1: // 满减券
                                $store_discount_amount = min($coupon['storeCoupon']['discount_value'], $goods_amount);
                                break;
                            case 2: // 折扣券
                                $discount_rate = $coupon['storeCoupon']['discount_value'] / 10;
                                $store_discount_amount = ds_commerce_money($goods_amount * (1 - $discount_rate), 2);
                                break;
                            case 3: // 直减券
                                $store_discount_amount = min($coupon['storeCoupon']['discount_value'], $goods_amount);
                                break;
                        }

                        $groupedItems[$store_id]['store_coupon_info'] = $coupon;
                        $groupedItems[$store_id]['store_discount'][] = [
                            'discount_type' => $coupon['storeCoupon']['coupon_type'],
                            'discount_id' => $coupon['id'],
                            'discount_name' => $coupon['storeCoupon']['coupon_name'],
                            'discount_amount' => $store_discount_amount,
                        ];
                        break;
                    }
                }
            }

            // 计算店铺优惠券的优惠分配 更新 pay_price 和 discount_price
            $groupedItems[$store_id]['goods_list'] = $this->calculateStoreCouponDiscount(
                $store['goods_list'],
                $store_discount_amount
            );




            // $item['pay_price'] 是计算后的价格，实际支付价格  
            foreach ($groupedItems[$store_id]['goods_list'] as $key => $item) {
                // 计算每个商品的优惠金额
                $groupedItems[$store_id]['goods_list'][$key]['discount_price'] = $item['promotion_price'] - $item['pay_price'];
            }



            // 优惠 =店铺优惠 + 平台优惠(可拓展)
            $discount_amount = $store_discount_amount;

            // 商品活动价格之和  - 运费 - 优惠
            $order_amount = $goods_amount + $shipping_amount - $discount_amount;
            // 实际支付金额  与 订单价一致
            $pay_amount = $order_amount;

            // echo $original_amount;exit;


            // 单个店铺的 总价  字段与order order_goods表一致
            $groupedItems[$store_id]['original_amount'] = $original_amount;
            $groupedItems[$store_id]['goods_amount'] = $goods_amount;
            $groupedItems[$store_id]['shipping_amount'] = $shipping_amount;
            $groupedItems[$store_id]['discount_amount'] = $discount_amount;
            $groupedItems[$store_id]['order_amount'] = $order_amount;
            $groupedItems[$store_id]['pay_amount'] = $pay_amount;
            $groupedItems[$store_id]['order_delivery_info'] = $order_delivery_info;
            $groupedItems[$store_id]['available_store_coupons'] = $available_store_coupons;

            // 计算总价
            $total_original_amount += $original_amount;
            $total_goods_amount += $goods_amount;
            $total_shipping_amount += $shipping_amount;
            $total_discount_amount += $discount_amount;
            $total_order_amount += $order_amount;
            $total_pay_amount += $pay_amount;
        }







        //订单相关信息

        $result = array(
            'store_list' => $groupedItems,
            'address' => $address,
            'user' => $user,
            'total_goods_num' => $total_goods_num,
            'total_original_amount' => ds_commerce_money($total_original_amount, 2),
            'total_goods_amount' => ds_commerce_money($total_goods_amount, 2),
            'total_shipping_amount' => ds_commerce_money($total_shipping_amount, 2),
            'total_discount_amount' => ds_commerce_money($total_discount_amount, 2),
            'total_order_amount' => ds_commerce_money($total_order_amount, 2),
            'total_pay_amount' => ds_commerce_money($total_pay_amount, 2),

        );



        return $result;
    }



    // 生成订单  [此处的订单数据 都是与展示的数据一致 都是checkTblOrderCheckout生成的 直接插入数据库即可]
    // $data 中 包含 邀请人ID
    public function generateTblOrder($order, $data)
    {
        // 推荐购买人ID(非用户邀请人)
        $referrer_id = $data['referrer_id'];
        $referrer_info = array();
        if ($referrer_id > 0) {
            $referrer_info = (new UserDao())->getUserInfoById($referrer_id);
        }




        // halt($order);
        // checkTblOrderCheckout 返回的数据

        // 多个店铺合并支付的 总价   ， 后期单独加表 处理
        $total_pay_amount = $order['total_pay_amount'];
        $total_original_amount = $order['total_original_amount'];


        // 订单ID [单个店铺支付] 
        $order_id = 0;
        // 订单合并ID [多个店铺合并支付]
        $order_merge_id = 0;
        // 收款商户ID
        $pay_merchant_id = 0;


        // 用户可以单店支付，以及多店选择一起支付，但是多店收款方只能是 平台收款，商户无法单独收款。 商户下的多个店铺，一起付款也为平台收款。
        // PS:如果需要支持多商户多店铺单独收款, 那么合并支付功能 就注释掉，下单后，直接跳转支付页即可。
        if (count($order['store_list']) > 1) {
            // 插入 订单合并支付批次表
            $order_merge_data = array(
                'user_id' => $order['user']['id'],
                'out_trade_no' => null, // 在支付的时候 生成
                'trade_no' => null, // 在回调成功后生成
                'total_amount' => $total_pay_amount,
                'status' => TblOrderMergeEnum::ORDER_MERGE_STATUS_PENDING, // 0 未付款 1 已付款
                'pay_channel' => '', // 支付渠道
                'pay_scene' => '', // 支付场景
            );

            // 多店合并支付，收款方只能为平台  [可能还会出现 一种情况 多个店铺都属于一个商户的，这种情况 收款方也为 平台收款]
            $pay_merchant_id = 0;

            $order_merge_id = (new TblOrderMergeDao())->createOrderMerge($order_merge_data);
        }




        $store_list = $order['store_list'];
        $address = $order['address'];
        $user = $order['user'];




        foreach ($store_list as $store) {

            // 每个店铺的订单数据

            // 单店支付(非合并支付)
            if ($order_merge_id == 0) {
                // 判断当前店铺的商户， 是否有单独收款的功能

                $condition = [];
                $condition[] = ['id', '=', $store['store_info']['merchant_id']];
                $condition[] = ['is_allow_payment', '=', 1];
                $condition[] = ['is_enabled', '=', 1];
                $merchant = (new MerchantDao())->getMerchantInfo($condition);


                // 如果当前店铺的商户，有单独收款的功能，那么就使用当前商户的ID
                if (!empty($merchant)) {
                    $pay_merchant_id = $merchant['id'];
                }
            }


            $order_data = array(
                'platform' => $store['store_info']['platform'],
                'order_merge_id' => $order_merge_id,
                'pay_merchant_id' => $pay_merchant_id,
                'pay_channel' => '',
                'pay_scene' => '',
                'order_sn' => generateOrderNo($store['store_info']['platform'], $user['id']),
                // 默认未付款
                'order_status' => TblOrderEnum::ORDER_STATUS_PENDING,
                // 下单推荐人用户ID(非用户注册邀请人)
                'order_referrer_id' => $referrer_info['id'] ?? 0,
                // 用于支付 外部订单号，申请支付重新生成
                'out_trade_no' => null,
                // 支付成功后 返回的订单号
                'trade_no' => null,
                'merchant_id' => $store['store_info']['merchant_id'],
                'store_id' => $store['store_info']['id'],
                'user_id' => $user['id'],
                'delivery_method' => $store['order_delivery_info']['insert_data']['delivery_method'] ?? '',
                'original_amount' => $store['original_amount'],
                'goods_amount' => $store['goods_amount'],
                'shipping_amount' => $store['shipping_amount'],
                'discount_amount' => $store['discount_amount'],
                'order_amount' => $store['order_amount'],
                'pay_amount' => $store['pay_amount'],
                'service_amount' => bcmul($store['pay_amount'], $store['store_info']['service_fee_rate'] / 100, 2),
                'invoice_info' => '',
                'refund_status' => 0,
                'refund_amount' => 0.00,

                'user_remark' => $data['user_remark'][$store['store_info']['id']] ?? '',
                'store_remark' => '',
                'add_time' => TIMESTAMP, // 订单生成时间
                'payment_time' => 0,
                'delivery_time' => 0,
                'shipping_time' => 0,
                'finnshed_time' => 0,
                'close_time' => 0,
            );

            // 生成订单
            $order_id = (new TblOrderDao())->createOrder($order_data);

            // [骑手配送]如果有交付信息，插入交付信息  order_delivery_info 且 交付方式为骑手配送
            if (!empty($store['order_delivery_info']['insert_data']) && $store['order_delivery_info']['insert_data']['delivery_method'] == TblOrderEnum::DELIVERY_RIDER) {
                $store['order_delivery_info']['insert_data']['order_id'] = $order_id;
                (new TblOrderDeliveryDao())->createOrderDelivery($store['order_delivery_info']['insert_data']);
            }

            // [师傅服务]如果有交付信息，插入交付信息  order_delivery_info 且 交付方式为师傅服务
            if (!empty($store['order_delivery_info']['insert_data']) && $store['order_delivery_info']['insert_data']['delivery_method'] == TblOrderEnum::DELIVERY_TECHNICIAN) {
                $store['order_delivery_info']['insert_data']['order_id'] = $order_id;
                // 师傅预约时间
                $store['order_delivery_info']['insert_data']['technician_appt_time'] = $data['appointment_time'] ?? 0;
                (new TblOrderDeliveryDao())->createOrderDelivery($store['order_delivery_info']['insert_data']);
            }


            // 添加订单收发货地址
            $address_data = array(
                'order_id' => $order_id,
                'reciver_name' => $address['real_name'],
                'reciver_mobile' => $address['mob_phone'],
                'reciver_address' => $address['area_info'] . $address['address_detail'],
                'reciver_longitude' => $address['longitude'] ?? '',
                'reciver_latitude' => $address['latitude'] ?? '',
                'shipper_name' => $store['store_info']['store_name'] ?? '',
                'shipper_mobile' => $store['store_info']['contact_phone'] ?? '',
                'shipper_address' => $store['store_info']['address'] ?? '',
                'shipper_longitude' => $store['store_info']['store_longitude'] ?? '',
                'shipper_latitude' => $store['store_info']['store_latitude'] ?? '',
            );
            (new TblOrderAddressDao())->createOrderAddress($address_data);




            // 生成订单商品
            $order_goods_data = array();
            foreach ($store['goods_list'] as $item) {

                // 商品状态 为下架 或者sys_status不为1的时候，
                if ($item['goods']['goods_status'] != TblGoodsEnum::STATUS_SHELVED) {
                    throw new CommonException('商品状态为下架');
                }
                if ($item['goods']['sys_status'] != TblGoodsEnum::SYS_STATUS_NORMAL) {
                    throw new CommonException('商品状态为系统审核未通过');
                }
                // 是否有足够库存
                if ($item['goodsSku']['sku_stock'] < $item['quantity']) {
                    throw new CommonException('商品库存不足');
                }


                $order_goods_data[] = array(
                    'order_id' => $order_id,
                    'goods_id' => $item['goods_id'],
                    'goods_name' => $item['goods']['goods_name'],
                    'sku_id' => $item['goodsSku']['id'],
                    'sku_name' => $item['goodsSku']['sku_name'] ?? '默认规格',
                    'promotion_platform' => $item['promotion_platform'],
                    'promotion_type' => $item['promotion_type'],
                    'promotion_related_id' => $item['promotion_related_id'],
                    'promotion_price' => $item['promotion_price'],
                    'sku_price' => $item['goodsSku']['sku_price'],
                    'pay_price' => $item['pay_price'],
                    'goods_num' => $item['quantity'],
                    'goods_image' => empty($item['goodsSku']['sku_image']) ? $item['goods']['cover_image'] : $item['goodsSku']['sku_image'],
                    'store_id' => $item['store_id'],
                    'user_id' => $user['id'],
                    'commis_rate' => '',
                );
            }

            // 批量创建订单商品
            (new TblOrderGoodsDao())->createOrderGoodsAll($order_goods_data);

            // 生成分销订单

            // 订单商品列表(因是批量插入，所以需要单独获取)
            $order_goods_list = (new TblOrderGoodsDao())->getOrderGoodsList([['order_id', '=', $order_id]]);
            foreach ($order_goods_list as $order_goods) {
                (new DeshangDistributorOrderService())->generateDistributorOrder($order_data, $order_goods, $user, $referrer_info);
            }

            // 如果使用了优惠券，则记录优惠券使用信息
            if (!empty($store['store_coupon_info'])) {
                // 修改优惠券使用状态
                (new DeshangTblStoreCouponService())->isUsedStoreCoupon($store['store_coupon_info']);
            }


            // 生成订单日志
            $order_log_data = array(
                'order_id' => $order_id,
                'order_status' => TblOrderEnum::ORDER_STATUS_PENDING,
                'message' => '下单',
                'create_role' => 'user',
                'create_by' => $user['username'],
            );
            (new TblOrderLogDao())->createOrderLog($order_log_data);

            // 触发订单生成事件
            event('OrderGenerateListener', [
                'order_goods_list' => $order_goods_list,
                'store_info' => $store['store_info'],
                'user_info' => $user,
            ]);
        }


        // 是否为合并支付
        $result = array(
            // 类型 与 trader_pay_log 表的 source_type 一致
            'source_type' => $order_merge_id > 0 ? 'order_merge' : 'order',
            // 来源ID 与 trader_pay_log 表的 source_id 一致
            'source_id' => $order_merge_id > 0 ? $order_merge_id : $order_id,
            // 收款商户ID
            'pay_merchant_id' => $pay_merchant_id,
            // 订单ID
            'pay_amount' => $total_pay_amount,
            // pay_info
            'pay_info' => $order_merge_id > 0 ? '订单合并支付' : '单个订单支付',
        );



        return $result;
    }



    /**
     * 计算店铺优惠券的优惠分配
     * 
     * @param array $goods_list 商品列表
     * @param float $discount_amount 优惠总金额
     * @return array 更新后的商品列表（带有pay_price）
     */
    private function calculateStoreCouponDiscount($goods_list, $discount_amount)
    {
        // 如果没有优惠，所有商品价格不变
        if ($discount_amount <= 0) {
            foreach ($goods_list as $key => $item) {
                $goods_list[$key]['pay_price'] = $item['promotion_price'];
            }
            return $goods_list;
        }

        // 计算商品总金额
        $goods_amount = 0;
        foreach ($goods_list as $item) {
            $goods_amount += $item['promotion_price'] * $item['quantity'];
        }

        // 已分配的优惠金额
        $applied_discount = 0;
        $goods_count = count($goods_list);

        // 按商品金额比例分配优惠
        foreach ($goods_list as $key => $item) {
            $item_total = $item['promotion_price'] * $item['quantity'];

            // 最后一个商品用剩余优惠金额，避免小数点误差
            if ($key == $goods_count - 1) {
                $item_discount = $discount_amount - $applied_discount;
            } else {
                // 按比例计算
                $item_discount = ds_commerce_money(($item_total / $goods_amount) * $discount_amount, 2);
                $applied_discount += $item_discount;
            }

            // 计算每个商品单位的优惠
            $item_discount_per_unit = ds_commerce_money($item_discount / $item['quantity'], 2);

            $goods_list[$key]['pay_price'] = ds_commerce_money($item['promotion_price'] - $item_discount_per_unit, 2);
        }

        return $goods_list;
    }
}
