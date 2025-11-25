<?php

namespace app\common\dao\pointsGoods;

use app\common\dao\BaseDao;
use app\common\model\pointsGoods\PointsGoodsEvaluateModel;

/**
 * 积分商品评价数据访问对象
 * 
 * 负责积分商品评价的数据库交互操作
 */
class PointsGoodsEvaluateDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化PointsGoodsEvaluateModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new PointsGoodsEvaluateModel();
    }

    /**
     * 创建积分商品评价
     * 
     * @param array $data 评价数据
     * @return int 新创建的评价ID
     */
    public function createPointsGoodsEvaluate(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除积分商品评价
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deletePointsGoodsEvaluate(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 软删除积分商品评价
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function softDeletePointsGoodsEvaluate(array $condition): int
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 更新积分商品评价
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updatePointsGoodsEvaluate(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取积分商品评价列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 评价列表
     */
    public function getPointsGoodsEvaluateList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 10): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取积分商品评价分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getPointsGoodsEvaluatePages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条积分商品评价信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 评价信息
     */
    public function getPointsGoodsEvaluateInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取积分商品评价信息
     * 
     * @param int $id 评价ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 评价信息
     */
    public function getPointsGoodsEvaluateInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取积分商品评价数量
     * 
     * @param array $condition 查询条件
     * @return int 评价数量
     */
    public function getPointsGoodsEvaluateCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取积分商品评价列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getPointsGoodsEvaluateColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
