<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoCategoryModel;

/**
 * 视频分类数据访问对象
 * 
 * 负责视频分类的数据库交互操作
 */
class VideoCategoryDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoCategoryModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoCategoryModel();
    }

    /**
     * 创建视频分类
     * 
     * @param array $data 分类数据
     * @return int 新创建的分类ID
     */
    public function createVideoCategory(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除视频分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoCategory(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新视频分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateVideoCategory(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取视频分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分类列表
     */
    public function getVideoCategoryList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取视频分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoCategoryPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条视频分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 分类信息
     */
    public function getVideoCategoryInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取视频分类信息
     * 
     * @param int $id 分类ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 分类信息
     */
    public function getVideoCategoryInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取视频分类数量
     * 
     * @param array $condition 查询条件
     * @return int 分类数量
     */
    public function getVideoCategoryCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取视频分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoCategoryColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
} 