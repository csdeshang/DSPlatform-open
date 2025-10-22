<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysAgreementModel;

/**
 * 系统协议数据访问对象
 * 
 * 负责系统协议的数据库交互操作
 */
class SysAgreementDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysAgreementModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysAgreementModel();
    }
    
    /**
     * 创建系统协议
     * 
     * @param array $data 协议数据
     * @return int 新创建的协议ID
     */
    public function createSysAgreement(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除系统协议
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysAgreement(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新系统协议
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateSysAgreement(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取系统协议列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 协议列表
     */
    public function getSysAgreementList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条系统协议信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 协议信息
     */
    public function getSysAgreementInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取系统协议数量
     * 
     * @param array $condition 查询条件
     * @return int 协议数量
     */
    public function getSysAgreementCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取系统协议列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysAgreementColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
