<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysConfigModel;

/**
 * 系统配置数据访问对象
 * 
 * 负责系统配置的数据库交互操作
 */
class SysConfigDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysConfigModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysConfigModel();
    }
    
    /**
     * 创建系统配置
     * 
     * @param array $data 配置数据
     * @return int 新创建的配置ID
     */
    public function createSysConfig(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除系统配置
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysConfig(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新系统配置
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateSysConfig(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取系统配置列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 配置列表
     */
    public function getSysConfigList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取系统配置分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 分页数据
     */
    public function getSysConfigPages(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条系统配置信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 配置信息
     */
    public function getSysConfigInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取系统配置信息
     * 
     * @param int $id 配置ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 配置信息
     */
    public function getSysConfigInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据键名获取系统配置信息
     * 
     * @param string $key 配置键名
     * @param string $field 查询字段，默认为所有字段
     * @return array 配置信息
     */
    public function getSysConfigInfoByKey(string $key, string $field = '*'): array
    {
        return $this->model->where('key', $key)->field($field)->findOrEmpty()->toArray();
    }
    

    
    /**
     * 获取系统配置数量
     * 
     * @param array $condition 查询条件
     * @return int 系统配置数量
     */
    public function getSysConfigCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    



}
