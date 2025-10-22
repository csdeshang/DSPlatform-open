<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoShortModel;

/**
 * 短视频数据访问对象
 * 
 * 负责短视频的数据库交互操作
 */
class VideoShortDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoShortModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoShortModel();
    }

    /**
     * 创建短视频
     * 
     * @param array $data 短视频数据
     * @return int 新创建的短视频ID
     */
    public function createVideoShort(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除短视频
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoShort(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新短视频
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateVideoShort(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取短视频列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 短视频列表
     */
    public function getVideoShortList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取短视频列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 短视频列表
     */
    public function getWithRelVideoShortList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'blogger' => function ($query) {
                        $query->field('id,blogger_name,avatar');
                    }
                ]
            )
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
    }


    /**
     * 获取短视频分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getVideoShortPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['audit_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联数据的短视频分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelVideoShortPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'blogger' => function ($query) {
                        $query->field('id,blogger_name,avatar');
                    }
                ]
            )
            ->append(['audit_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条短视频信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 短视频信息
     */
    public function getVideoShortInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->append(['audit_status_desc'])
            ->field($field)->findOrEmpty()->toArray();
    }

    /** 
     * 获取带关联数据的单条短视频信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 短视频信息
     */
    public function getWithRelVideoShortInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'blogger' => function ($query) {
                        $query->field('id,blogger_name,avatar');
                    }
                ]
            )
            ->append(['audit_status_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 根据ID获取短视频信息
     * 
     * @param int $id 短视频ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 短视频信息
     */
    public function getVideoShortInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取短视频数量
     * 
     * @param array $condition 查询条件
     * @return int 短视频数量
     */
    public function getVideoShortCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取短视频列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoShortColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 短视频统计数据自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setVideoShortInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }

    /**
     * 短视频统计数据自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setVideoShortDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
