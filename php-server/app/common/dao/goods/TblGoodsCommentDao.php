<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsCommentModel;

/**
 * 商品评论数据访问对象
 * 
 * 负责商品评论的数据库交互操作
 */
class TblGoodsCommentDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsCommentModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsCommentModel();
    }
    
    /**
     * 创建商品评论
     * 
     * @param array $data 评论数据
     * @return int 新创建的评论ID
     */
    public function createGoodsComment(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }
    /**
     * 批量创建商品评论
     * 
     * @param array $dataList 评论数据列表
     * @return bool 是否成功
     */
    public function createGoodsCommentAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品评论
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsComment(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品评论
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsComment(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 评论列表
     */
    public function getGoodsCommentList(array $condition, string $field = '*', string $order = 'create_at desc' , int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取带关联的商品评论列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 评论列表
     */
    public function getWithRelGoodsCommentList(array $condition, string $field = '*', string $order = 'create_at desc' , int $limit = 10): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'goods' => function ($query) {
                        $query->field('id,goods_name,cover_image');
                    },
                ]
            )
            ->field($field)
            ->order($order)
            ->limit($limit)
            ->select()
            ->toArray();
        return $result;
    }



    /**
     * 获取商品评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getGoodsCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取带关联的商品评论分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getWithRelGoodsCommentPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'goods' => function ($query) {
                        $query->field('id,platform,goods_name,cover_image');
                    },
                ]
            )
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条商品评论信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 评论信息
     */
    public function getGoodsCommentInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品评论信息
     * 
     * @param int $id 评论ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 评论信息
     */
    public function getGoodsCommentById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品评论数量
     * 
     * @param array $condition 查询条件
     * @return int 评论数量
     */
    public function getGoodsCommentCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品评论列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsCommentColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
