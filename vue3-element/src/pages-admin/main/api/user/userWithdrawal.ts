import request, { API_BASE_URLS } from '@/utils/request'


export function getUserWithdrawalLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/withdrawal-logs/pages`, { params })
}

export function getUserWithdrawalLogInfo(id: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/withdrawal-logs/${id}`)
}


export function operationUserWithdrawalLog(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/user/withdrawal-logs/${params.id}/operation`,  params)
}






