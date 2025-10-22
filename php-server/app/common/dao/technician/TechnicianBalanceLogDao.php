<?php

namespace app\common\dao\technician;

use app\common\dao\BaseDao;
use app\common\model\technician\TechnicianBalanceLogModel;

/**
 * 师傅余额日志数据访问对象
 * 
 * 负责师傅余额变动记录的数据库交互操作
 */
class TechnicianBalanceLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TechnicianBalanceLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TechnicianBalanceLogModel();
    }

    /**
     * 创建师傅余额日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createTechnicianBalanceLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除师傅余额日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTechnicianBalanceLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新师傅余额日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateTechnicianBalanceLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取师傅余额日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getTechnicianBalanceLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取师傅余额日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getTechnicianBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['change_type_desc', 'change_mode_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取师傅余额日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelTechnicianBalanceLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'technician' => function ($query) {
                        $query->field('id,name,mobile');
                    },
                ]
            )
            ->append(['change_type_desc', 'change_mode_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条师傅余额日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getTechnicianBalanceLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取师傅余额日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getTechnicianBalanceLogInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取师傅余额日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getTechnicianBalanceLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取师傅余额日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getTechnicianBalanceLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
