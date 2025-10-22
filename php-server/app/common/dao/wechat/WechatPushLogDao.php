<?php

namespace app\common\dao\wechat;

use app\common\dao\BaseDao;
use app\common\model\wechat\WechatPushLogModel;

/**
 * 微信推送日志数据访问对象
 */
class WechatPushLogDao extends BaseDao
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new WechatPushLogModel();
    }
    
    /**
     * 创建推送日志记录
     */
    public function createWechatPushLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除推送日志记录
     */
    public function deleteWechatPushLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新推送日志记录
     */
    public function updateWechatPushLog(array $condition, array $data): bool
    {
        $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取推送日志记录列表
     */
    public function getWechatPushLogList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取推送日志记录分页列表
     */
    public function getWechatPushLogPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条推送日志记录信息
     */
    public function getWechatPushLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取推送日志记录信息
     */
    public function getWechatPushLogInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取推送日志记录数量
     */
    public function getWechatPushLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取推送日志记录列
     */
    public function getWechatPushLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }




}
