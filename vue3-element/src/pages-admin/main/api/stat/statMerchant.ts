import request, { API_BASE_URLS } from '@/utils/request'


// 获取商户概览数据, 主要用于首页
export function getStatMerchantOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/merchants/overview`, { params })
}



// 获取商户新用户统计数据
export function getStatMerchantNew(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/merchants/new`, { params })
}


