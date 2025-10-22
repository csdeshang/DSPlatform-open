<?php

namespace app\common\dao\store;

use app\common\dao\BaseDao;
use app\common\model\store\TblStoreCouponUserModel;

/**
 * 用户店铺优惠券数据访问对象
 * 
 * 负责用户领取的店铺优惠券的数据库交互操作
 */
class tblStoreCouponUserDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblStoreCouponUserModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblStoreCouponUserModel();
    }

    /**
     * 创建用户优惠券
     * 
     * @param array $data 用户优惠券数据
     * @return int 新创建的用户优惠券ID
     */
    public function createStoreCouponUser(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除用户优惠券
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteStoreCouponUser(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新用户优惠券
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateStoreCouponUser(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取用户优惠券列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 用户优惠券列表
     */
    public function getStoreCouponUserList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取用户优惠券分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getStoreCouponUserPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的店铺优惠券分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelStoreCouponUserPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['status_desc'])
            ->with(
                [
                    'storeCoupon' => function ($query) {
                        $query->append(['status_desc', 'claim_type_desc']);
                    },
                ]
            )
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条用户优惠券信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 用户优惠券信息
     */
    public function getStoreCouponUserInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 根据ID获取用户优惠券信息
     * 
     * @param int $id 用户优惠券ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 用户优惠券信息
     */
    public function getStoreCouponUserInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取用户优惠券数量
     * 
     * @param array $condition 查询条件
     * @return int 用户优惠券数量
     */
    public function getStoreCouponUserCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取用户优惠券列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getStoreCouponUserColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
