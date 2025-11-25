import request, { API_BASE_URLS } from '@/utils/request'





// 用户充值记录
export function getUserRechargeLogPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/recharge-logs/pages`, { params })
}