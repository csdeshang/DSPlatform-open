import request, { API_BASE_URLS } from '@/utils/request'


export function getTradePayLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/trade/pay-log/pages`, { params })
}