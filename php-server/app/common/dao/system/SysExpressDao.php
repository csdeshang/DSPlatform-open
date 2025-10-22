<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysExpressModel;

/**
 * 系统快递公司数据访问对象
 * 
 * 负责系统快递公司的数据库交互操作
 */
class SysExpressDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysExpressModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysExpressModel();
    }
    
    /**
     * 创建快递公司
     * 
     * @param array $data 快递公司数据
     * @return int 新创建的快递公司ID
     */
    public function createExpress(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除快递公司
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteExpress(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新快递公司
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateExpress(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取快递公司列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 快递公司列表
     */
    public function getExpressList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取快递公司分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getExpressPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条快递公司信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 快递公司信息
     */
    public function getExpressInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取快递公司信息
     * 
     * @param int $id 快递公司ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 快递公司信息
     */
    public function getExpressInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    
    /**
     * 获取快递公司数量
     * 
     * @param array $condition 查询条件
     * @return int 快递公司数量
     */
    public function getExpressCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取快递公司列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getExpressColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    


}
