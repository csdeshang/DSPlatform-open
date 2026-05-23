import request, { API_BASE_URLS } from '@/utils/request'


export function getRiderPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/riders/pages`, { params })
}

export function getRiderInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/riders/${ id }`);
}


export function createRider(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/rider/riders`,   params )
}

export function updateRider(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/rider/riders/${ params.id }`,   params )
}

/**
 * 审核骑手申请
 * @param id 骑手ID
 * @param params 审核参数
 * @returns
 */
export function auditRider(id: number, params: Record<string, any>) {
  return request.patch(`${API_BASE_URLS.ADMIN}/rider/riders/${id}/audit`, params)
}

/**
 * 软删除骑手
 * @param id 骑手ID
 * @returns
 */
export function softDeleteRider(id: number) {
  return request.patch(`${API_BASE_URLS.ADMIN}/rider/riders/${id}/soft-delete`)
}

/**
 * 恢复已删除的骑手
 * @param id 骑手ID
 * @returns
 */
export function restoreRider(id: number) {
  return request.patch(`${API_BASE_URLS.ADMIN}/rider/riders/${id}/restore`)
}

