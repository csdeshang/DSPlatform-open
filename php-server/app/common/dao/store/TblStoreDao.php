<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreModel;

/**
 * 店铺数据访问对象
 * 
 * 负责店铺的数据库交互操作
 */
class TblStoreDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreModel();
    }

    /**
     * 创建店铺
     * 
     * @param array $data 店铺数据
     * @return int 新创建的店铺ID
     */
    public function createStore(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStore(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新店铺
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateStore(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取店铺列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 店铺列表
     */
    public function getStoreList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getStorePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取店铺分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelStorePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'platformInfo' => function ($query) {
                        $query->field('id,name,platform,scene');
                    },
                    'merchant' => function ($query) {
                        $query->field('id,name,contact_name');
                    },
                ]
            )
            ->append(['apply_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }




    /**
     * 获取单条店铺信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 店铺信息
     */
    public function getStoreInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取店铺信息
     * 
     * @param int $id 店铺ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 店铺信息
     */
    public function getStoreInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取店铺数量
     * 
     * @param array $condition 查询条件
     * @return int 店铺数量
     */
    public function getStoreCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取店铺列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreColumn(array $condition, string $column): array
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
    public function setStoreInc(array $condition, string $field, int $step = 1): bool
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
    public function setStoreDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }

    /**
     * 检测店铺名称是否已存在
     * 
     * @param array $condition 查询条件
     * @return bool 是否存在重复
     */
    public function checkStoreNameExists(array $condition): bool
    {
        $store = $this->model->where($condition)->findOrEmpty();
        return !$store->isEmpty();
    }

}
