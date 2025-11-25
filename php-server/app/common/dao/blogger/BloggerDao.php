<?php

namespace app\common\dao\blogger;

use app\common\dao\BaseDao;
use app\common\model\blogger\BloggerModel;

/**
 * 视频博主数据访问对象
 * 
 * 负责博主的数据库交互操作
 */
class BloggerDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化BloggerModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new BloggerModel();
    }
    
    /**
     * 创建博主
     * 
     * @param array $data 博主数据
     * @return int 新创建的博主ID
     */
    public function createBlogger(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除博主
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteBlogger(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新博主
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateBlogger(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取博主列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 博主列表
     */
    public function getBloggerList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->append(['verification_status_desc', 'verification_type_desc'])
        ->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取博主分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getBloggerPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['verification_status_desc', 'verification_type_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联数据的博主分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelBloggerPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->with(
            [
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                }
            ]
        )
        ->append(['verification_status_desc', 'verification_type_desc'])
        ->field($field)
        ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条博主信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 博主信息
     */
    public function getBloggerInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->append(['verification_status_desc', 'verification_type_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取博主信息
     * 
     * @param int $id 博主ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 博主信息
     */
    public function getBloggerInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据用户ID获取博主信息
     * 
     * @param int $userId 用户ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 博主信息
     */
    public function getBloggerInfoByUserId(int $userId, string $field = '*'): array
    {
        return $this->model->where('user_id', $userId)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取博主数量
     * 
     * @param array $condition 查询条件
     * @return int 博主数量
     */
    public function getBloggerCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取博主列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getBloggerColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
    /**
     * 博主统计数据自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setBloggerInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }

    /**
     * 博主统计数据自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setBloggerDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }




}
