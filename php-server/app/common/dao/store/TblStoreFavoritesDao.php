<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreFavoritesModel;

/**
 * 店铺收藏数据访问对象
 * 
 * 负责店铺收藏的数据库交互操作
 */
class TblStoreFavoritesDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreFavoritesModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreFavoritesModel();
    }
    
    /**
     * 创建店铺收藏
     * 
     * @param array $data 收藏数据
     * @return int 新创建的收藏ID
     */
    public function createStoreFavorites(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建店铺收藏
     * 
     * @param array $dataList 收藏数据列表
     * @return bool 是否成功
     */
    public function createStoreFavoritesAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除店铺收藏
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreFavorites(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新店铺收藏
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateStoreFavorites(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取店铺收藏列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 收藏列表
     */
    public function getStoreFavoritesList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 100): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取带关联的店铺收藏列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 收藏列表
     */
    public function getWithRelStoreFavoritesList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 100): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name,store_logo,sales_num,collect_num');
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
     * 获取店铺收藏分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getStoreFavoritesPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的店铺收藏分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getWithRelStoreFavoritesPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name,store_logo,sales_num,collect_num');
                    },
                ]
            )
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条店铺收藏信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 收藏信息
     */
    public function getStoreFavoritesInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取店铺收藏信息
     * 
     * @param int $id 收藏ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 收藏信息
     */
    public function getStoreFavoritesById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取店铺收藏数量
     * 
     * @param array $condition 查询条件
     * @return int 收藏数量
     */
    public function getStoreFavoritesCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取店铺收藏列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreFavoritesColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
