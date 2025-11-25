<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsBrandModel;

/**
 * 商品品牌数据访问对象
 * 
 * 负责商品品牌的数据库交互操作
 */
class TblGoodsBrandDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsBrandModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsBrandModel();
    }
    
    /**
     * 创建商品品牌
     * 
     * @param array $data 品牌数据
     * @return int 新创建的品牌ID
     */
    public function createGoodsBrand(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除商品品牌
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsBrand(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品品牌
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsBrand(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品品牌列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 品牌列表
     */
    public function getGoodsBrandList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品品牌分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getGoodsBrandPages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品品牌信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 品牌信息
     */
    public function getGoodsBrandInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品品牌信息
     * 
     * @param int $id 品牌ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 品牌信息
     */
    public function getGoodsBrandById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品品牌数量
     * 
     * @param array $condition 查询条件
     * @return int 品牌数量
     */
    public function getGoodsBrandCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品品牌列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsBrandColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    

}
