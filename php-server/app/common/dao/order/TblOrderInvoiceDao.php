<?php

namespace app\common\dao\order;

use app\common\dao\BaseDao;
use app\common\enum\order\TblOrderInvoiceEnum;
use app\common\model\order\TblOrderInvoiceModel;

/**
 * 订单发票申请数据访问对象
 *
 * 负责订单发票申请（tbl_order_invoice）的数据库交互操作
 */
class TblOrderInvoiceDao extends BaseDao
{
    /**
     * 构造函数
     *
     * 初始化 TblOrderInvoiceModel 模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblOrderInvoiceModel();
    }

    /**
     * 创建订单发票申请
     *
     * @param array $data 申请数据
     * @return int 新创建的申请 ID
     */
    public function createOrderInvoice(array $data): int
    {
        $result = $this->model->create($data);
        return (int) $result->id;
    }

    /**
     * 更新订单发票申请
     *
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateOrderInvoice(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 统计指定订单下「进行中」的开票申请数量
     *
     * 用于判断是否允许用户再次提交申请（待处理、处理中、已开票视为占用名额）。
     *
     * @param int $order_id 订单 ID
     * @return int 符合条件的申请条数
     */
    public function countBlockingByOrderId(int $order_id): int
    {
        return (int) $this->model->where('order_id', $order_id)
            ->whereIn('invoice_status', TblOrderInvoiceEnum::blockingInvoiceStatuses())
            ->count();
    }

    /**
     * 获取单条订单发票申请信息（无关联预加载）
     *
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 申请信息
     */
    public function getOrderInvoiceInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取带关联的订单发票申请分页列表
     *
     * 预加载店铺、用户；追加 invoice_status_desc。
     *
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按 ID 降序
     * @return array 分页数据
     */
    public function getWithRelOrderInvoicePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with([
                'store' => function ($query) {
                    $query->field('id,store_name');
                },
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                },
            ])
            ->append(['invoice_status_desc'])
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联的订单发票申请详情
     *
     * 预加载店铺、用户、申请日志列表；追加 invoice_status_desc。
     *
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 申请详情
     */
    public function getWithRelOrderInvoiceInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)
            ->with([
                'store' => function ($query) {
                    $query->field('id,store_name');
                },
                'user' => function ($query) {
                    $query->field('id,username,nickname,avatar');
                },
                'orderInvoiceLogList' => function ($query) {
                    $query->append(['invoice_status_desc'])->order('create_at desc');
                },
            ])
            ->append(['invoice_status_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
    }
}
