<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysNoticeLogModel;

/**
 * 系统通知日志数据访问对象
 * 
 * 负责系统通知日志的数据库交互操作
 */
class SysNoticeLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化SysNoticeLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysNoticeLogModel();
    }
    
    /**
     * 创建系统通知日志
     * 
     * @param array $data 日志数据
     * @return int 新创建的日志ID
     */
    public function createSysNoticeLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建系统通知日志
     * 
     * @param array $dataList 日志数据列表
     * @return bool 是否成功
     */
    public function createSysNoticeLogAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除系统通知日志
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysNoticeLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新系统通知日志
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateSysNoticeLog(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取系统通知日志列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序（最新记录优先）
     * @return array 日志列表
     */
    public function getSysNoticeLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->append(['notice_channel_desc','send_status_desc'])
        ->field($field)->order($order)->select()->toArray();
    }
    /**
     * 获取系统通知日志分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getSysNoticeLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['notice_channel_desc','send_status_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条系统通知日志信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 日志信息
     */
    public function getSysNoticeLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
        ->append(['notice_channel_desc','send_status_desc'])
        ->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取系统通知日志数量
     * 
     * @param array $condition 查询条件
     * @return int 日志数量
     */
    public function getSysNoticeLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取系统通知日志列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysNoticeLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
