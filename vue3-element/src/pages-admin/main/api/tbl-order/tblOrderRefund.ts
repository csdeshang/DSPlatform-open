import request, { API_BASE_URLS } from '@/utils/request';


// 获取退货退款列表
export function getTblOrderRefundPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/refund/pages`, { params })
}


// 获取订单退款记录列表
export function getTblOrderRefundList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/refund/list`, { params })
}


// 获取订单退款记录详情
export function getTblOrderRefundInfo(refundId: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/refund/info/${refundId}`)
}


// 重新发起退款， 当退款状态为 60 或者 80 时，可以重新发起退款， 表示订单相关的处理已完成，但是金额未退款
export function retryTblOrderRefund(refundId: number) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-order/refund/retry/${refundId}`)
}



// 获取订单退款操作记录列表
export function getTblOrderRefundLogList(refundId: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/refund-log/list/${refundId}`)
}





