import request, { API_BASE_URLS } from '@/utils/request'


// 订单配送
export function getTblOrderDeliveryPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/delivery/pages`, { params })
}


