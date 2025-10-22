<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoCommentModel;

/**
 * 视频评论数据访问对象
 * 
 * 负责视频评论的数据库交互操作
 */
class VideoCommentDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoCommentModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoCommentModel();
    }

    /**
     *  创建视频评论
     * 
     * @param array $data 评论数据
     * @return int 新创建的评论ID
     */
    public function createVideoComment(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除视频评论
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoComment(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新视频评论
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateVideoComment(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取视频评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 评论列表
     */
    public function getVideoCommentList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoCommentPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取视频评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelVideoCommentPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }





    /**
     * 获取单条视频评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getVideoCommentInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取视频评论数量
     * 
     * @param array $condition 查询条件
     * @return int 评论数量
     */
    public function getVideoCommentCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取视频评论列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoCommentColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


    /**
     * 评论点赞数自增
     * 
     * @param array $condition 查询条件
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setVideoCommentInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }

    /**
     * 评论点赞数自减
     * 
     * @param array $condition 查询条件
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setVideoCommentDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}

