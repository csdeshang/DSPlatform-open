<?php

namespace app\common\dao\rider;

use app\common\dao\BaseDao;
use app\common\model\rider\RiderModel;

/**
 * 骑手数据访问对象
 * 
 * 负责骑手的数据库交互操作
 */
class RiderDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化RiderModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new RiderModel();
    }

    /**
     * 创建骑手
     * 
     * @param array $data 骑手数据
     * @return int 新创建的骑手ID
     */
    public function createRider(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除骑手
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteRider(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新骑手
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateRider(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取骑手列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 骑手列表
     */
    public function getRiderList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取骑手分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getRiderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取骑手分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelRiderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    }
                ]
            )
            ->append(['status_desc', 'apply_status_desc', 'is_enabled_desc', 'audit_time_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条骑手信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 骑手信息
     */
    public function getRiderInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联数据的单条骑手信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 骑手信息
     */
    public function getWithRelRiderInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    }
                ]
            )
            ->field($field)->findOrEmpty()->toArray();
    }




    /**
     * 根据ID获取骑手信息
     * 
     * @param int $id 骑手ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 骑手信息
     */
    public function getRiderInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取骑手数量
     * 
     * @param array $condition 查询条件
     * @return int 骑手数量
     */
    public function getRiderCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取骑手列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getRiderColumn(array $condition, string $column): array
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
    public function setRiderInc(array $condition, string $field, int $step = 1): bool
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
    public function setRiderDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
