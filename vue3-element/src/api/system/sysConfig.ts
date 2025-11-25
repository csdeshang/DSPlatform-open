
import request, { API_BASE_URLS } from '@/utils/request'


// 获取单个配置
export function getSysConfigByKey(key: string) {
  return request.get(`${API_BASE_URLS.USER}/system/configs/by-key`,   { params: { key } })
}

// 获取指定类型的所有配置
export function getConfigsByType(type: string) {
  return request.get(`${API_BASE_URLS.USER}/system/configs/by-type`, { params: { type } });
}

// 获取多个配置项
export function getConfigsByKeys(keys: string[]) {
  return request.post(`${API_BASE_URLS.USER}/system/configs/by-keys`, { keys: keys.join(',') });
}

// 获取多个类型的所有配置
export function getConfigsByTypes(types: string[]) {
  return request.post(`${API_BASE_URLS.USER}/system/configs/by-types`, { types: types.join(',') });
}


