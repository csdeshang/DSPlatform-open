import request, { API_BASE_URLS } from '@/utils/request'


// 获取用户概览数据, 主要用于首页
export function getStatUserOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/users/overview`, { params })
}



// 获取新用户统计数据
export function getStatUserNew(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/users/new`, { params })
}


