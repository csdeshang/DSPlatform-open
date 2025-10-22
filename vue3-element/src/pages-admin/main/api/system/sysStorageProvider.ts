import request, { API_BASE_URLS } from '@/utils/request'


// 存储服务商列表
export function getStorageProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/storage/provider/list`, { params })
}

// 短信服务商详情
export function getStorageProviderInfo(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/storage/provider/info`, { params });
}

// 短信服务商配置更新 
export function updateStorageProviderConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/storage/provider/update`, params)
}