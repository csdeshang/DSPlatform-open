<?php

namespace app\common\dao\admin;

use app\common\dao\BaseDao;
use app\common\model\admin\AdminRoleModel;

/**
 * 管理员角色数据访问对象
 * 
 * 负责管理员角色的数据库交互操作
 */
class AdminRoleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AdminRoleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminRoleModel();
    }
    
    /**
     * 创建管理员角色
     * 
     * @param array $data 角色数据
     * @return int 新创建的角色ID
     */
    public function createAdminRole(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除管理员角色
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAdminRole(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新管理员角色
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateAdminRole(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取管理员角色列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 角色列表
     */
    public function getAdminRoleList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取管理员角色分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 分页数据
     */
    public function getAdminRolePages(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条管理员角色信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 角色信息
     */
    public function getAdminRoleInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取管理员角色信息
     * 
     * @param int $id 角色ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 角色信息
     */
    public function getAdminRoleById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取管理员角色数量
     * 
     * @param array $condition 查询条件
     * @return int 角色数量
     */
    public function getAdminRoleCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取管理员角色列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAdminRoleColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
