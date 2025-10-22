import request, { API_BASE_URLS } from '@/utils/request'


export function getUserPointsLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/points-log/pages`, { params })
}


export function modifyUserPoints(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/user/points/modifyUserPoints`,  params )
}


