import request, { API_BASE_URLS } from '@/utils/request'


// 地图服务商列表
export function getLbsProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/lbs-provider/list`, { params })
}

// 地图服务商详情
export function getLbsProviderInfo(provider: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/lbs-provider/${provider}`);
}

// 地图服务商配置更新 
export function updateLbsProviderConfig(params: Record<string, any>) {
    const { provider, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/lbs-provider/${provider}`, restParams)
}