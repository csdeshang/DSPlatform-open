<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreCouponModel;

/**
 * 店铺优惠券数据访问对象
 * 
 * 负责店铺优惠券的数据库交互操作
 */
class tblStoreCouponDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreCouponModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreCouponModel();
    }

    /**
     * 创建店铺优惠券
     * 
     * @param array $data 优惠券数据
     * @return int 新创建的优惠券ID
     */
    public function createStoreCoupon(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除店铺优惠券
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreCoupon(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新店铺优惠券
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateStoreCoupon(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取店铺优惠券列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 优惠券列表
     */
    public function getStoreCouponList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->append(['status_desc', 'claim_type_desc','coupon_type_desc'])
        ->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取店铺优惠券分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getStoreCouponPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->append(['status_desc', 'claim_type_desc','coupon_type_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条店铺优惠券信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 优惠券信息
     */
    public function getStoreCouponInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->append(['status_desc', 'claim_type_desc','coupon_type_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取店铺优惠券信息
     * 
     * @param int $id 优惠券ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 优惠券信息
     */
    public function getStoreCouponInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取店铺优惠券数量
     * 
     * @param array $condition 查询条件
     * @return int 优惠券数量
     */
    public function getStoreCouponCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取店铺优惠券列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreCouponColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    

}
