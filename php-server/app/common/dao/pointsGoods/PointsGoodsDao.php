<?php

namespace app\common\dao\pointsGoods;

use app\common\dao\BaseDao;
use app\common\model\pointsGoods\PointsGoodsModel;
use app\deshang\exceptions\CommonException;

/**
 * 积分商品数据访问对象
 * 
 * 负责积分商品的数据库交互操作
 */
class PointsGoodsDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化PointsGoodsModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PointsGoodsModel();
    }

    /**
     * 创建积分商品
     * 
     * @param array $data 积分商品数据
     * @return int 新创建的积分商品ID
     */
    public function createPointsGoods(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除积分商品
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePointsGoods(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 软删除积分商品
     * 
     * @param array $condition 删除条件
     * @return bool 是否删除成功
     */
    public function softDeletePointsGoods(array $condition): bool
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 更新积分商品
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updatePointsGoods(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取积分商品列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 积分商品列表
     */
    public function getPointsGoodsList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取积分商品分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getPointsGoodsPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['goods_status_desc', 'is_hot_desc', 'is_recommend_desc', 'is_new_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条积分商品信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 积分商品信息
     */
    public function getPointsGoodsInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取积分商品信息
     * 
     * @param int $id 积分商品ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 积分商品信息
     */
    public function getPointsGoodsInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取积分商品数量
     * 
     * @param array $condition 查询条件
     * @return int 积分商品数量
     */
    public function getPointsGoodsCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取积分商品列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getPointsGoodsColumn(array $condition, string $column): array
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
    public function setPointsGoodsInc(array $condition, string $field, int $step = 1): bool
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
    public function setPointsGoodsDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }


}
