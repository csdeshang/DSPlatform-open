import request, { API_BASE_URLS } from '@/utils/request'

// 打印机服务商列表
export function getPrinterProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/printer/provider/list`, { params })
}

// 打印机服务商详情
export function getPrinterProviderInfo(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/printer/provider/info`, { params });
}

// 打印机服务商配置更新 
export function updatePrinterProviderConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/printer/provider/update`, params)
} 