<?php

namespace app\deshang\service\order;

use app\deshang\service\BaseDeshangService;
use app\deshang\exceptions\CommonException;

/**
 * 商城运费计算服务
 */
class DeshangMallExpressService extends BaseDeshangService
{
    // 运费计算策略
    const STRATEGY_MAX_FEE = 'max_fee';        // 取最高运费
    const STRATEGY_SUM_FEE = 'sum_fee';        // 运费相加

    private $express_strategy = self::STRATEGY_MAX_FEE; // 默认取最高运费策略

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 设置运费计算策略
     * 
     * @param string $strategy 策略类型
     * @return $this
     */
    public function setExpressStrategy($strategy)
    {
        $this->express_strategy = $strategy;
        return $this;
    }

    /**
     * 计算商城运费
     * 
     * @param array $store_info 店铺信息
     * @param array $goods_list 商品列表
     * @param array $address 收货地址
     * @return array 运费计算结果
     */
    public function calculateMallExpressFee($store_info, $goods_list, $address)
    {
        // 先计算每个商品的运费
        $goods_express_fees = [];
        foreach ($goods_list as $item) {
            $goods_express_fees[] = $this->calculateSingleGoodsExpressFee($item, $address);
        }
        
        // 根据策略合并所有商品的运费
        $final_result = $this->mergeExpressFeesByStrategy($goods_express_fees);
        
        return $final_result;
    }

    /**
     * 计算单个商品的运费
     * 
     * @param array $goods_item 商品项
     * @param array $address 收货地址
     * @return array 单个商品的运费计算结果
     */
    private function calculateSingleGoodsExpressFee($goods_item, $address)
    {
        if (!isset($goods_item['goods']['mall_express_type'])) {
            return [
                'express_type' => 0,
                'express_fee' => 0,
                'express_tpl_id' => 0,
                'shipping_amount' => 0,
                'quantity' => $goods_item['quantity'] ?? 1,
                'calculation_details' => [
                    'type' => 'unknown',
                    'description' => '商品无运费设置',
                    'fee' => 0
                ]
            ];
        }

        $express_type = intval($goods_item['goods']['mall_express_type']);
        $express_fee = floatval($goods_item['goods']['mall_express_fee'] ?? 0);
        $express_tpl_id = intval($goods_item['goods']['mall_express_tpl_id'] ?? 0);
        $quantity = intval($goods_item['quantity'] ?? 1);

        $result = [
            'express_type' => $express_type,
            'express_fee' => $express_fee,
            'express_tpl_id' => $express_tpl_id,
            'quantity' => $quantity,
            'shipping_amount' => 0,
            'calculation_details' => []
        ];

        // 根据运费类型计算单个商品的运费
        switch ($express_type) {
            case 0: // 包邮
                $result['shipping_amount'] = 0;
                $result['calculation_details'] = [
                    'type' => 'free_shipping',
                    'description' => '商品包邮',
                    'fee' => 0
                ];
                break;

            case 1: // 统一运费
                $result['shipping_amount'] = $express_fee * $quantity;
                $result['calculation_details'] = [
                    'type' => 'fixed_fee',
                    'description' => '统一运费',
                    'fee' => $result['shipping_amount']
                ];
                break;

            case 2: // 运费模板
                // 预留运费模板功能
                $result['shipping_amount'] = $this->calculateTemplateFee([
                    'express_type' => $express_type,
                    'express_fee' => $express_fee,
                    'express_tpl_id' => $express_tpl_id
                ], [$goods_item], $address) * $quantity;
                $result['calculation_details'] = [
                    'type' => 'template_fee',
                    'description' => '运费模板计算',
                    'fee' => $result['shipping_amount']
                ];
                break;

            default:
                $result['shipping_amount'] = 0;
                $result['calculation_details'] = [
                    'type' => 'unknown',
                    'description' => '未知运费类型',
                    'fee' => 0
                ];
                break;
        }

        return $result;
    }

    /**
     * 根据策略合并所有商品的运费
     * 
     * @param array $goods_express_fees 所有商品的运费计算结果
     * @return array 最终运费计算结果
     */
    private function mergeExpressFeesByStrategy($goods_express_fees)
    {
        if (empty($goods_express_fees)) {
            return [
                'shipping_amount' => 0,
                'express_type' => 0,
                'express_fee' => 0,
                'express_tpl_id' => 0,
                'calculation_details' => [
                    'type' => 'empty',
                    'description' => '无商品',
                    'fee' => 0
                ]
            ];
        }

        switch ($this->express_strategy) {
            case self::STRATEGY_MAX_FEE:
                return $this->mergeByMaxFee($goods_express_fees);
            case self::STRATEGY_SUM_FEE:
                return $this->mergeBySumFee($goods_express_fees);
            default:
                return $this->mergeByMaxFee($goods_express_fees);
        }
    }

    /**
     * 取最高运费策略合并
     */
    private function mergeByMaxFee($goods_express_fees)
    {
        $max_fee = 0;
        $max_item = null;
        
        foreach ($goods_express_fees as $item) {
            if ($item['shipping_amount'] > $max_fee) {
                $max_fee = $item['shipping_amount'];
                $max_item = $item;
            }
        }

        return [
            'shipping_amount' => $max_fee,
            'express_type' => $max_item['express_type'] ?? 0,
            'express_fee' => $max_item['express_fee'] ?? 0,
            'express_tpl_id' => $max_item['express_tpl_id'] ?? 0,
            'calculation_details' => [
                'type' => 'max_fee',
                'description' => '取最高运费',
                'fee' => $max_fee,
                'strategy' => 'max_fee'
            ]
        ];
    }

    /**
     * 运费相加策略合并
     */
    private function mergeBySumFee($goods_express_fees)
    {
        $total_fee = 0;
        $has_template = false;
        $template_id = 0;
        $express_type = 0;

        foreach ($goods_express_fees as $item) {
            $total_fee += $item['shipping_amount'];
            
            // 记录运费类型
            if ($item['express_type'] == 2) {
                $has_template = true;
                $template_id = $item['express_tpl_id'];
            }
            if ($item['express_type'] > $express_type) {
                $express_type = $item['express_type'];
            }
        }

        return [
            'shipping_amount' => $total_fee,
            'express_type' => $has_template ? 2 : $express_type,
            'express_fee' => $total_fee,
            'express_tpl_id' => $template_id,
            'calculation_details' => [
                'type' => 'sum_fee',
                'description' => '运费相加',
                'fee' => $total_fee,
                'strategy' => 'sum_fee'
            ]
        ];
    }



    /**
     * 计算运费模板费用（预留功能）
     * 
     * @param array $express_settings 运费设置
     * @param array $goods_list 商品列表
     * @param array $address 收货地址
     * @return float 运费金额
     */
    private function calculateTemplateFee($express_settings, $goods_list, $address)
    {
        // 运费模板功能暂未实现
        // 这里可以根据运费模板ID查询模板规则进行计算
        // 例如：按重量、按件数、按地区等计算
        
        $template_id = $express_settings['express_tpl_id'] ?? 0;
        
        if ($template_id <= 0) {
            return 0;
        }

        // TODO: 实现运费模板计算逻辑
        // 1. 根据template_id查询运费模板
        // 2. 根据商品重量/件数/体积计算
        // 3. 根据收货地址地区计算
        // 4. 返回计算结果

        return 0;
    }



}
