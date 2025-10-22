import request, { API_BASE_URLS } from '@/utils/request'


export function getTradeTransferLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/trade/transfer-log/pages`, { params })
}