<?php

namespace app\common\dao\technician;

use app\common\dao\BaseDao;
use app\common\model\technician\TechnicianGoodsRelModel;

/**
 * 师傅商品关联数据访问对象
 * 
 * 负责师傅与商品关联的数据库交互操作
 */
class TechnicianGoodsRelDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TechnicianGoodsRelModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TechnicianGoodsRelModel();
    }

    /**
     * 创建师傅商品关联
     * 
     * @param array $data 关联数据
     * @return int 新创建的关联ID
     */
    public function createTechnicianGoodsRel(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建师傅商品关联
     * 
     * @param array $dataList 关联数据列表
     * @return bool 是否成功
     */
    public function createTechnicianGoodsRelAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除师傅商品关联
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTechnicianGoodsRel(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新师傅商品关联
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateTechnicianGoodsRel(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }



    /**
     * 获取师傅商品关联列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 师傅商品关联列表
     */
    public function getTechnicianGoodsRelList(array $condition, string $field = '*', string $order = 'id desc', $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }


    /**
     * 获取师傅商品关联信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 师傅商品关联信息
     */
    public function getTechnicianGoodsRelInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取师傅商品关联数量
     * 
     * @param array $condition 查询条件
     * @return int 关联数量
     */
    public function getTechnicianGoodsRelCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取师傅商品关联列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getTechnicianGoodsRelColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
