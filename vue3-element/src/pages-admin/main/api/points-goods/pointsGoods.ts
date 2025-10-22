import request, { API_BASE_URLS } from '@/utils/request';

// 获取积分商品列表
export function getPointsGoodsPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/goods/pages`, { params })
}

// 获取积分商品信息
export function getPointsGoodsInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/goods/${id}`);
}

// 创建积分商品
export function createPointsGoods(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/goods`, params)
}

// 更新积分商品信息
export function updatePointsGoods(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/goods/${params.id}`, params)
}

// 删除积分商品
export function deletePointsGoods(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/points-goods/goods/${id}`)
}

