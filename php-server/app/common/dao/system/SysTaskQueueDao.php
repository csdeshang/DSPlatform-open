<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysTaskQueueModel;

class SysTaskQueueDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysTaskQueueModel();
    }

    /**
     * 创建任务队列记录
     * 
     * @param array $data 任务数据
     * @return int 新创建的任务ID
     */
    public function createSysTaskQueue(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除任务队列记录
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysTaskQueue(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新任务队列记录
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响行数
     */
    public function updateSysTaskQueue(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取任务队列列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 任务列表
     */
    public function getSysTaskQueueList(array $condition, string $field = '*', string $order = 'id desc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    public function getSysTaskQueuePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $query = $this->model->where($condition)
            ->append(['status_desc', 'queue_group_desc', 'scheduled_at'])
            ->field($field)->order($order);
        return $this->getPaginate($query);
    }

    public function getSysTaskQueueInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
            ->append(['status_desc', 'queue_group_desc', 'scheduled_at'])
            ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    public function getSysTaskQueueInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)
            ->append(['status_desc', 'queue_group_desc', 'scheduled_at'])
            ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取任务队列数量
     * 
     * @param array $condition 查询条件
     * @return int 任务数量
     */
    public function getSysTaskQueueCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取任务队列列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysTaskQueueColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
