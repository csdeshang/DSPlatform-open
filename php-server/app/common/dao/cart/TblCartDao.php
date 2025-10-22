<?php

namespace app\common\dao\cart;

use app\common\dao\BaseDao;
use app\common\model\cart\TblCartModel;

/**
 * 购物车数据访问对象
 * 
 * 负责购物车的数据库交互操作
 */
class TblCartDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblCartModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblCartModel();
    }

    /**
     * 创建购物车项
     * 
     * @param array $data 购物车数据
     * @return int 新创建的购物车项ID
     */
    public function createCart(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除购物车项
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteCart(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新购物车项
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateCart(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取购物车列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 购物车列表
     */
    public function getCartList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取带关联的购物车列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 购物车列表
     */
    public function getWithRelCartList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)
            ->with([
                'goods' => function ($query) {
                    $query->field('id,goods_name,goods_minprice,goods_status,store_id,brand_id,store_goods_cid,cover_image,stock_num,click_num,sales_num,collect_num,evaluate_num,goods_sort,avg_goods_score,is_flashsale_goods,flashsale_goods_status,is_wholesale_goods,is_userdiscount_goods,is_distributor_goods,distributor_goods_type,sys_status,mall_express_type,mall_express_tpl_id,mall_express_fee');
                },
                'goodsSku' => function ($query) {
                    
                }
            ])
            ->append(['promotion_type_desc'])
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }




    /**
     * 获取单条购物车信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 购物车信息
     */
    public function getCartInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取购物车信息
     * 
     * @param int $id 购物车ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 购物车信息
     */
    public function getCartInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取购物车数量
     * 
     * @param array $condition 查询条件
     * @return int 购物车数量
     */
    public function getCartCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取购物车列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getCartColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 清空用户购物车
     * 
     * @param int $userId 用户ID
     * @return int 受影响的行数
     */
    public function clearUserCart(int $userId): int
    {
        return $this->model->where('user_id', $userId)->delete();
    }
}
