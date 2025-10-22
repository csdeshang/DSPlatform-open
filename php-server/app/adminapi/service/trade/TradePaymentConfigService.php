<?php


namespace app\adminapi\service\trade;


use app\deshang\base\service\BaseAdminService;
use app\deshang\service\trade\DeshangTradePaymentConfigService;




class TradePaymentConfigService extends BaseAdminService
{


    // 获取系统支付配置列表  merchant_id 为0 
    public function getPaymentConfigByMerchant(){
        // 商户id 系统支付配置 为0
        $id = 0;
        $list = (new DeshangTradePaymentConfigService())->getPaymentConfigByMerchant($id);
        return $list;
    }



    // 获取单条配置
    public function getPaymentConfigInfoById($id){
        $info = (new DeshangTradePaymentConfigService())->getPaymentConfigInfoById($id);
        return $info;
    }

    // 后台只有创建自己的商户配置
    public function createPaymentConfig($data){
        $data['merchant_id'] = 0;
        $result = (new DeshangTradePaymentConfigService())->createPaymentConfig($data);
        return $result;
    }

    // 后台只有更新自己的商户配置
    public function updatePaymentConfig($data){
        $data['merchant_id'] = 0;
        $result = (new DeshangTradePaymentConfigService())->updatePaymentConfig($data);
        return $result;
    }

    public function deletePaymentConfig($id){
        $result = (new DeshangTradePaymentConfigService())->deletePaymentConfig($id);
        return $result;
    }



    
    
}