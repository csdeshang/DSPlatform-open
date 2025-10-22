<?php

namespace app\adminapi\service\stat;
use app\deshang\base\service\BaseAdminService;
use app\common\dao\merchant\MerchantDao;

use app\common\enum\merchant\MerchantEnum;



class StatMerchantService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new MerchantDao();
    }

    public function getStatMerchantOverview(){
        $result = [];

        // 总新增商户数
        $result['new_merchant']['total'] = $this->dao->getMerchantCount([]);
        // 今日新增
        $result['new_merchant']['today'] = $this->dao->getMerchantCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_merchant']['yesterday'] = $this->dao->getMerchantCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_merchant']['week'] = $this->dao->getMerchantCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_merchant']['month'] = $this->dao->getMerchantCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);        

        // 商户资产(总资产,总收入,总支出)
        $merchant_asset = $this->dao->getMerchantInfo([['id', '>', 0]],'sum(balance) as total_balance, sum(balance_in) as total_balance_in, sum(balance_out) as total_balance_out');
        $result['merchant_asset']['total_balance'] = ds_commerce_money($merchant_asset['total_balance'], 2);
        $result['merchant_asset']['total_balance_in'] = ds_commerce_money($merchant_asset['total_balance_in'], 2);
        $result['merchant_asset']['total_balance_out'] = ds_commerce_money($merchant_asset['total_balance_out'], 2);

        // 待审核
        $result['apply_status']['wait'] = $this->dao->getMerchantCount([
            ['apply_status', '=', MerchantEnum::APPLY_STATUS_WAIT]
        ]);


        return $result;
    }



}