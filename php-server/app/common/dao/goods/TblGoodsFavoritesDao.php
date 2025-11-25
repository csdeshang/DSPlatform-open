<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsFavoritesModel;

/**
 * 商品收藏数据访问对象
 * 
 * 负责商品收藏的数据库交互操作
 */
class TblGoodsFavoritesDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsFavoritesModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsFavoritesModel();
    }
    
    /**
     * 创建商品收藏
     * 
     * @param array $data 收藏数据
     * @return int 新创建的收藏ID
     */
    public function createGoodsFavorites(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品收藏
     * 
     * @param array $dataList 收藏数据列表
     * @return bool 是否成功
     */
    public function createGoodsFavoritesAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品收藏
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsFavorites(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品收藏
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsFavorites(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品收藏列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 收藏列表
     */
    public function getGoodsFavoritesList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 100): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }

    /**
     * 获取带关联的商品收藏列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @param int $limit 限制条数，默认不限制
     * @return array 收藏列表
     */
    public function getWithRelGoodsFavoritesList(array $condition, string $field = '*', string $order = 'create_at desc', int $limit = 100): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name,cover_image,goods_minprice');
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
     * 获取商品收藏分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getGoodsFavoritesPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的商品收藏分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getWithRelGoodsFavoritesPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name,cover_image,goods_minprice,collect_num');
                    },
                ]
            )
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品收藏信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 收藏信息
     */
    public function getGoodsFavoritesInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品收藏信息
     * 
     * @param int $id 收藏ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 收藏信息
     */
    public function getGoodsFavoritesById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品收藏数量
     * 
     * @param array $condition 查询条件
     * @return int 收藏数量
     */
    public function getGoodsFavoritesCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品收藏列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsFavoritesColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
