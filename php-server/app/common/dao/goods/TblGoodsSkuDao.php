<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsSkuModel;

/**
 * 商品SKU数据访问对象
 * 
 * 负责商品SKU的数据库交互操作
 */
class TblGoodsSkuDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsSkuModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsSkuModel();
    }

    /**
     * 创建商品SKU
     * 
     * @param array $data SKU数据
     * @return int 新创建的SKU ID
     */
    public function createGoodsSku(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品SKU
     * 
     * @param array $dataList SKU数据列表
     * @return bool 是否成功
     */
    public function createGoodsSkuAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品SKU
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsSku(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新商品SKU
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateGoodsSku(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取商品SKU列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array SKU列表
     */
    public function getGoodsSkuList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品SKU列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID升序
     * @return array SKU列表
     */
    public function getWithRelGoodsSkuList(array $condition, string $field = '*', string $order = 'id asc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name,cover_image');
                    },
                ]
            )
            ->field($field)
            ->order($order)
            ->select()
            ->toArray();
        return $result;
    }


    /**
     * 获取单条商品SKU信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array SKU信息
     */
    public function getGoodsSkuInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取商品SKU信息
     * 
     * @param int $id SKU ID
     * @param string $field 查询字段，默认为所有字段
     * @return array SKU信息
     */
    public function getGoodsSkuInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取商品SKU数量
     * 
     * @param array $condition 查询条件
     * @return int SKU数量
     */
    public function getGoodsSkuCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取商品SKU列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsSkuColumn(array $condition, string $column): array
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
    public function setGoodsSkuInc(array $condition, string $field, int $step = 1): bool
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
    public function setGoodsSkuDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
