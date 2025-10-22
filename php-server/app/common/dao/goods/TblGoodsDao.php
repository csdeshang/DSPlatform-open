<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsModel;

/**
 * 商品数据访问对象
 * 
 * 负责商品的数据库交互操作
 */
class TblGoodsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsModel();
    }

    /**
     * 创建商品
     * 
     * @param array $data 商品数据
     * @return int 新创建的商品ID
     */
    public function createGoods(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除商品
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoods(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新商品
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateGoods(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取商品列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 商品列表
     */
    public function getGoodsList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }


    public function getWithRelGoodsList(array $condition, string $field = '*', string $order = 'id desc', int $limit = 10): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goodsSku' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,goods_id,sku_name,sku_image,sku_price,market_price,sku_stock,is_default')->where('is_default', 1);
                    },
                    'goodsSkuList' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,goods_id,sku_name,sku_image,sku_price,market_price,sku_stock,is_default');
                    },
                ]
            )
            ->append(['sys_status_desc', 'goods_status_desc'])
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select()
            ->toArray();
        return $result;
    }



    /**
     * 获取商品分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getGoodsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    public function getWithRelGoodsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goodsSku' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,goods_id,sku_name,sku_image,sku_price,market_price,sku_stock,is_default')->where('is_default', 1);
                    },
                    'goodsSkuList' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,goods_id,sku_name,sku_image,sku_price,market_price,sku_stock,is_default');
                    },
                ]
            )
            ->append(['sys_status_desc', 'goods_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条商品信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getGoodsInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
        ->append(['sys_status_desc', 'goods_status_desc'])
        ->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取商品信息
     * 
     * @param int $id 商品ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getGoodsInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取商品数量
     * 
     * @param array $condition 查询条件
     * @return int 商品数量
     */
    public function getGoodsCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取商品列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setGoodsInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }


    /**
     * 自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setGoodsDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
