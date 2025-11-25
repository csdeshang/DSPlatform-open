import request, { API_BASE_URLS } from '@/utils/request'


export function getUserGrowthLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/growth-logs/pages`, { params })
}

export function getUserGrowthLogInfo(id: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/growth-logs/${id}`)
}

export function modifyUserGrowth(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/user/users/${params.user_id}/growth`, params)
}


