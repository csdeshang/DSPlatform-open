import request, { API_BASE_URLS } from '@/utils/request'


export function getMerchantBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/merchant/balance-logs/pages`, { params })
}


export function modifyMerchantBalance(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/merchant/merchants/${params.merchant_id}/balance`, params)
}


