<?php

namespace app\common\dao\distributor;

use app\common\dao\BaseDao;
use app\common\model\distributor\DistributorGoodsModel;

/**
 * 分销商品数据访问对象
 * 
 * 负责分销商品的数据库交互操作
 */
class DistributorGoodsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化DistributorGoodsModel模型实例
     */
    public function __construct()
    {
        $this->model = new DistributorGoodsModel();
    }

    /**
     * 创建分销商品
     * 
     * @param array $data 商品数据
     * @return int 新创建的商品ID
     */
    public function createDistributorGoods(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建分销商品
     * 
     * @param array $data 商品数据
     * @return int 新创建的商品ID
     */
    public function createDistributorGoodsAll(array $data): bool
    {
        return $this->model->saveAll($data) ? true : false;
    }


    /**
     * 删除分销商品
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteDistributorGoods(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新分销商品
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateDistributorGoods(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取分销商品列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 商品列表
     */
    public function getDistributorGoodsList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }

    /**
     * 获取分销商品分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getDistributorGoodsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getDistributorGoodsInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取商品信息
     * 
     * @param int $id 商品ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 商品信息
     */
    public function getDistributorGoodsById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取商品数量
     * 
     * @param array $condition 查询条件
     * @return int 商品数量
     */
    public function getDistributorGoodsCount(array $condition): int
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
    public function getDistributorGoodsColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
