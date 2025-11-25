<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysPlatformModel;

/**
 * 系统平台数据访问对象
 * 
 * 负责系统平台的数据库交互操作
 */
class SysPlatformDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysPlatformModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysPlatformModel();
    }
    
    /**
     * 创建系统平台
     * 
     * @param array $data 平台数据
     * @return int 新创建的平台ID
     */
    public function createSysPlatform(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除系统平台
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysPlatform(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新系统平台
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateSysPlatform(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取系统平台列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 平台列表
     */
    public function getSysPlatformList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条系统平台信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 平台信息
     */
    public function getSysPlatformInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取系统平台数量
     * 
     * @param array $condition 查询条件
     * @return int 平台数量
     */
    public function getSysPlatformCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取系统平台列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysPlatformColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
