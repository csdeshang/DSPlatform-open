import request, { API_BASE_URLS } from '@/utils/request';

// 获取积分商品评价分页列表
export function getPointsGoodsEvaluatePages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/evaluate/pages`, { params })
}

// 切换积分商品评价字段状态
export function togglePointsGoodsEvaluateField(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/evaluate/toggle`, params)
}

// 商家回复积分商品评价
export function replyPointsGoodsEvaluate(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/evaluate/reply`, params)
}
