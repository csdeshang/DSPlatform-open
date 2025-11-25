import request, { API_BASE_URLS } from '@/utils/request'

// 打印机服务商列表
export function getPrinterProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/printer-provider/list`, { params })
}

// 打印机服务商详情
export function getPrinterProviderInfo(provider: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/printer-provider/${provider}`);
}

// 打印机服务商配置更新 
export function updatePrinterProviderConfig(params: Record<string, any>) {
    const { provider, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/printer-provider/${provider}`, restParams)
} 