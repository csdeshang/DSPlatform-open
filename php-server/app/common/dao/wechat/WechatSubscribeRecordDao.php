<?php

namespace app\common\dao\wechat;

use app\common\dao\BaseDao;
use app\common\model\wechat\WechatSubscribeRecordModel;
use app\common\enum\wechat\WechatSubscribeEnum;

/**
 * 微信订阅记录数据访问对象
 */
class WechatSubscribeRecordDao extends BaseDao
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new WechatSubscribeRecordModel();
    }
    
    /**
     * 创建订阅记录
     */
    public function createWechatSubscribeRecord(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订阅记录
     */
    public function deleteWechatSubscribeRecord(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新订阅记录
     */
    public function updateWechatSubscribeRecord(array $condition, array $data): bool
    {
        $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取订阅记录列表
     */
    public function getWechatSubscribeRecordList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取订阅记录分页列表
     */
    public function getWechatSubscribeRecordPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条订阅记录信息
     */
    public function getWechatSubscribeRecordInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取订阅记录信息
     */
    public function getWechatSubscribeRecordInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取订阅记录数量
     */
    public function getWechatSubscribeRecordCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取订阅记录列
     */
    public function getWechatSubscribeRecordColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    

} 