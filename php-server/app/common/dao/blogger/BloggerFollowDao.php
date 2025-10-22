<?php

namespace app\common\dao\blogger;

use app\common\dao\BaseDao;
use app\common\model\blogger\BloggerFollowModel;

/**
 * 博主关注数据访问对象
 * 
 * 负责博主关注的数据库交互操作
 */
class BloggerFollowDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化BloggerFollowModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new BloggerFollowModel();
    }

    /**
     * 创建博主关注
     * 
     * @param array $data 关注数据
     * @return int 新创建的关注ID
     */
    public function createBloggerFollow(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除博主关注
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteBloggerFollow(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 获取博主关注列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 关注列表
     */
    public function getBloggerFollowList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取博主关注分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getBloggerFollowPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联数据的博主关注分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelBloggerFollowPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with([
                'blogger' => function ($query) {
                    $query->field('id,blogger_name,avatar,follower_count');
                },
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                }
            ])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条博主关注信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 关注信息
     */
    public function getBloggerFollowInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取带关联数据的单条博主关注信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 关注信息
     */
    public function getWithRelBloggerFollowInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with([
                'blogger' => function ($query) {
                    $query->field('id,blogger_name,avatar,follower_count');
                },
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                }
            ])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }

    /**
     * 获取博主关注数量
     * 
     * @param array $condition 查询条件
     * @return int 关注数量
     */
    public function getBloggerFollowCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取博主关注列（特定字段）
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getBloggerFollowColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

   



}
