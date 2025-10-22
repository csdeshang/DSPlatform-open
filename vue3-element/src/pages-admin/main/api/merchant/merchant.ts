import request, { API_BASE_URLS } from '@/utils/request'

/**
 * 商户列表
 * @param params
 * @returns
 */
export function getMerchantPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/merchant/merchant/pages`, { params })
}


export function getMerchantInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/merchant/merchant/${id}`)
}

export function createMerchant(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/merchant/merchant`, params)
}

export function updateMerchant(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/merchant/merchant/${params.id}`, params)
}

/**
 * 审核商户申请
 * @param id 商户ID
 * @param params 审核参数
 * @returns
 */
export function auditMerchant(id: number, params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/merchant/merchant/audit/${id}`, params)
}
