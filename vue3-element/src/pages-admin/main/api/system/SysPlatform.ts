import request, { API_BASE_URLS } from '@/utils/request'




export function getSysPlatformList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/platform/list`, { params })
}

export function updateSysPlatform(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/system/platform/${params.id}`, params)
}
