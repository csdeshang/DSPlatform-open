import request, { API_BASE_URLS } from '@/utils/request'


export function getUserGrowthLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/growth-log/pages`, { params })
}


export function modifyUserGrowth(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/user/growth/modifyUserGrowth`,  params )
}


