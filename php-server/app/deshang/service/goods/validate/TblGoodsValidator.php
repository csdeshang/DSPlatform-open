<?php

namespace app\deshang\service\goods\validate;

use app\deshang\base\BaseValidate;

class TblGoodsValidator extends BaseValidate
{
    protected $rule = [
        'goods_name' => 'require|length:2,128',
        'goods_advword' => 'length:0,150',
        'goods_status' => 'in:0,1',
        'brand_id' => 'integer|egt:0',
        'store_goods_cid' => 'integer|egt:0',
        'stock_num' => 'integer|egt:0',
        'virtual_sales_num' => 'integer|egt:0',
        'goods_sort' => 'integer|egt:0',
        'goods_parameters' => 'array',
        
        // [Mall类型商品]运费相关字段验证
        'mall_express_type' => 'in:0,1,2',
        'mall_express_tpl_id' => 'integer|egt:0',
        'mall_express_fee' => 'float|egt:0',
    ];

    protected $message = [
        'goods_name.require' => '商品名称不能为空',
        'goods_name.length' => '商品名称长度必须在2到128个字符之间',
        'goods_advword.length' => '商品广告词长度不能超过150个字符',
        'goods_status.in' => '商品状态值不正确',
        'brand_id.integer' => '品牌ID必须是整数',
        'brand_id.egt' => '品牌ID不能小于0',
        'store_goods_cid.integer' => '店铺商品分类ID必须是整数',
        'store_goods_cid.egt' => '店铺商品分类ID不能小于0',
        'stock_num.integer' => '库存数量必须是整数',
        'stock_num.egt' => '库存数量不能小于0',
        'virtual_sales_num.integer' => '虚拟销量必须是整数',
        'virtual_sales_num.egt' => '虚拟销量不能小于0',
        'goods_sort.integer' => '排序值必须是整数',
        'goods_sort.egt' => '排序值不能小于0',
        'goods_parameters.array' => '商品参数格式不正确',
        
        // 运费相关字段错误信息
        'mall_express_type.in' => '快递类型值不正确，只能是0(包邮)、1(统一运费)、2(运费模板)',
        'mall_express_tpl_id.integer' => '运费模板ID必须是整数',
        'mall_express_tpl_id.egt' => '运费模板ID不能小于0',
        'mall_express_fee.float' => '固定运费必须是数字',
        'mall_express_fee.egt' => '固定运费不能小于0',
    ];

    protected $scene = [
        'add' => ['goods_name', 'goods_status', 'brand_id', 'store_goods_cid', 'stock_num', 'virtual_sales_num', 'goods_sort', 'mall_express_type', 'mall_express_tpl_id', 'mall_express_fee'],
        'edit' => ['goods_name', 'goods_status', 'brand_id', 'store_goods_cid', 'stock_num', 'virtual_sales_num', 'goods_sort', 'mall_express_type', 'mall_express_tpl_id', 'mall_express_fee'],
    ];

    // 自定义验证规则
    protected function checkExpressFee($value, $rule, $data)
    {
        // 如果选择了统一运费，则必须填写运费金额
        if (isset($data['mall_express_type']) && $data['mall_express_type'] == 1) {
            if (empty($value) || $value <= 0) {
                return '选择统一运费时，必须填写运费金额';
            }
        }
        return true;
    }

    protected function checkExpressTemplate($value, $rule, $data)
    {
        // 如果选择了运费模板，则必须填写模板ID
        if (isset($data['mall_express_type']) && $data['mall_express_type'] == 2) {
            if (empty($value) || $value <= 0) {
                return '选择运费模板时，必须选择有效的运费模板';
            }
        }
        return true;
    }
}
