<?php

namespace app\adminapi\controller\tblStore\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\system\SysPrinterProviderEnum;
class TblStorePrinterValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0',
        'store_name' => 'max:100',
        'printer_name' => 'max:100',
        'printer_provider' => 'checkPrinterProvider',
        'order_id' => 'integer|gt:0',
        'print_status' => 'in:0,1',
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '打印机ID不能为空',
        'id.integer' => '打印机ID必须是整数',
        'id.gt' => '打印机ID必须大于0',
        'store_name.max' => '店铺名称不能超过100个字符',
        'printer_name.max' => '打印机名称不能超过100个字符',
        'printer_provider.checkPrinterProvider' => '打印机服务商值不正确',
        'order_id.integer' => '订单ID必须是整数',
        'order_id.gt' => '订单ID必须大于0',
        'print_status.in' => '打印状态值不正确（0:失败 1:成功）',
    ];

    // 定义场景
    protected $scene = [
        'info' => ['id'],
        'pages' => ['store_name', 'printer_name', 'printer_provider'],
        'logs' => ['store_name', 'order_id', 'print_status'],
    ];


    
    public function checkPrinterProvider($value, $rule, $data){
        if (empty($value)) {
            return true; // 空值允许
        }
        return array_key_exists($value, SysPrinterProviderEnum::getPrinterProviderDict());
    }
} 