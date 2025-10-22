<?php

namespace app\adminapi\service\stat;
use app\deshang\base\service\BaseAdminService;

use app\common\dao\distributor\DistributorOrderDao;
use app\common\dao\distributor\DistributorApplyDao;
use app\common\dao\distributor\DistributorGoodsDao;
use app\common\dao\distributor\DistributorBalanceLogDao;
use app\common\dao\user\UserDao;
use app\common\dao\goods\TblGoodsDao;

use app\common\enum\distributor\DistributorEnum;
use app\common\enum\distributor\DistributorApplyEnum;
use app\common\enum\distributor\DistributorOrderEnum;



class StatDistributorService extends BaseAdminService
{

    protected $user_dao;
    protected $distributor_order_dao;
    protected $distributor_apply_dao;
    protected $distributor_goods_dao;
    protected $distributor_balance_log_dao;

    public function __construct()
    {
        parent::__construct();
        $this->user_dao = new UserDao();
        $this->distributor_order_dao = new DistributorOrderDao();
        $this->distributor_apply_dao = new DistributorApplyDao();
        $this->distributor_goods_dao = new DistributorGoodsDao();
        $this->distributor_balance_log_dao = new DistributorBalanceLogDao();

    }

    public function getStatDistributorOverview(){
        $result = [];

        // 总新增分销商数
        $result['new_distributor']['total'] = $this->user_dao->getUserCount([
            ['is_distributor', '=', 1]
        ]);
        // 今日新增
        $result['new_distributor']['today'] = $this->user_dao->getUserCount([
            ['is_distributor', '=', 1],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_distributor']['yesterday'] = $this->user_dao->getUserCount([
            ['is_distributor', '=', 1],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_distributor']['week'] = $this->user_dao->getUserCount([
            ['is_distributor', '=', 1],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_distributor']['month'] = $this->user_dao->getUserCount([
            ['is_distributor', '=', 1],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);        

        // 商户资产(总资产,总收入,总支出)
        $condition = [];
        $condition[] = ['is_distributor', '=', 1];
        $distributor_asset = $this->user_dao->getUserInfo($condition,'sum(distributor_balance) as total_distributor_balance, sum(distributor_balance_in) as total_distributor_balance_in, sum(distributor_balance_out) as total_distributor_balance_out');
        $result['distributor_asset']['total_distributor_balance'] = $distributor_asset['total_distributor_balance'];
        $result['distributor_asset']['total_distributor_balance_in'] = $distributor_asset['total_distributor_balance_in'];
        $result['distributor_asset']['total_distributor_balance_out'] = $distributor_asset['total_distributor_balance_out'];

        // 待审核
        $result['apply_status']['wait'] = $this->distributor_apply_dao->getDistributorApplyCount([
            ['apply_status', '=', DistributorApplyEnum::APPLY_STATUS_PENDING]
        ]);

        // 分销订单总数  未付款不计算在内
        $result['distributor_order']['total_count'] = $this->distributor_order_dao->getDistributorOrderCount([['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_INVALID]]);
        // 已结算的金额 
        $result['distributor_order']['total_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED]], 'commission_amount');
        // 待结算的金额 
        $result['distributor_order']['total_wait_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_WAIT]], 'commission_amount');
        // 今日分销订单总数
        $result['distributor_order']['today_count'] = $this->distributor_order_dao->getDistributorOrderCount([['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_INVALID], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]]);
        // 今日结算的金额
        $result['distributor_order']['today_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');
        // 今日未结算的金额
        $result['distributor_order']['today_wait_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_WAIT], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');
        // 本周分销订单总数
        $result['distributor_order']['week_count'] = $this->distributor_order_dao->getDistributorOrderCount([['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_INVALID], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]]);
        // 本周结算的金额
        $result['distributor_order']['week_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');
        // 本周未结算的金额
        $result['distributor_order']['week_wait_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_WAIT], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');
        // 本月分销订单总数
        $result['distributor_order']['month_count'] = $this->distributor_order_dao->getDistributorOrderCount([['commission_status', '!=', DistributorOrderEnum::COMMISSION_STATUS_INVALID], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]]);
        // 本月结算的金额
        $result['distributor_order']['month_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_SETTLED], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');
        // 本月未结算的金额
        $result['distributor_order']['month_wait_settled_amount'] = $this->distributor_order_dao->getDistributorOrderSum([['commission_status', '=', DistributorOrderEnum::COMMISSION_STATUS_WAIT], ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))], ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]], 'commission_amount');


        // 分销商品 (根据TblGoodsModel表 检索  distributor_goods 存储的是具体佣金配置)
        $result['distributor_goods']['total_count'] = (new TblGoodsDao())->getGoodsCount([['is_distributor_goods', '=', 1]]);


        return $result;
    }



}