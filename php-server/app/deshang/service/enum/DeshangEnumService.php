<?php

namespace app\deshang\service\enum;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;

/**
 * 枚举数据服务类
 * 
 * 负责管理和提供系统中的枚举数据，使用三级数组组织不同模块的枚举
 * 统一使用 module.table.field 格式的枚举类型标识
 */
class DeshangEnumService extends BaseDeshangService
{
    /**
     * 枚举类映射 - 使用三级数组组织
     * 
     * 格式: 
     * [
     *   '模块名' => [
     *     '表名' => [
     *       '字段名' => ['完整类名', '静态方法名']
     *     ]
     *   ]
     * ]
     * 
     * @var array
     */
    protected $enumClassMap = [
        // 系统模块
        'default' => [

            // 余额表
            'user_balance_log' => [
                'change_type' => ['app\common\enum\user\UserBalanceEnum', 'getChangeTypeDict'],
                'change_mode' => ['app\common\enum\user\UserBalanceEnum', 'getChangeModeDict'],
            ],
            // 用户成长值记录表
            'user_growth_log' => [
                'change_type' => ['app\common\enum\user\UserGrowthEnum', 'getChangeTypeDict'],
                'change_mode' => ['app\common\enum\user\UserGrowthEnum', 'getChangeModeDict'],
            ],
            // 用户积分记录表
            'user_points_log' => [
                'change_type' => ['app\common\enum\user\UserPointsEnum', 'getChangeTypeDict'],
                'change_mode' => ['app\common\enum\user\UserPointsEnum', 'getChangeModeDict'],
            ],
            // 用户提现账户表
            'user_withdrawal_account' => [
                'account_type' => ['app\common\enum\user\UserWithdrawalEnum', 'getAccountTypeDict'],
            ],
            // 用户提现日志表
            'user_withdrawal_log' => [
                'transfer_type' => ['app\common\enum\user\UserWithdrawalEnum', 'getTransferTypeDict'],
                'status' => ['app\common\enum\user\UserWithdrawalEnum', 'getStatusDict'],
                'account_type' => ['app\common\enum\user\UserWithdrawalEnum', 'getAccountTypeDict'],
            ],
            // 用户行为日志表
            'user_behavior_log' => [
                'behavior_type' => ['app\common\enum\user\UserBehaviorEnum', 'getBehaviorTypeDict'],
                'behavior_status' => ['app\common\enum\user\UserBehaviorEnum', 'getBehaviorStatusDict'],
                'risk_level' => ['app\common\enum\user\UserBehaviorEnum', 'getRiskLevelDict'],
                'is_abnormal' => ['app\common\enum\user\UserBehaviorEnum', 'getAbnormalDict'],
            ],
            // 可编辑页面表
            'editable_page' => [
                'type' => ['app\common\enum\editable\EditablePageEnum', 'getEditablePageTypeDict'],
            ],

            //系统通知日志表
            'sys_notice_log' => [
                'notice_channel' => ['app\common\enum\system\SysNoticeEnum', 'getChannelDict'],
            ],
            //商户表
            'merchant' => [
                'apply_status' => ['app\common\enum\merchant\MerchantEnum', 'getApplyStatusDict'],
            ],
            //商户余额变动日志表
            'merchant_balance_log' => [
                'change_type' => ['app\common\enum\merchant\MerchantBalanceEnum', 'getChangeTypeDict'],
                'change_mode' => ['app\common\enum\merchant\MerchantBalanceEnum', 'getChangeModeDict'],
            ],
            //交易支付日志表
            'trade_pay_log' => [
                'source_type' => ['app\common\enum\trade\TradePayEnum', 'getSourceTypeDict'],
                'pay_status' => ['app\common\enum\trade\TradePayEnum', 'getPayStatusDict'],
            ],
            //交易退款日志表
            'trade_refund_log' => [
                'refund_status' => ['app\common\enum\trade\TradeRefundEnum', 'getRefundStatusDict'],
            ],
            //交易转账日志表
            'trade_transfer_log' => [
                'source_type' => ['app\common\enum\trade\TradeTransferEnum', 'getSourceTypeDict'],
                'transfer_status' => ['app\common\enum\trade\TradeTransferEnum', 'getTransferStatusDict'],
            ],
            //商品表
            'tbl_goods' => [
                'goods_status' => ['app\common\enum\goods\TblGoodsEnum', 'getGoodsStatusDict'],
                'sys_status' => ['app\common\enum\goods\TblGoodsEnum', 'getSysStatusDict'],
            ],
            //店铺表
            'tbl_store' => [
                'apply_status' => ['app\common\enum\store\TblStoreEnum', 'getApplyStatusDict'],
            ],
             // 优惠券表
             'tbl_store_coupon' => [
                'coupon_type' => ['app\common\enum\store\TblStoreCouponEnum', 'getStoreCouponTypeDict'],
                'claim_type' => ['app\common\enum\store\TblStoreCouponEnum', 'getStoreCouponClaimTypeDict'],
                'status' => ['app\common\enum\store\TblStoreCouponEnum', 'getStoreCouponStatusDict'],
            ],
            //店铺打印机表
            'tbl_store_printer' => [
                'printer_provider' => ['app\common\enum\system\SysPrinterProviderEnum', 'getPrinterProviderDict'],
            ],
            //订单表
            'tbl_order' => [
                'order_status' => ['app\common\enum\order\TblOrderEnum', 'getAllOrderStatusDict'],
                'delivery_method' => ['app\common\enum\order\TblOrderEnum', 'getAllOrderDeliveryDict'],
                'refund_status' => ['app\common\enum\order\TblOrderEnum', 'getOrderRefundStatusDict'],
            ],
            //订单退款表
            'tbl_order_refund' => [
                'refund_status' => ['app\common\enum\order\TblOrderRefundEnum', 'getRefundStatusDict'],
                'refund_type' => ['app\common\enum\order\TblOrderRefundEnum', 'getAllRefundTypeDict'],
                'refund_method' => ['app\common\enum\order\TblOrderRefundEnum', 'getRefundMethodDict'],
            ],


            //分销商申请表
            'distributor_apply' => [
                'apply_status' => ['app\common\enum\distributor\DistributorApplyEnum', 'getDistributorApplyStatusDict'],
            ],
            //分销订单表
            'distributor_order' => [
                'commission_type' => ['app\common\enum\distributor\DistributorOrderEnum', 'getCommissionTypeDict'],
                'commission_status' => ['app\common\enum\distributor\DistributorOrderEnum', 'getCommissionStatusDict'],
            ],
            //分销员余额变动日志表
            'distributor_balance_log' => [
                'change_type' => ['app\common\enum\distributor\DistributorBalanceEnum', 'getChangeTypeDict'],
                'change_mode' => ['app\common\enum\distributor\DistributorBalanceEnum', 'getChangeModeDict'],
            ],
            //师傅表
            'technician' => [
                'technician_status' => ['app\common\enum\technician\TechnicianEnum', 'getTechnicianStatusDict'],
                'apply_status' => ['app\common\enum\technician\TechnicianEnum', 'getApplyStatusDict'],
            ],
            //博主表
            'blogger' => [
                'verification_status' => ['app\common\enum\blogger\BloggerEnum', 'getVerificationStatusDict'],
                'verification_type' => ['app\common\enum\blogger\BloggerEnum', 'getVerificationTypeDict'],
            ],

            //课时表
            'kms_lesson' => [
                'lesson_type' => ['app\common\enum\kms\KmsLessonEnum', 'getLessonTypeDict'],
                'is_free' => ['app\common\enum\kms\KmsLessonEnum', 'getIsFreeDict'],
            ],
            //积分商品订单
            'points_goods_order' => [
                'order_status' => ['app\common\enum\pointsGoods\PointsGoodsOrderEnum', 'getOrderStatusDict'],
                'delivery_method' => ['app\common\enum\pointsGoods\PointsGoodsOrderEnum', 'getDeliveryMethodDict'],
            ],
            //积分商品订单日志
            'points_goods_order_log' => [
                'order_status' => ['app\common\enum\pointsGoods\PointsGoodsOrderEnum', 'getOrderStatusDict'],
            ],
            //系统错误日志表
            'sys_error_logs' => [
                'exception_class' => ['app\common\enum\system\SysErrorLogsEnum', 'getExceptionClassDict'],
            ]
            

            
        ],
    ];

    /**
     * 获取所有枚举数据
     * @return array
     * @throws CommonException
     */
    public function getAllEnumData(): array
    {
        $result = [];

        // 遍历所有模块、表和字段
        foreach ($this->enumClassMap as $module => $tables) {
            foreach ($tables as $table => $fields) {
                foreach ($fields as $field => $classInfo) {
                    $fullType = $module . '.' . $table . '.' . $field;
                    $result[$fullType] = $this->getSingleEnumData($fullType);
                }
            }
        }

        return $result;
    }
    
    /**
     * 获取指定枚举数据
     * 
     * 只支持完整的 module.table.field 格式
     * 
     * @param string $type 枚举类型，必须使用 module.table.field 格式
     * @return array
     * @throws CommonException
     */
    public function getEnumData(string $type): array
    {
        // 验证格式
        $parts = explode('.', $type);
        if (count($parts) !== 3) {
            throw new CommonException("无效的枚举类型格式: {$type}");
        }
        
        // 获取单个枚举数据并以原格式返回
        $data = $this->getSingleEnumData($type);
        return [$type => $data];
    }

    /**
     * 获取单个枚举数据
     * @param string $type 枚举类型 (格式: module.table.field)
     * @return array
     * @throws CommonException
     */
    public function getSingleEnumData(string $type): array
    {
        // 解析类型
        $parts = explode('.', $type);
        
        if (count($parts) !== 3) {
            throw new CommonException("无效的枚举类型格式: {$type}");
        }
        
        $module = $parts[0];
        $table = $parts[1];
        $field = $parts[2];
        
        if (
            !isset($this->enumClassMap[$module]) ||
            !isset($this->enumClassMap[$module][$table]) ||
            !isset($this->enumClassMap[$module][$table][$field])
        ) {
            throw new CommonException("未定义的枚举类型: {$type}");
        }
        
        $classInfo = $this->enumClassMap[$module][$table][$field];
        [$class, $method] = $classInfo;
        
        if (!class_exists($class)) {
            throw new CommonException("枚举类不存在: {$class}");
        }
        
        if (!method_exists($class, $method)) {
            throw new CommonException("枚举方法不存在: {$class}::{$method}");
        }
        
        $data = call_user_func([$class, $method]);
        
        if (!is_array($data)) {
            throw new CommonException("枚举数据必须是数组: {$class}::{$method}");
        }
        
        return $data;
    }

    /**
     * 注册新的枚举类型
     * 
     * @param string $module 模块名称
     * @param string $table 表名
     * @param string $field 字段名
     * @param string $class 枚举类名
     * @param string $method 枚举方法名
     * @return bool
     * @throws CommonException
     */
    public function registerEnumType(string $module, string $table, string $field, string $class, string $method): bool
    {
        if (empty($module) || empty($table) || empty($field) || empty($class) || empty($method)) {
            throw new CommonException("注册枚举类型失败: 参数不完整");
        }
        
        // 检查字段格式
        if (strpos($field, '.') !== false) {
            throw new CommonException("字段名不应包含点号(.)，请使用简单名称");
        }
        
        // 检查类和方法是否存在
        if (!class_exists($class)) {
            throw new CommonException("注册枚举类型失败: 类不存在 {$class}");
        }
        
        if (!method_exists($class, $method)) {
            throw new CommonException("注册枚举类型失败: 方法不存在 {$class}::{$method}");
        }
        
        // 确保模块和表存在
        if (!isset($this->enumClassMap[$module])) {
            $this->enumClassMap[$module] = [];
        }
        
        if (!isset($this->enumClassMap[$module][$table])) {
            $this->enumClassMap[$module][$table] = [];
        }
        
        // 注册枚举类型
        $this->enumClassMap[$module][$table][$field] = [$class, $method];
        
        return true;
    }
}