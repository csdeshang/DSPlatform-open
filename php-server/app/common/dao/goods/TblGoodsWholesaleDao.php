<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsWholesaleModel;

/**
 * 商品批发价格阶梯数据访问对象
 * 
 * 负责商品批发价格阶梯的数据库交互操作
 */
class TblGoodsWholesaleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsWholesaleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsWholesaleModel();
    }
    
    /**
     * 创建商品批发价格阶梯
     * 
     * @param array $data 批发价格数据
     * @return int 新创建的批发价格ID
     */
    public function createGoodsWholesale(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品批发价格阶梯
     * 
     * @param array $dataList 批发价格数据列表
     * @return bool 是否成功
     */
    public function createGoodsWholesaleAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品批发价格
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsWholesale(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品批发价格
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateGoodsWholesale(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取商品批发价格列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按最小数量升序
     * @return array 批发价格列表
     */
    public function getGoodsWholesaleList(array $condition, string $field = '*', string $order = 'quantity_min asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品批发价格分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按最小数量升序
     * @return array 分页数据
     */
    public function getGoodsWholesalePages(array $condition, string $field = '*', string $order = 'quantity_min asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品批发价格信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认为空
     * @return array 批发价格信息
     */
    public function getGoodsWholesaleInfo(array $condition, string $field = '*', string $order = ''): array
    {
        $query = $this->model->where($condition)->field($field);
        if (!empty($order)) {
            $query = $query->order($order);
        }
        return $query->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品批发价格信息
     * 
     * @param int $id 批发价格ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 批发价格信息
     */
    public function getGoodsWholesaleById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品批发价格数量
     * 
     * @param array $condition 查询条件
     * @return int 批发价格数量
     */
    public function getGoodsWholesaleCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品批发价格列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsWholesaleColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
} 