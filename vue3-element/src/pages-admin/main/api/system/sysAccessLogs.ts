import request, { API_BASE_URLS } from '@/utils/request'

export function getSysAccessLogsPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/access-logs/pages`, { params })
}



export function getSysAccessLogsInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/access-logs/${id}`)
}