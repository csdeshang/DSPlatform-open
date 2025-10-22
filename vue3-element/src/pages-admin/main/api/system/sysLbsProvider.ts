import request, { API_BASE_URLS } from '@/utils/request'


// 地图服务商列表
export function getLbsProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/lbs/provider/list`, { params })
}

// 地图服务商详情
export function getLbsProviderInfo(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/lbs/provider/info`, { params });
}

// 短信服务商配置更新 
export function updateLbsProviderConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/lbs/provider/update`, params)
}