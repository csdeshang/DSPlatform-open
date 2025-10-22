import request, { API_BASE_URLS } from '@/utils/request'

/**
 * 获取骑手配送费规则列表
 */
export function getRiderFeeRulePages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/fee-rule/pages`, { params })
}

/**
 * 新增骑手配送费规则
 */
export function addRiderFeeRule(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/rider/fee-rule`, params)
}

/**
 * 更新骑手配送费规则
 */
export function updateRiderFeeRule(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/rider/fee-rule/${params.id}`, params)
}

/**
 * 更新骑手配送费规则状态
 */
export function updateRiderFeeRuleStatus(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/rider/fee-rule/status/${params.id}`, params)
}

/**
 * 删除骑手配送费规则
 */
export function deleteRiderFeeRule(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/rider/fee-rule/${id}`)
}

/**
 * 获取骑手配送费规则详情
 */
export function getRiderFeeRuleDetail(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/fee-rule/${id}`)
} 