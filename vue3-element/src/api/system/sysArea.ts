
import request, { API_BASE_URLS } from '@/utils/request'


// 获取地区列表
export function getSysAreaList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.USER}/system/area/list`, { params })
}

export function getSysAreaTree() {
  return request.get(`${API_BASE_URLS.USER}/system/area/tree`)
}

