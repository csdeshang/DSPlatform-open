<?php

namespace app\common\dao\technician;

use app\common\dao\BaseDao;
use app\common\model\technician\TechnicianModel;

/**
 * 师傅数据访问对象
 * 
 * 负责师傅的数据库交互操作
 */
class TechnicianDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TechnicianModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TechnicianModel();
    }

    /**
     * 创建师傅
     * 
     * @param array $data 师傅数据
     * @return int 新创建的师傅ID
     */
    public function createTechnician(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除师傅
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTechnician(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新师傅
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateTechnician(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取师傅列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 师傅列表
     */
    public function getTechnicianList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取师傅分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getTechnicianPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['technician_status_desc', 'gender_desc', 'apply_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联数据的师傅分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelTechnicianPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'store' => function ($query) {
                        $query->field('id,merchant_id,store_name,store_logo');
                    }
                ]
            )
            ->append(['technician_status_desc', 'gender_desc', 'apply_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条师傅信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 师傅信息
     */
    public function getTechnicianInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->append(['technician_status_desc', 'gender_desc', 'apply_status_desc'])
            ->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联数据的单条师傅信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 师傅信息
     */
    public function getWithRelTechnicianInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'store' => function ($query) {
                        $query->field('id,merchant_id,store_name,store_logo');
                    }
                ]
            )
            ->append(['technician_status_desc', 'gender_desc', 'apply_status_desc'])
            ->field($field)->findOrEmpty()->toArray();
    }




    /**
     * 根据ID获取师傅信息
     * 
     * @param int $id 师傅ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 师傅信息
     */
    public function getTechnicianInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取师傅数量
     * 
     * @param array $condition 查询条件
     * @return int 师傅数量
     */
    public function getTechnicianCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取师傅列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getTechnicianColumn(array $condition, string $column): array
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
    public function setTechnicianInc(array $condition, string $field, int $step = 1): bool
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
    public function setTechnicianDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
