import request, { API_BASE_URLS } from '@/utils/request';

/**
 * 获取管理员列表
 * @param params
 * @returns
 */
export function getAdminPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/admin/admin/pages`, { params })
}

export function getAdminInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/admin/admin/${ id }`);
}


export function createAdmin(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/admin/admin`,   params )
}

export function updateAdmin(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/admin/admin/${ params.id }`,   params )
}

export function deleteAdmin(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/admin/admin/${ id }`)
}