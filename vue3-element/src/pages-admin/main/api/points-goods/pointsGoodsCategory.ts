import request, { API_BASE_URLS } from '@/utils/request'

// 获取积分商品分类树
export function getPointsGoodsCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/category/tree`, { params })
}

// 获取积分商品分类详情
export function getPointsGoodsCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/category/${id}`);
}

// 创建
export function createPointsGoodsCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/category`, params)
}

// 更新
export function updatePointsGoodsCategory(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/category/${params.id}`, params)
}

// 删除积分商品分类
export function deletePointsGoodsCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/points-goods/category/${id}`)
}
