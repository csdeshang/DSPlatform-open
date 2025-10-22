<?php

namespace app\common\dao\rider;

use app\common\dao\BaseDao;
use app\common\model\rider\RiderCommentModel;

/**
 * 骑手评论数据访问对象
 * 
 * 负责骑手评论的数据库交互操作
 */
class RiderCommentDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化RiderComment模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new RiderCommentModel();
    }
    
    /**
     * 创建骑手评论
     * 
     * @param array $data 评论数据
     * @return int 新创建的评论ID
     */
    public function createRiderComment(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建骑手评论
     * 
     * @param array $dataList 评论数据列表
     * @return bool 是否成功
     */
    public function createRiderCommentAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除骑手评论
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteRiderComment(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 软删除骑手评论
     * 
     * @param array $condition 删除条件
     * @return bool 是否删除成功
     */
    public function softDeleteRiderComment(array $condition): bool
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => time()
        ];
        return $this->model->where($condition)->update($data) ? true : false;
    }
    
    /**
     * 更新骑手评论
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateRiderComment(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取骑手评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 评论列表
     */
    public function getRiderCommentList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取带关联的骑手评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 评论列表
     */
    public function getWithRelRiderCommentList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 10): array
    {
        $result = $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'rider' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count');
                },
            ])
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select()
            ->toArray();
        return $result;
    }

    /**
     * 获取骑手评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getRiderCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的骑手评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getWithRelRiderCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'rider' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count');
                },
            ])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条骑手评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getRiderCommentInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取骑手评论信息
     * 
     * @param int $id 评论ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getRiderCommentById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取带关联的单条骑手评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getWithRelRiderCommentInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'rider' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count');
                },
                'order' => function ($query) {
                    $query->field('id,order_sn,order_status');
                }
            ])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }
    
    /**
     * 获取骑手评论数量
     * 
     * @param array $condition 查询条件
     * @return int 评论数量
     */
    public function getRiderCommentCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取骑手评论列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getRiderCommentColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


}
