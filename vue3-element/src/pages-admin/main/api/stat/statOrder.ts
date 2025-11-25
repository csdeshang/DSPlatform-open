import request, { API_BASE_URLS } from '@/utils/request'


// 获取订单概览数据, 主要用于首页
export function getStatOrderOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/orders/overview`, { params })
}



// 获取订单新用户统计数据
export function getStatOrderNew(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/orders/new`, { params })
}


