<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\model\order\TblOrderModel;
use app\common\enum\order\TblOrderEnum;

/**
 * 订单数据访问对象
 * 
 * 负责订单的数据库交互操作
 */
class TblOrderDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblOrderModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderModel();
    }


    /**
     * 检查用户是否已购买商品
     * 
     * @param int $user_id 用户ID
     * @param int $goods_id 商品ID
     * @return array 订单信息
     */
    public function findUserPurchased(int $user_id, int $goods_id)
    {
        // 通过订单商品表关联查询，检查是否有已支付的订单
        $result = $this->model->alias('o')
            ->join('tbl_order_goods og', 'o.id = og.order_id')
            ->where('o.user_id', $user_id)
            ->where('og.goods_id', $goods_id)
            ->whereIn('o.order_status', [TblOrderEnum::ORDER_STATUS_PAID, TblOrderEnum::ORDER_STATUS_ACCEPTED, TblOrderEnum::ORDER_STATUS_CONFIRMED, TblOrderEnum::ORDER_STATUS_COMPLETED])
            ->where('o.is_deleted', 0)
            ->field('o.*, og.goods_id, og.goods_name')
            ->findOrEmpty()
            ->toArray();
        return $result;
    }


    /**
     * 创建订单
     * 
     * @param array $data 订单数据
     * @return int 新创建的订单ID
     */
    public function createOrder(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除订单
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteOrder(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新订单
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrder(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取订单列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 订单列表
     */
    public function getOrderList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取订单分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getOrderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的订单分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelOrderPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name,platform,store_latitude,store_longitude');
                    },
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'orderGoodsList' => function ($query) {
                        $query->append(['promotion_type_desc']);
                        // $query->field('id,order_id,goods_id,goods_name,sku_id,sku_name,pay_price,goods_num,goods_image');
                    },
                    'orderDelivery' => function ($query) {
                        $query->append(['delivery_status_desc']);
                    },
                    // 'orderAddress' => function ($query) {
                    //     $query->field('*');
                    // },
                ]
            )
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }



    /**
     * 获取单条订单信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 订单信息
     */
    public function getOrderInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取单条订单信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 订单信息
     */
    public function getWithRelOrderInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
            ->with(
                [
                    'store' => function ($query) {
                        $query->field('id,store_name,platform,store_latitude,store_longitude');
                    },
                    'user' => function ($query) {
                        $query->field('id,username,nickname,avatar');
                    },
                    'orderGoodsList' => function ($query) {
                        $query->append(['promotion_type_desc']);
                    },
                    'orderAddress' => function ($query) {
                        $query->field('*');
                    },
                    'orderLogList' => function ($query) {
                        $query->field('id,order_id,order_status,message,create_role,create_by,create_at')->order('create_at desc');
                    },
                    'payMerchant' => function ($query) {
                        $query->field('id,name,contact_name,contact_phone');
                    },
                    'orderRefundList' => function ($query) {
                        $query->append(['refund_type_desc', 'refund_status_desc', 'refund_method_desc']);
                    },
                    'orderDelivery' => function ($query) {
                        $query->append(['delivery_status_desc']);
                    },
                    'orderFinance' => function ($query) {
                        $query->field('*');
                    },
                ]
            )
            ->append(['order_status_desc', 'delivery_method_desc'])
            ->field($field)
            ->lock($lock)
            ->findOrEmpty()
            ->toArray();
    }


    /**
     * 根据ID获取订单信息
     * 
     * @param int $id 订单ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 订单信息
     */
    public function getOrderInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取订单数量
     * 
     * @param array $condition 查询条件
     * @return int 订单数量
     */
    public function getOrderCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取订单列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }


    /**
     * 获取订单列求和
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getOrderColumnSum(array $condition, string $column)
    {
        return $this->model->where($condition)->sum($column);
    }

    /**
     * 自增
     * 
     * @param array $condition 查询条件
     * @param string $field 字段名
     * @param int $step 步长
     * @return bool 是否更新成功
     */
    public function setOrderInc(array $condition, string $field, int $step = 1): bool
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
    public function setOrderDec(array $condition, string $field, int $step = 1): bool
    {
        return $this->model->where($condition)->setDec($field, $step);
    }
}
