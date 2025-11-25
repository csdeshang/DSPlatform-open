import request, { API_BASE_URLS } from '@/utils/request';

// 获取积分商品订单分页列表
export function getPointsGoodsOrderPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/orders/pages`, { params })
}

// 获取积分商品订单详情
export function getPointsGoodsOrderInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}`);
}

// 更新积分商品订单信息
export function updatePointsGoodsOrder(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/orders/${params.id}`, params)
}

// 取消积分商品订单
export function cancelPointsGoodsOrder(id: number) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}/cancel`)
}

// 发货积分商品订单
export function shipPointsGoodsOrder(id: number, params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}/ship`, params)
}

// 确认收货积分商品订单
export function confirmPointsGoodsOrder(id: number) {
    return request.post(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}/confirm`)
}

// 获取积分商品订单日志
export function getPointsGoodsOrderLogs(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}/logs`);
}

// 删除积分商品订单
export function deletePointsGoodsOrder(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/points-goods/orders/${id}`)
}
