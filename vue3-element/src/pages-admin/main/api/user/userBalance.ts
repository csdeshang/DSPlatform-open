import request, { API_BASE_URLS } from '@/utils/request'


export function getUserBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/balance-logs/pages`, { params })
}


export function modifyUserBalance(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/user/users/${params.user_id}/balance`, params)
}


