
import request, { API_BASE_URLS } from '@/utils/request'


// 获取平台列表
export function getSysPlatformList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.USER}/system/platform/list`, { params })
}