import request, { API_BASE_URLS } from '@/utils/request'


export function getUserPointsLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/points-logs/pages`, { params })
}

export function getUserPointsLogInfo(id: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/points-logs/${id}`)
}

export function modifyUserPoints(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/user/users/${params.user_id}/points`, params)
}


