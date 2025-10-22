import request, { API_BASE_URLS } from '@/utils/request';

// 获取积分商品订单分页列表
export function getPointsGoodsOrderPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/order/pages`, { params })
}

// 获取积分商品订单详情
export function getPointsGoodsOrderInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/order/${id}`);
}

// 更新积分商品订单信息
export function updatePointsGoodsOrder(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/order/${params.id}`, params)
}

// 取消积分商品订单
export function cancelPointsGoodsOrder(id: number) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/order/cancel/${id}`)
}

// 发货积分商品订单
export function shipPointsGoodsOrder(id: number, params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/order/ship/${id}`, params)
}

// 确认收货积分商品订单
export function confirmPointsGoodsOrder(id: number) {
    return request.put(`${API_BASE_URLS.ADMIN}/points-goods/order/confirm/${id}`)
}

// 获取积分商品订单日志
export function getPointsGoodsOrderLogs(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/points-goods/order/logs/${id}`);
}

// 删除积分商品订单
export function deletePointsGoodsOrder(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/points-goods/order/${id}`)
}
