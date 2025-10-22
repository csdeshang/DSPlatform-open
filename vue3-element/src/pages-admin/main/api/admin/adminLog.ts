import request, { API_BASE_URLS } from '@/utils/request';

export function getAdminLogPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/admin/logs/pages`, { params })
}

export function getAdminLogInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/admin/logs/${id}`)
}