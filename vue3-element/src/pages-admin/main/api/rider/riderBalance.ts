import request, { API_BASE_URLS } from '@/utils/request'


// 骑手余额日志
export function getRiderBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/rider/balance-logs/pages`, { params })
}

// 骑手余额调整
export function modifyRiderBalance(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/rider/riders/${params.rider_id}/balance`, params)
}


