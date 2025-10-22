<?php

namespace app\adminapi\controller\pointsGoods\validate;

use app\deshang\base\BaseValidate;

class PointsGoodsValidate extends BaseValidate
{
    // 定义验证规则
    protected $rule = [
        'id' => 'require|integer|gt:0', // 积分商品ID，必填，必须是大于0的整数
        'goods_name' => 'require|max:128|chsDash', // 商品名称，必填，最大长度128，可包含汉字、字母、数字、下划线和破折号
        'goods_advword' => 'max:150|chsDash', // 商品广告词，最大长度150，可包含汉字、字母、数字、下划线和破折号
        'goods_body' => 'max:65535', // 商品详情描述，最大长度65535
        'goods_status' => 'in:0,1', // 商品状态，必须是0或1
        'category_id' => 'integer|egt:0', // 积分商品分类ID，必须是大于等于0的整数
        'slide_image' => 'require|array', // 商品轮播图，必填，必须是数组
        'points_price' => 'require|integer|egt:0', // 积分价格，必填，必须是大于等于0的整数
        'market_price' => 'float|egt:0', // 市场参考价格，必须是大于等于0的浮点数
        'stock_num' => 'integer|egt:0', // 库存数量，必须是大于等于0的整数
        'limit_per_user' => 'integer|egt:0', // 每人限购数量，必须是大于等于0的整数
        'limit_per_day' => 'integer|egt:0', // 每日限购数量，必须是大于等于0的整数
        'goods_sort' => 'integer|between:0,9999', // 排序权重，必须是0到9999之间的整数
        'is_hot' => 'in:0,1', // 是否热门，必须是0或1
        'is_recommend' => 'in:0,1', // 是否推荐，必须是0或1
        'is_new' => 'in:0,1', // 是否新品，必须是0或1
    ];

    // 定义验证提示
    protected $message = [
        'id.require' => '积分商品ID不能为空',
        'id.integer' => '积分商品ID必须是整数',
        'id.gt' => '积分商品ID必须大于0',
        'goods_name.require' => '商品名称不能为空',
        'goods_name.max' => '商品名称不能超过128个字符',
        'goods_name.chsDash' => '商品名称只能包含汉字、字母、数字、下划线和破折号',
        'goods_advword.max' => '商品广告词不能超过150个字符',
        'goods_advword.chsDash' => '商品广告词只能包含汉字、字母、数字、下划线和破折号',
        'goods_body.max' => '商品详情描述不能超过65535个字符',
        'goods_status.in' => '商品状态必须是0或1',
        'category_id.integer' => '积分商品分类ID必须是整数',
        'category_id.egt' => '积分商品分类ID必须大于等于0',
        'slide_image.require' => '商品轮播图不能为空',
        'slide_image.array' => '商品轮播图必须是数组',
        'points_price.require' => '积分价格不能为空',
        'points_price.integer' => '积分价格必须是整数',
        'points_price.egt' => '积分价格必须大于等于0',
        'market_price.float' => '市场参考价格必须是数字',
        'market_price.egt' => '市场参考价格必须大于等于0',
        'stock_num.integer' => '库存数量必须是整数',
        'stock_num.egt' => '库存数量必须大于等于0',
        'limit_per_user.integer' => '每人限购数量必须是整数',
        'limit_per_user.egt' => '每人限购数量必须大于等于0',
        'limit_per_day.integer' => '每日限购数量必须是整数',
        'limit_per_day.egt' => '每日限购数量必须大于等于0',
        'goods_sort.integer' => '排序权重必须是整数',
        'goods_sort.between' => '排序权重必须在0到9999之间',
        'is_hot.in' => '是否热门必须是0或1',
        'is_recommend.in' => '是否推荐必须是0或1',
        'is_new.in' => '是否新品必须是0或1',
    ];

    // 定义场景
    protected $scene = [
        'pages' => ['goods_status', 'category_id'], // 分页查询场景 - 只验证搜索条件
        'info' => ['id'], // 获取详情场景
        'create' => ['goods_name', 'goods_advword', 'goods_body', 'category_id', 'slide_image', 'points_price', 'market_price', 'stock_num', 'limit_per_user', 'limit_per_day', 'goods_sort', 'is_hot', 'is_recommend', 'is_new'], // 创建场景
        'update' => ['id', 'goods_name', 'goods_advword', 'goods_body', 'goods_status', 'category_id', 'slide_image', 'points_price', 'market_price', 'stock_num', 'limit_per_user', 'limit_per_day', 'goods_sort', 'is_hot', 'is_recommend', 'is_new'], // 更新场景
        'delete' => ['id'], // 删除场景
    ];

}
