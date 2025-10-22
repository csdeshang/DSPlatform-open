<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoCollectModel;

/**
 * 视频收藏数据访问对象
 * 
 * 负责视频收藏的数据库交互操作
 */
class VideoCollectDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoCollectModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoCollectModel();
    }

    /**
     * 创建视频收藏
     * 
     * @param array $data 收藏数据
     * @return int 新创建的收藏ID
     */
    public function createVideoCollect(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除视频收藏
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoCollect(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 获取视频收藏列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 收藏列表
     */
    public function getVideoCollectList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频收藏分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoCollectPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条视频收藏信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 收藏信息
     */
    public function getVideoCollectInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取视频收藏数量
     * 
     * @param array $condition 查询条件
     * @return int 收藏数量
     */
    public function getVideoCollectCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }


    /**
     * 获取视频收藏列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoCollectColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

}
