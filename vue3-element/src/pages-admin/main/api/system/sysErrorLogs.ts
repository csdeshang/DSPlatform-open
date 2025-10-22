import request, { API_BASE_URLS } from '@/utils/request'

export function getSysErrorLogsPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/error-logs/pages`, { params })
}


export function getSysErrorLogsInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/error-logs/${id}`)
}