import request, { API_BASE_URLS } from '@/utils/request'


export function getDistributorBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/distributor/balance-log/pages`, { params })
}


export function modifyDistributorBalance(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/distributor/balance/modifyDistributorBalance`,  params )
}


