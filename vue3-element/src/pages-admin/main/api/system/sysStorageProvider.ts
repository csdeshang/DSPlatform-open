import request, { API_BASE_URLS } from '@/utils/request'


// 存储服务商列表
export function getStorageProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/storage-provider/list`, { params })
}

// 存储服务商详情
export function getStorageProviderInfo(provider: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/storage-provider/${provider}`);
}

// 存储服务商配置更新 
export function updateStorageProviderConfig(params: Record<string, any>) {
    const { provider, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/storage-provider/${provider}`, restParams)
}