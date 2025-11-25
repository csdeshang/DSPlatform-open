<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysNoticeTplModel;

/**
 * 系统通知模板数据访问对象
 * 
 * 负责系统通知模板的数据库交互操作
 */
class SysNoticeTplDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysNoticeTplModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysNoticeTplModel();
    }
    
    /**
     * 创建系统通知模板
     * 
     * @param array $data 模板数据
     * @return int 新创建的模板ID
     */
    public function createSysNoticeTpl(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除系统通知模板
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysNoticeTpl(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新系统通知模板
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateSysNoticeTpl(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取系统通知模板列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 模板列表
     */
    public function getSysNoticeTplList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)
        ->append(['template_type_desc','receiver_type_desc'])
        ->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取单条系统通知模板信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 模板信息
     */
    public function getSysNoticeTplInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
        ->append(['template_type_desc','receiver_type_desc'])
        ->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取系统通知模板数量
     * 
     * @param array $condition 查询条件
     * @return int 模板数量
     */
    public function getSysNoticeTplCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取系统通知模板列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysNoticeTplColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
