<?php

namespace app\common\dao\system;

use app\common\dao\BaseDao;
use app\common\model\system\SysArticleModel;

/**
 * 文章数据访问对象
 * 
 * 负责文章的数据库交互操作
 */
class SysArticleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化ArticleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new SysArticleModel();
    }
    
    /**
     * 创建文章
     * 
     * @param array $data 文章数据
     * @return int 新创建的文章ID
     */
    public function createSysArticle(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除文章
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteSysArticle(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新文章
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateSysArticle(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取文章列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @param int $limit 限制条数，默认10条
     * @return array 文章列表
     */
    public function getSysArticleList(array $condition, string $field = '*', string $order = 'id asc', int $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取文章分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getSysArticlePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条文章信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 文章信息
     */
    public function getSysArticleInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取文章数量
     * 
     * @param array $condition 查询条件
     * @return int 文章数量
     */
    public function getSysArticleCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取文章列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getSysArticleColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setSysArticleInc(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setInc($field, $step);
    }


    /**
     * 自减
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setSysArticleDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }


}
