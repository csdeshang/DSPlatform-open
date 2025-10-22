<?php

namespace app\common\dao\technician;

use app\common\dao\BaseDao;
use app\common\model\technician\TechnicianCommentModel;

/**
 * 师傅评论数据访问对象
 * 
 * 负责师傅评论的数据库交互操作
 */
class TechnicianCommentDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TechnicianComment模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TechnicianCommentModel();
    }
    
    /**
     * 创建师傅评论
     * 
     * @param array $data 评论数据
     * @return int 新创建的评论ID
     */
    public function createTechnicianComment(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建师傅评论
     * 
     * @param array $dataList 评论数据列表
     * @return bool 是否成功
     */
    public function createTechnicianCommentAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除师傅评论
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTechnicianComment(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 软删除师傅评论
     * 
     * @param array $condition 删除条件
     * @return bool 是否删除成功
     */
    public function softDeleteTechnicianComment(array $condition): bool
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => time()
        ];
        return $this->model->where($condition)->update($data) ? true : false;
    }
    
    /**
     * 更新师傅评论
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateTechnicianComment(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取师傅评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 评论列表
     */
    public function getTechnicianCommentList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取带关联的师傅评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认10条
     * @return array 评论列表
     */
    public function getWithRelTechnicianCommentList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 10): array
    {
        $result = $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'technician' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count,avatar,work_years');
                }
            ])
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select()
            ->toArray();
        return $result;
    }

    /**
     * 获取师傅评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getTechnicianCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的师傅评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getWithRelTechnicianCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'technician' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count,avatar,work_years');
                }
            ])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条师傅评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getTechnicianCommentInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取师傅评论信息
     * 
     * @param int $id 评论ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getTechnicianCommentById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取带关联的单条师傅评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getWithRelTechnicianCommentInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with([
                'user' => function ($query) {
                    $query->field('id,nickname,avatar,username');
                },
                'technician' => function ($query) {
                    $query->field('id,name,mobile,avg_score,comment_count,avatar,work_years');
                },
                'order' => function ($query) {
                    $query->field('id,order_sn,order_status');
                },
            ])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }
    
    /**
     * 获取师傅评论数量
     * 
     * @param array $condition 查询条件
     * @return int 评论数量
     */
    public function getTechnicianCommentCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取师傅评论列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getTechnicianCommentColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


}
