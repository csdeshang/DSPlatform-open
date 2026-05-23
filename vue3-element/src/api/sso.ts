import request, { API_BASE_URLS } from '@/utils/request'

/**
 * SSO（ticket 换 token）
 * 后端接口：/api/sso/ticket、/api/sso/exchange
 */
export function ssoCreateTicket() {
  // ticket 的签发依赖“当前站点已登录”的 access-token header（由 request 拦截器注入）
  return request.post(`${API_BASE_URLS.USER}/sso/ticket`)
}

export function ssoExchangeTicket(ticket: string) {
  return request.post(`${API_BASE_URLS.USER}/sso/exchange`, { ticket })
}

