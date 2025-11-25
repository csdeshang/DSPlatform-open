import request, { API_BASE_URLS } from '@/utils/request'

// 获取积分商品分类树
export function getPointsGoodsCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/categories/tree`, { params })
}

// 获取积分商品分类详情
export function getPointsGoodsCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/categories/${id}`);
}

// 创建积分商品分类
export function createPointsGoodsCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/categories`, params)
}

// 更新积分商品分类
export function updatePointsGoodsCategory(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/categories/${params.id}`, params)
}

// 删除积分商品分类
export function deletePointsGoodsCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/points-goods/categories/${id}`)
}
