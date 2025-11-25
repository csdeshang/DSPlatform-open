<?php

namespace app\common\dao\admin;

use app\common\dao\BaseDao;
use app\common\model\admin\AdminMenuModel;

/**
 * 管理员菜单数据访问对象
 * 
 * 负责管理员菜单的数据库交互操作
 */
class AdminMenuDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AdminMenuModel模型实例
     */
    public function __construct()
    {
        $this->model = new AdminMenuModel();
    }

    /**
     * 创建管理员菜单
     * 
     * @param array $data 菜单数据
     * @return int 新创建的菜单ID
     */
    public function createAdminMenu(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除管理员菜单
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAdminMenu(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新管理员菜单
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateAdminMenu(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取管理员菜单列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 菜单列表
     */
    public function getAdminMenuList(array $condition, string $field = '*', string $order = 'sort asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取管理员菜单分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 分页数据
     */
    public function getAdminMenuPages(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条管理员菜单信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 菜单信息
     */
    public function getAdminMenuInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取管理员菜单信息
     * 
     * @param int $id 菜单ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 菜单信息
     */
    public function getAdminMenuById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取管理员菜单数量
     * 
     * @param array $condition 查询条件
     * @return int 菜单数量
     */
    public function getAdminMenuCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取管理员菜单列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名   
     * @return array 列数据
     */
    public function getAdminMenuColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    
    
}
