<?php

namespace app\deshang\service\order;

use app\common\dao\order\TblOrderDao;
use app\common\dao\order\TblOrderInvoiceDao;
use app\common\dao\order\TblOrderInvoiceLogDao;
use app\common\enum\order\TblOrderEnum;
use app\common\enum\order\TblOrderInvoiceEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\NotFoundException;
use app\deshang\exceptions\PermissionException;
use app\deshang\service\BaseDeshangService;

/**
 * 订单开票申请领域服务
 *
 * 店铺侧「标记处理中 / 驳回 / 完成开票 / 作废」等多表写入由调用；
 * 用户申请开票仍由 API 服务层事务 + 订单行锁后调用 applyInvoice。
 */
class DeshangTblOrderInvoiceService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 用户提交开票申请（调用方已开启事务并对订单加锁；每次新增一条 tbl_order_invoice）
     *
     * @param array $order_info 订单行（含 platform 等）
     * @param array $data 校验后的开票快照字段 + order_id
     * @param int $user_id 当前用户
     * @return array{id:int}
     */
    public function applyInvoice(array $order_info, array $data, int $user_id): array
    {
        $actions = (new DeshangTblOrderService())->getUserAvailableActions($order_info);
        if (!in_array('invoice', $actions, true)) {
            throw new PermissionException('当前订单不可申请开票');
        }

        if ((int) $order_info['user_id'] !== (int) $user_id) {
            throw new CommonException('订单不属于当前用户');
        }

        if ((int) $order_info['order_status'] !== TblOrderEnum::ORDER_STATUS_COMPLETED) {
            throw new CommonException('仅已完成订单可申请开票');
        }

        if ((int) $order_info['refunding_count'] !== 0) {
            throw new CommonException('存在退款处理中，暂不可申请开票');
        }

        if ((int) $order_info['refund_status'] === TblOrderEnum::REFUND_STATUS_FULL_REFUNDED) {
            throw new CommonException('订单已全额退款，不可申请开票');
        }

        $orderId = (int) $order_info['id'];
        $blocking = (new TblOrderInvoiceDao())->countBlockingByOrderId($orderId);
        if ($blocking > 0) {
            throw new CommonException('该订单已有进行中的开票申请');
        }

        $pay = (string) ($order_info['pay_amount'] ?? '0');
        $refund = (string) ($order_info['refund_amount'] ?? '0');
        $invoice_amount = bcsub($pay, $refund, 4);
        if (bccomp($invoice_amount, '0', 4) !== 1) {
            throw new CommonException('可开票金额无效');
        }

        $now = TIMESTAMP;
        $invoice_row = [
            'platform' => $order_info['platform'],
            'order_id' => $orderId,
            'order_sn' => (string) $order_info['order_sn'],
            'user_id' => (int) $user_id,
            'merchant_id' => (int) ($order_info['merchant_id'] ?? 0),
            'store_id' => (int) $order_info['store_id'],
            'invoice_type' => (int) $data['invoice_type'],
            'invoice_kind' => (int) $data['invoice_kind'],
            'invoice_title' => (string) $data['invoice_title'],
            'tax_number' => (string) ($data['tax_number'] ?? ''),
            'register_address' => $data['register_address'] ?? null,
            'register_phone' => $data['register_phone'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account' => $data['bank_account'] ?? null,
            'invoice_amount' => $invoice_amount,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_PENDING,
            'apply_remark' => $data['apply_remark'] ?? null,
            'receiver_email' => $data['receiver_email'] ?? null,
            'receiver_mobile' => $data['receiver_mobile'] ?? null,
            'reject_reason' => null,
            'reject_time' => 0,
            'void_reason' => null,
            'void_time' => 0,
            'issue_time' => 0,
            'issue_remark' => null,
            'invoice_file_url' => null,
            'invoice_no' => null,
            'out_invoice_no' => null,
            'create_at' => $now,
            'update_at' => $now,
        ];

        $invoice_id = (new TblOrderInvoiceDao())->createOrderInvoice($invoice_row);

        (new TblOrderInvoiceLogDao())->createOrderInvoiceLog([
            'invoice_id' => $invoice_id,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_PENDING,
            'message' => '用户提交开票申请',
            'create_role' => 'user',
            'create_uid' => $user_id,
            'create_at' => $now,
            'extra' => null,
        ]);

        (new TblOrderDao())->updateOrder(
            [['id', '=', $orderId]],
            ['invoice_status' => TblOrderInvoiceEnum::STATUS_PENDING]
        );

        return ['id' => $invoice_id];
    }

    /**
     * 店铺端当前可执行的操作标识（与前端按钮、详情 store_invoice_actions 一致）
     *
     * - mark_processing：仅「待处理」
     * - reject / issue：待处理或处理中
     * - void：仅「已开票」（红冲/误开等，作废后订单 invoice_status 同步为已作废，用户可再次申请）
     *
     * @return string[] 如 mark_processing、reject、issue、void
     */
    public static function getStoreInvoiceActions($invoiceStatus): array
    {
        $st = (int) $invoiceStatus;

        if ($st === TblOrderInvoiceEnum::STATUS_PENDING) {
            return ['mark_processing', 'reject', 'issue'];
        }
        if ($st === TblOrderInvoiceEnum::STATUS_PROCESSING) {
            return ['reject', 'issue'];
        }
        if ($st === TblOrderInvoiceEnum::STATUS_ISSUED) {
            return ['void'];
        }

        return [];
    }

    /**
     * 店铺：待处理 → 处理中
     *
     * 同步 tbl_order.invoice_status 为「处理中」，并写 tbl_order_invoice_log（create_role=store）。
     *
     * @param int $invoiceId 申请单主键
     * @param int $storeId 当前店铺 ID
     * @param int $operatorUserId 操作人用户 ID（写入日志 create_uid）
     */
    public function storeMarkProcessing(int $invoiceId, int $storeId, int $operatorUserId): void
    {
        $invDao = new TblOrderInvoiceDao();
        $invoice = $invDao->getOrderInvoiceInfo([['id', '=', $invoiceId], ['store_id', '=', $storeId]], '*', true);
        if (empty($invoice['id'])) {
            throw new NotFoundException('开票申请不存在');
        }
        if ((int) $invoice['invoice_status'] !== TblOrderInvoiceEnum::STATUS_PENDING) {
            throw new CommonException('仅「待处理」可标记为处理中');
        }
        $orderId = (int) $invoice['order_id'];
        $this->assertOrderBelongsToStore($orderId, $storeId);
        $now = TIMESTAMP;
        $invDao->updateOrderInvoice([['id', '=', $invoiceId], ['store_id', '=', $storeId]], [
            'invoice_status' => TblOrderInvoiceEnum::STATUS_PROCESSING,
            'update_at' => $now,
        ]);
        (new TblOrderInvoiceLogDao())->createOrderInvoiceLog([
            'invoice_id' => $invoiceId,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_PROCESSING,
            'message' => '店铺标记为处理中',
            'create_role' => 'store',
            'create_uid' => $operatorUserId,
            'create_at' => $now,
            'extra' => null,
        ]);
        (new TblOrderDao())->updateOrder(
            [['id', '=', $orderId]],
            ['invoice_status' => TblOrderInvoiceEnum::STATUS_PROCESSING]
        );
    }

    /**
     * 店铺：待处理或处理中 → 已驳回
     *
     * 写入 reject_reason、reject_time；同步订单 invoice_status 为「已驳回」，便于买家端再次发起申请。
     */
    public function storeReject(int $invoiceId, int $storeId, int $operatorUserId, string $rejectReason): void
    {
        $rejectReason = trim($rejectReason);
        if ($rejectReason === '') {
            throw new CommonException('请填写驳回原因');
        }
        $invDao = new TblOrderInvoiceDao();
        $invoice = $invDao->getOrderInvoiceInfo([['id', '=', $invoiceId], ['store_id', '=', $storeId]], '*', true);
        if (empty($invoice['id'])) {
            throw new NotFoundException('开票申请不存在');
        }
        $st = (int) $invoice['invoice_status'];
        if ($st !== TblOrderInvoiceEnum::STATUS_PENDING && $st !== TblOrderInvoiceEnum::STATUS_PROCESSING) {
            throw new CommonException('当前状态不可驳回');
        }
        $orderId = (int) $invoice['order_id'];
        $this->assertOrderBelongsToStore($orderId, $storeId);
        $now = TIMESTAMP;
        $invDao->updateOrderInvoice([['id', '=', $invoiceId], ['store_id', '=', $storeId]], [
            'invoice_status' => TblOrderInvoiceEnum::STATUS_REJECTED,
            'reject_reason' => $rejectReason,
            'reject_time' => $now,
            'update_at' => $now,
        ]);
        (new TblOrderInvoiceLogDao())->createOrderInvoiceLog([
            'invoice_id' => $invoiceId,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_REJECTED,
            'message' => '店铺驳回：' . (function_exists('mb_substr') ? mb_substr($rejectReason, 0, 200) : substr($rejectReason, 0, 200)),
            'create_role' => 'store',
            'create_uid' => $operatorUserId,
            'create_at' => $now,
            'extra' => null,
        ]);
        (new TblOrderDao())->updateOrder(
            [['id', '=', $orderId]],
            ['invoice_status' => TblOrderInvoiceEnum::STATUS_REJECTED]
        );
    }

    /**
     * 店铺：待处理或处理中 → 已开票
     *
     * 写入发票号码、可选第三方流水号与电子票下载地址、issue_time；同步订单 invoice_status 为「已开票」。
     *
     * @param array{invoice_no:string,out_invoice_no?:string,invoice_file_url?:string,issue_remark?:string} $payload
     */
    public function storeIssue(int $invoiceId, int $storeId, int $operatorUserId, array $payload): void
    {
        $invoiceNo = trim((string) ($payload['invoice_no'] ?? ''));
        if ($invoiceNo === '') {
            throw new CommonException('请填写发票号码');
        }
        $invDao = new TblOrderInvoiceDao();
        $invoice = $invDao->getOrderInvoiceInfo([['id', '=', $invoiceId], ['store_id', '=', $storeId]], '*', true);
        if (empty($invoice['id'])) {
            throw new NotFoundException('开票申请不存在');
        }
        $st = (int) $invoice['invoice_status'];
        if ($st !== TblOrderInvoiceEnum::STATUS_PENDING && $st !== TblOrderInvoiceEnum::STATUS_PROCESSING) {
            throw new CommonException('当前状态不可完成开票');
        }
        $orderId = (int) $invoice['order_id'];
        $this->assertOrderBelongsToStore($orderId, $storeId);
        $now = TIMESTAMP;
        $outNo = isset($payload['out_invoice_no']) ? trim((string) $payload['out_invoice_no']) : '';
        $fileUrl = isset($payload['invoice_file_url']) ? trim((string) $payload['invoice_file_url']) : '';
        $issueRemark = isset($payload['issue_remark']) ? trim((string) $payload['issue_remark']) : '';
        $invDao->updateOrderInvoice([['id', '=', $invoiceId], ['store_id', '=', $storeId]], [
            'invoice_status' => TblOrderInvoiceEnum::STATUS_ISSUED,
            'invoice_no' => $invoiceNo,
            'out_invoice_no' => $outNo !== '' ? $outNo : null,
            'invoice_file_url' => $fileUrl !== '' ? $fileUrl : null,
            'issue_remark' => $issueRemark !== '' ? $issueRemark : null,
            'issue_time' => $now,
            'update_at' => $now,
        ]);
        (new TblOrderInvoiceLogDao())->createOrderInvoiceLog([
            'invoice_id' => $invoiceId,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_ISSUED,
            'message' => '店铺完成开票',
            'create_role' => 'store',
            'create_uid' => $operatorUserId,
            'create_at' => $now,
            'extra' => null,
        ]);
        (new TblOrderDao())->updateOrder(
            [['id', '=', $orderId]],
            ['invoice_status' => TblOrderInvoiceEnum::STATUS_ISSUED]
        );
    }

    /**
     * 店铺：已开票 → 已作废
     *
     * 写入 void_reason、void_time；同步订单 invoice_status 为「已作废」，用户可再次发起开票申请。
     */
    public function storeVoid(int $invoiceId, int $storeId, int $operatorUserId, string $voidReason): void
    {
        $voidReason = trim($voidReason);
        if ($voidReason === '') {
            throw new CommonException('请填写作废原因');
        }
        $invDao = new TblOrderInvoiceDao();
        $invoice = $invDao->getOrderInvoiceInfo([['id', '=', $invoiceId], ['store_id', '=', $storeId]], '*', true);
        if (empty($invoice['id'])) {
            throw new NotFoundException('开票申请不存在');
        }
        if ((int) $invoice['invoice_status'] !== TblOrderInvoiceEnum::STATUS_ISSUED) {
            throw new CommonException('仅「已开票」可申请作废');
        }
        $orderId = (int) $invoice['order_id'];
        $this->assertOrderBelongsToStore($orderId, $storeId);
        $now = TIMESTAMP;
        $invDao->updateOrderInvoice([['id', '=', $invoiceId], ['store_id', '=', $storeId]], [
            'invoice_status' => TblOrderInvoiceEnum::STATUS_VOIDED,
            'void_reason' => $voidReason,
            'void_time' => $now,
            'update_at' => $now,
        ]);
        (new TblOrderInvoiceLogDao())->createOrderInvoiceLog([
            'invoice_id' => $invoiceId,
            'invoice_status' => TblOrderInvoiceEnum::STATUS_VOIDED,
            'message' => '店铺作废开票',
            'create_role' => 'store',
            'create_uid' => $operatorUserId,
            'create_at' => $now,
            'extra' => null,
        ]);
        (new TblOrderDao())->updateOrder(
            [['id', '=', $orderId]],
            ['invoice_status' => TblOrderInvoiceEnum::STATUS_VOIDED]
        );
    }

    /**
     * 二次校验订单归属店铺（防数据不一致或越权）
     */
    private function assertOrderBelongsToStore(int $orderId, int $storeId): void
    {
        $order = (new TblOrderDao())->getOrderInfo([['id', '=', $orderId]], 'id,store_id', true);
        if (empty($order['id'])) {
            throw new CommonException('订单不存在');
        }
        if ((int) $order['store_id'] !== (int) $storeId) {
            throw new PermissionException('订单不属于当前店铺');
        }
    }
}
