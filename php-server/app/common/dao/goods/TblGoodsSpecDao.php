<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsSpecModel;

/**
 * 商品规格数据访问对象
 * 
 * 负责商品规格的数据库交互操作
 */
class TblGoodsSpecDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsSpecModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsSpecModel();
    }
    
    /**
     * 创建商品规格
     * 
     * @param array $data 规格数据
     * @return int 新创建的规格ID
     */
    public function createGoodsSpec(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品规格
     * 
     * @param array $dataList 规格数据列表
     * @return bool 是否成功
     */
    public function createGoodsSpecAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品规格
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsSpec(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品规格
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateGoodsSpec(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取商品规格列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array 规格列表
     */
    public function getGoodsSpecList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }


    /**
     * 获取单条商品规格信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 规格信息
     */
    public function getGoodsSpecInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品规格信息
     * 
     * @param int $id 规格ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 规格信息
     */
    public function getGoodsSpecInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品规格数量
     * 
     * @param array $condition 查询条件
     * @return int 规格数量
     */
    public function getGoodsSpecCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品规格列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsSpecColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    
}
