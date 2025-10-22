<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoLikeModel;

/**
 * 视频点赞数据访问对象
 * 
 * 负责视频点赞的数据库交互操作
 */
class VideoLikeDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoLikeModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoLikeModel();
    }

    /**
     * 创建视频点赞
     * 
     * @param array $data 点赞数据
     * @return int 新创建的点赞ID
     */
    public function createVideoLike(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除视频点赞
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoLike(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 获取视频点赞列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 点赞列表
     */
    public function getVideoLikeList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频点赞分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoLikePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条视频点赞信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 点赞信息
     */
    public function getVideoLikeInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取视频点赞数量
     * 
     * @param array $condition 查询条件
     * @return int 点赞数量
     */
    public function getVideoLikeCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }


    /**
     * 获取视频点赞列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoLikeColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

}
