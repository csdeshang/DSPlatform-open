import request, { API_BASE_URLS } from '@/utils/request'


export function getTradeRefundLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/trade/refund-log/pages`, { params })
}