<?php

namespace app\common\dao\admin;

use app\common\dao\BaseDao;
use app\common\model\admin\AdminModel;

/**
 * 管理员数据访问对象
 * 
 * 负责管理员的数据库交互操作
 */
class AdminDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AdminModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminModel();
    }

    /**
     * 创建管理员
     * 
     * @param array $data 管理员数据
     * @return int 新创建的管理员ID
     */
    public function createAdmin(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除管理员
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAdmin(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新管理员
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateAdmin(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取管理员列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @param int $limit 限制条数，默认10条
     * @return array 管理员列表
     */
    public function getAdminList(array $condition, string $field = '*', string $order = 'id asc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取管理员分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 分页数据
     */
    public function getAdminPages(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条管理员信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 管理员信息
     */
    public function getAdminInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联管理员信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 管理员信息
     */
    public function getWithRelAdminInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with([
                'adminRole' => function ($query) {
                    $query->field('id,rules');
                }
            ])
            ->field($field)
            ->findOrEmpty()->toArray();
    }




    /**
     * 根据ID获取管理员信息
     * 
     * @param int $id 管理员ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 管理员信息
     */
    public function getAdminInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }


    /**
     * 获取管理员数量
     * 
     * @param array $condition 查询条件
     * @return int 管理员数量
     */
    public function getAdminCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取管理员列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAdminColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
