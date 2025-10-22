import request, { API_BASE_URLS } from '@/utils/request';


// 获取订单列表
export function getTblOrderPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/order/pages`, { params })
}

// 获取订单详情
export function getTblOrderInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/order/info/${id}`);
}



// 获取订单商品列表
export function getTblOrderGoodsList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/goods/list`, { params })
}

// 获取订单商品分页
export function getTblOrderGoodsPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/goods/pages`, { params })
}




// 获取订单日志列表
export function getTblOrderLogList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/logs`, { params })
}


// 获取订单支付日志列表
export function getTblOrderPayLogList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/pay-logs`, { params })
}






