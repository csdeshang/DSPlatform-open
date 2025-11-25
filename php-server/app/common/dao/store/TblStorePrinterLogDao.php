<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStorePrinterLogModel;

/**
 * 店铺打印机日志数据访问对象
 * 
 * 负责店铺打印机日志的数据库交互操作
 */
class TblStorePrinterLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStorePrinterLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStorePrinterLogModel();
    }

    /**
     * 创建店铺打印机日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createStorePrinterLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺打印机日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStorePrinterLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新店铺打印机日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateStorePrinterLog(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取店铺打印机日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 日志列表
     */
    public function getStorePrinterLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺打印机日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getStorePrinterLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取店铺打印机日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelStorePrinterLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->with(
            [
                'store' => function ($query) {
                    $query->field('id,store_name');
                },
            ]
        )
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条店铺打印机日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getStorePrinterLogInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取店铺打印机日志信息
     * 
     * @param int $id 日志ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 日志信息
     */
    public function getStorePrinterLogInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取店铺打印机日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getStorePrinterLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取店铺打印机日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStorePrinterLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


}
