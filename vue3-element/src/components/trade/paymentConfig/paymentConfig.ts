import request, { API_BASE_URLS } from '@/utils/request'

import storage from '@/utils/storage'


const store_id = storage.get('store_id')



// 获取商户支付配置  后台的默认ID为0
export function getPaymentConfigByMerchant() {
    if (store_id) {
        return request.get(`${API_BASE_URLS.STORE}/trade/payment-config/merchant`)
    } else {
        return request.get(`${API_BASE_URLS.ADMIN}/trade/payment-config/merchant`)
    }
}

export function getPaymentConfigInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/trade/payment-config/${id}`)
}

export function createPaymentConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/trade/payment-config`, params )
}

export function updatePaymentConfig(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/trade/payment-config/${params.id}`,  params )
}

