import request, { API_BASE_URLS } from '@/utils/request'


export function getMerchantBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/merchant/balance-log/pages`, { params })
}


export function modifyMerchantBalance(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/merchant/balance/modifyMerchantBalance`,  params )
}


