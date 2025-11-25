import request, { API_BASE_URLS } from '@/utils/request'


// 短信服务商列表
export function getSmsProviderList(params?: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/sms-provider/list`, { params })
}

// 短信服务商详情
export function getSmsProviderInfo(provider: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/sms-provider/${provider}`);
}

// 短信服务商配置更新 
export function updateSmsProviderConfig(params: Record<string, any>) {
    const { provider, ...restParams } = params;
    return request.put(`${API_BASE_URLS.ADMIN}/system/sms-provider/${provider}`, restParams)
}

// 短信发送测试
export function testSmsSend(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/sms-provider/test`, params)
}




    
