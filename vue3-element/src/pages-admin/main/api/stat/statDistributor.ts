import request, { API_BASE_URLS } from '@/utils/request'


// 获取分销商概览数据, 主要用于首页
export function getStatDistributorOverview(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/stat/distributor/overview`, { params })
}

