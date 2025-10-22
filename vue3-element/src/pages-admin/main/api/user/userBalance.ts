import request, { API_BASE_URLS } from '@/utils/request'


export function getUserBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/balance-log/pages`, { params })
}


export function modifyUserBalance(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/user/balance/modifyUserBalance`,  params )
}


