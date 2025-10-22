<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoViewLogModel;

/**
 * 视频浏览记录数据访问对象
 * 
 * 负责视频浏览记录的数据库交互操作
 */
class VideoViewLogDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoViewLogModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoViewLogModel();
    }

    /**
     * 创建视频浏览记录
     * 
     * @param array $data 浏览记录数据
     * @return int 新创建的记录ID
     */
    public function createVideoViewLog(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 更新视频浏览记录
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateVideoViewLog(array $condition, array $data): int
    {
        return $this->model->where($condition)->update($data);
    }

    /**
     * 删除视频浏览记录
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoViewLog(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 获取视频浏览记录列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按更新时间降序
     * @return array 浏览记录列表
     */
    public function getVideoViewLogList(array $condition, string $field = '*', string $order = 'update_at desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频浏览记录分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按更新时间降序
     * @return array 分页数据
     */
    public function getVideoViewLogPages(array $condition, string $field = '*', string $order = 'update_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条视频浏览记录信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 浏览记录信息
     */
    public function getVideoViewLogInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取视频浏览记录数量
     * 
     * @param array $condition 查询条件
     * @return int 记录数量
     */
    public function getVideoViewLogCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取视频浏览记录列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoViewLogColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


} 