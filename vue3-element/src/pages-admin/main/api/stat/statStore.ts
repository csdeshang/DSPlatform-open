import request, { API_BASE_URLS } from '@/utils/request'


// 获取店铺概览数据, 主要用于首页
export function getStatStoreOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/stores/overview`, { params })
}



// 获取店铺新用户统计数据
export function getStatStoreNew(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/stores/new`, { params })
}


