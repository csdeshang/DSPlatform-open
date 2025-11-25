<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsCategoryRelModel;

/**
 * 商品分类关联数据访问对象
 * 
 * 负责商品与分类关联的数据库交互操作
 */
class TblGoodsCategoryRelDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsCategoryRelModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsCategoryRelModel();
    }
    
    /**
     * 创建商品分类关联
     * 
     * @param array $data 关联数据
     * @return int 新创建的关联ID
     */
    public function createGoodsCategoryRel(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }
    
    /**
     * 批量创建商品分类关联
     * 
     * @param array $dataList 关联数据列表
     * @return bool 是否成功
     */
    public function createGoodsCategoryRelAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品分类关联
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsCategoryRel(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品分类关联
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsCategoryRel(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }


    /**
     * 获取商品分类关联数量
     * 
     * @param array $condition 查询条件
     * @return int 关联数量
     */
    public function getGoodsCategoryRelCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品分类关联列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsCategoryRelColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
