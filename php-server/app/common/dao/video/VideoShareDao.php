<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoShareModel;

/**
 * 视频分享数据访问对象
 * 
 * 负责视频分享的数据库交互操作
 */
class VideoShareDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoShareModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoShareModel();
    }

    /**
     * 创建视频分享
     * 
     * @param array $data 分享数据
     * @return int 新创建的分享ID
     */
    public function createVideoShare(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除视频分享
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoShare(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 获取视频分享列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分享列表
     */
    public function getVideoShareList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频分享分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoSharePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条视频分享信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 分享信息
     */
    public function getVideoShareInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取视频分享数量
     * 
     * @param array $condition 查询条件
     * @return int 分享数量
     */
    public function getVideoShareCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }



    /**
     * 获取视频分享列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoShareColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

}
