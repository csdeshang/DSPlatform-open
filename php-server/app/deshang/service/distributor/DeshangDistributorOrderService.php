<?php


namespace app\deshang\service\distributor;

use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\StateException;
use app\deshang\service\BaseDeshangService;

use app\common\dao\user\UserDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\distributor\DistributorLevelDao;
use app\common\dao\distributor\DistributorGoodsDao;
use app\common\dao\distributor\DistributorOrderDao;
use app\common\dao\order\TblOrderFinanceDao;


use app\common\enum\distributor\DistributorGoodsEnum;
use app\common\enum\distributor\DistributorOrderEnum;
use app\common\enum\merchant\MerchantBalanceEnum;

use app\deshang\service\merchant\DeshangMerchantBalanceService;
use think\facade\Db;

class DeshangDistributorOrderService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }



    // 生成分销订单 (如果下单有推荐人则优先使用推荐人， 没有推荐人则使用用户注册邀请人)
    // 为了避免改价逃避推荐佣金， 订单改价 也是按照 下单的商品价格 计算，默认规则也有固定佣金非比例佣金
    // 如需要按照改价后的价格计算，则需要单独写方法
    public function generateDistributorOrder(array $order, array $order_goods, array $user, array $referrer_info)
    {


        // 获取订单商品是否开启分销
        $distributor_is_enabled = sysConfig('distributor:distributor_is_enabled');
        if ($distributor_is_enabled != 1) {
            return;
        }

        // 商品是否设置分销
        $goods_info = (new TblGoodsDao())->getGoodsInfo([['id', '=', $order_goods['goods_id']]]);
        if ($goods_info['is_distributor_goods'] != 1) {
            return;
        }

        // 分销佣金类型
        $distributor_goods_type = $goods_info['distributor_goods_type'];




        // 1 处理自购佣金
        // 创建自购分销商订单
        $this->createDistributorOrder($order, $order_goods, $user, $distributor_goods_type, DistributorOrderEnum::COMMISSION_TYPE_SELF);



        // 2 处理一级分销佣金
        // 获取推荐人  优先获取下单推荐人， 没有再使用用户注册邀请人
        $parent1_user_info = array();
        if (!empty($referrer_info)) {
            $parent1_user_info = $referrer_info;
        } else {
            // 当前购买用户是否有邀请人
            if ($user['inviter_id'] > 0) {
                $parent1_user_info = (new UserDao())->getUserInfoById($user['inviter_id']);
            }
        }

        // 没有推荐人，则不享受一级分销佣金
        if (empty($parent1_user_info)) {
            return;
        }

        // 推荐人和购买者是同一个人，则不享受一级分销佣金
        if ($parent1_user_info['id'] == $order_goods['user_id']) {
            return;
        }


        // 创建一级分销商订单
        $this->createDistributorOrder($order, $order_goods, $parent1_user_info, $distributor_goods_type, DistributorOrderEnum::COMMISSION_TYPE_PARENT1);



        // 3 处理二级分销佣金
        $parent2_user_id = $parent1_user_info['inviter_id'];
        if ($parent2_user_id == 0) {
            return;
        }

        // 获取推荐人的上级
        $parent2_user_info = (new UserDao())->getUserInfoById($parent2_user_id);
        if (empty($parent2_user_info)) {
            return;
        }
        // 创建二级分销商订单
        $this->createDistributorOrder($order, $order_goods, $parent2_user_info, $distributor_goods_type, DistributorOrderEnum::COMMISSION_TYPE_PARENT2);
    }



    private function createDistributorOrder($order, $order_goods, $user, $distributor_goods_type, $commission_type)
    {
        // 购买者是否是分销商 获取自购佣金
        if ($user['is_distributor'] == 1 && $user['distributor_status'] == 1) {

            $commission_amount = 0;

            $commission_remark = '';

            // 获取当前用户的分销等级 默认1级
            $user_distributor_level_id = $user['distributor_level_id'] ?? 1;

            switch ($distributor_goods_type) {

                case DistributorGoodsEnum::TYPE_DEFAULT:
                    // 默认佣金
                    // 获取自购佣金比例或金额
                    $level_info = (new DistributorLevelDao())->getDistributorLevelInfo([['id', '=', $user_distributor_level_id]]);
                    if (!empty($level_info)) {

                        // 根据佣金类型 获取自购佣金比例或金额
                        $commission_ratio = 0;
                        switch ($commission_type) {
                            case DistributorOrderEnum::COMMISSION_TYPE_SELF:
                                $commission_ratio = $level_info['base_self_ratio'];
                                break;
                            case DistributorOrderEnum::COMMISSION_TYPE_PARENT1:
                                $commission_ratio = $level_info['base_parent1_ratio'];
                                break;
                            case DistributorOrderEnum::COMMISSION_TYPE_PARENT2:
                                $commission_ratio = $level_info['base_parent2_ratio'];
                                break;
                            default:
                                throw new CommonException('分销佣金类型错误');
                        }



                        $commission_amount = bcmul($order_goods['pay_price'] * $commission_ratio / 100, 2);
                        $commission_remark = '支付金额：' . $order_goods['pay_price'] . ' 默认等级设置佣金比例：' . $commission_ratio . '%' . ' 佣金金额：' . $commission_amount;
                    }
                    break;
                case DistributorGoodsEnum::TYPE_RATIO || DistributorGoodsEnum::TYPE_FIXED:
                    // 单独设置比例或金额
                    $condition = [];
                    $condition[] = ['goods_id', '=', $order_goods['goods_id']];
                    $condition[] = ['sku_id', '=', $order_goods['sku_id']];
                    $condition[] = ['distributor_level_id', '=', $user_distributor_level_id];
                    $distributor_goods_info = (new DistributorGoodsDao())->getDistributorGoodsInfo($condition);


                    if (!empty($distributor_goods_info)) {
                        if ($distributor_goods_type == DistributorGoodsEnum::TYPE_RATIO) {

                            // 根据佣金类型 获取自购佣金比例
                            $commission_ratio = 0;
                            switch ($commission_type) {
                                case DistributorOrderEnum::COMMISSION_TYPE_SELF:
                                    $commission_ratio = $distributor_goods_info['goods_self_ratio'];
                                    break;
                                case DistributorOrderEnum::COMMISSION_TYPE_PARENT1:
                                    $commission_ratio = $distributor_goods_info['goods_parent1_ratio'];
                                    break;
                                case DistributorOrderEnum::COMMISSION_TYPE_PARENT2:
                                    $commission_ratio = $distributor_goods_info['goods_parent2_ratio'];
                                    break;
                                default:
                                    throw new CommonException('分销佣金类型错误');
                            }

                            $commission_amount = bcmul($order_goods['pay_price'] * $commission_ratio / 100, 2);
                            $commission_remark = '支付金额：' . $order_goods['pay_price'] . ' 单独设置佣金比例：' . $commission_ratio . '%' . ' 佣金金额：' . $commission_amount;
                        } else {

                            $commission_amount = bcmul($distributor_goods_info['goods_self_amount'], 2);

                            // 根据佣金类型 获取自购佣金金额
                            $commission_amount = 0;
                            switch ($commission_type) {
                                case DistributorOrderEnum::COMMISSION_TYPE_SELF:
                                    $commission_amount = $distributor_goods_info['goods_self_amount'];
                                    break;
                                case DistributorOrderEnum::COMMISSION_TYPE_PARENT1:
                                    $commission_amount = $distributor_goods_info['goods_parent1_amount'];
                                    break;
                                case DistributorOrderEnum::COMMISSION_TYPE_PARENT2:
                                    $commission_amount = $distributor_goods_info['goods_parent2_amount'];
                                    break;
                                default:
                                    throw new CommonException('分销佣金类型错误');
                            }

                            $commission_remark = '支付金额：' . $order_goods['pay_price'] . ' 单独设置佣金金额：' . $commission_amount;
                        }
                    }
                    break;
                default:
                    throw new CommonException('分销佣金类型错误');
                    break;
            }




            // 如果有佣金，则创建分销订单记录
            if ($commission_amount > 0) {
                $distributor_order_data = [
                    'order_id' => $order_goods['order_id'],
                    'order_goods_id' => $order_goods['id'],
                    'distributor_user_id' => $user['id'], // 分销商 用户ID
                    'user_id' => $order_goods['user_id'], // 购买者ID
                    'store_id' => $order_goods['store_id'],
                    'goods_id' => $order_goods['goods_id'],
                    'merchant_id' => $order['merchant_id'],
                    'pay_price' => $order_goods['pay_price'],
                    'distributor_level_id' => $user_distributor_level_id,
                    'commission_amount' => $commission_amount,
                    'commission_type' => $commission_type,
                    'commission_status' => DistributorOrderEnum::COMMISSION_STATUS_PENDING,
                    'commission_remark' => $commission_remark,
                ];
                (new DistributorOrderDao())->createDistributorOrder($distributor_order_data);
            }
        }
    }




    // 根据订单ID 取消 分销订单
    public function cancelDistributorOrderByOrderId($order_id)
    {
        if (empty($order_id)) {
            throw new CommonException('订单ID不能为空');
        }

        // 根据订单ID 取消 分销订单
        $condition = [];
        $condition[] = ['order_id', '=', $order_id];
        $condition[] = ['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED];
        (new DistributorOrderDao())->updateDistributorOrder($condition, ['commission_status' => DistributorOrderEnum::COMMISSION_STATUS_INVALID]);
    }


    // 根据商品订单ID 取消 分销订单
    public function cancelDistributorOrderByGoodsOrderId($order_goods_id)
    {
        if (empty($order_goods_id)) {
            throw new CommonException('商品订单ID不能为空');
        }

        // 根据商品订单ID 取消 分销订单
        $condition = [];
        $condition[] = ['order_goods_id', '=', $order_goods_id];
        $condition[] = ['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED];
        (new DistributorOrderDao())->updateDistributorOrder($condition, ['commission_status' => DistributorOrderEnum::COMMISSION_STATUS_INVALID]);
    }


    // 订单付款之后，根据订单ID 修改分销商订单状态 （未付款状态修改为待结算）
    public function receivePayDistributorOrder(int $order_id)
    {
        if (empty($order_id)) {
            throw new CommonException('订单ID不能为空');
        }

        // 根据订单ID 修改分销商订单状态  检索是否有此订单ID记录的分销商订单
        $condition = [];
        $condition[] = ['order_id', '=', $order_id];
        $condition[] = ['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_PENDING];

        (new DistributorOrderDao())->updateDistributorOrder($condition, ['commission_status' => DistributorOrderEnum::COMMISSION_STATUS_WAIT]);

        return true;
    }

    # 获取当前订单的佣金总额
    public function getDistributorOrderCommissionAmount($order_info): float
    {
        if (empty($order_info)) {
            throw new CommonException('订单ID不能为空');
        }

        // 获取当前订单下，所有分销商订单
        $distributor_order_list = (new DistributorOrderDao())->getDistributorOrderList([['order_id', '=', $order_info['id']]]);

        if (empty($distributor_order_list)) {
            return 0;
        }

        // 遍历分销商订单，如果订单状态为待结算，则修改为已结算
        $total_commission_amount = 0;
        foreach ($distributor_order_list as $distributor_order) {
            if ($distributor_order['commission_status'] != DistributorOrderEnum::COMMISSION_STATUS_WAIT) {
                throw new StateException('分销商订单状态错误');
            }
            // 获取分销订单总佣金
            $total_commission_amount += $distributor_order['commission_amount'];
        }
        return $total_commission_amount;
    }


    // 处理退款，返还用户确认收货时 扣除的分销商佣金
    public function refundDistributorOrder(array $refund_info, int $merchant_id)
    {
        if (empty($refund_info)) {
            throw new CommonException('退款信息不能为空');
        }

        $order_goods_id = $refund_info['order_goods_id'];

        $total_commission_amount = 0;
        // order_goods_id = 0 表示 全部退款
        $condition = [];
        $condition[] = ['order_id', '=', $refund_info['order_id']];
        $condition[] = ['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED];
        if ($order_goods_id == 0) {
            $total_commission_amount = (new DistributorOrderDao())->getDistributorOrderSum($condition, 'commission_amount');
        } else {
            $condition[] = ['order_goods_id', '=', $order_goods_id];
            $total_commission_amount = (new DistributorOrderDao())->getDistributorOrderSum($condition, 'commission_amount');
        }

        // 修改分销商订单状态
        (new DistributorOrderDao())->updateDistributorOrder($condition, ['commission_status' => DistributorOrderEnum::COMMISSION_STATUS_REFUND]);

        // 给商户退还确认收货时 扣除的分销商佣金
        if ($total_commission_amount > 0) {
            $balance_data = [
                'change_mode' => MerchantBalanceEnum::MODE_INCREASE,
                'change_type' => MerchantBalanceEnum::TYPE_COMMISSION,
                'change_amount' => $total_commission_amount,
                'merchant_id' => $merchant_id,
                'store_id' => $refund_info['store_id'],
                'related_id' => $refund_info['id'],
                'change_desc' => '退款，返还佣金' . $total_commission_amount . '元' . '(订单ID：' . $refund_info['order_id'] . ')',
            ];
            (new DeshangMerchantBalanceService())->modifyMerchantBalance($balance_data);


            // 结算完成是允许在一定时间内允许退款， 出现退款需要修改 订单结算数据
            // 退还佣金 及修改 分销商订单状态
            $order_finance_data = [
                'store_amount' => Db::raw('store_amount + ' . $total_commission_amount),
                'distributor_amount' => Db::raw('distributor_amount - ' . $total_commission_amount),
                'settle_time' => time(),
            ];
            (new TblOrderFinanceDao())->updateOrderFinance(['order_id' => $refund_info['order_id']], $order_finance_data);
        }

        return true;
    }



    // 分销商结算





}
