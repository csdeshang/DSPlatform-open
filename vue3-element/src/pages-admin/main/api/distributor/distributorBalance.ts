import request, { API_BASE_URLS } from '@/utils/request'


export function getDistributorBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/distributor/balance-logs/pages`, { params })
}


export function modifyDistributorBalance(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/distributor/distributors/${params.distributor_user_id}/balance`, params)
}


