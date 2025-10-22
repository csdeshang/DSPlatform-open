import request, { API_BASE_URLS } from '@/utils/request'


// 获取商品概览数据, 主要用于首页
export function getStatGoodsOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/goods/overview`, { params })
}



// 获取商品新用户统计数据
export function getStatGoodsNew(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/goods/new`, { params })
}


