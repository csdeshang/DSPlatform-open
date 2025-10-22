<?php

namespace app\deshang\service\cart\validate;

use app\deshang\base\BaseValidate;
use app\common\enum\goods\TblGoodsPromotionEnum;

class TblCartValidator extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'goods_id' => 'require|integer', // 商品ID必须存在且为整数
        'quantity' => 'require|integer|gt:0', // 数量必须存在且为正整数
        'sku_id' => 'require|integer', // SKU ID必须存在且为整数
        'promotion_type' => 'checkPromotionType', // 促销类型必须是指定的值
        'cart_id' => 'require|integer', // 购物车ID必须存在且为整数
        'cart_ids' => 'require|array|isAllNumbers', // 购物车ID必须存在且为数组
    ];

    // 定义错误信息
    protected $message = [
        'goods_id.require' => '商品ID不能为空',
        'goods_id.integer' => '商品ID必须为整数',
        'quantity.require' => '数量不能为空',
        'quantity.integer' => '数量必须为整数',
        'quantity.gt' => '数量必须大于0',
        'sku_id.require' => 'SKU ID不能为空',
        'sku_id.integer' => 'SKU ID必须为整数',
        'promotion_type.checkPromotionType' => '促销类型不合法',
        'cart_id.require' => '购物车ID不能为空',
        'cart_id.integer' => '购物车ID必须为整数',
        'cart_ids.require' => '购物车ID不能为空',
        'cart_ids.array' => '购物车ID必须为数组',
        'cart_ids.isAllNumbers' => '购物车ID必须为数字',

    ];

    // 定义场景
    protected $scene = [
        'add' => ['goods_id', 'quantity', 'sku_id', 'promotion_type'], // 添加场景
        'update' => ['cart_id', 'quantity'], // 更新场景（如果需要）
        'delete' => ['cart_ids'], // 删除场景
    ];


    // 检测促销类型
    protected function checkPromotionType($value, $rule, $data = [])
    {

        // 获取促销类型字典
        $promotion_type_dict = TblGoodsPromotionEnum::getPromotionTypeDict();
        if (!array_key_exists($value, $promotion_type_dict)) {
            return false;
        }
        return true;
    }


    


}
