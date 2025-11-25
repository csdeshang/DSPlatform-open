import request, { API_BASE_URLS } from '@/utils/request'

// 快递查询服务商列表
export function getExpressProviderList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/express-provider/list`, { params })
}

// 快递查询服务商详情
export function getExpressProviderInfo(provider: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/express-provider/${provider}`);
}

// 快递查询服务商配置更新 
export function updateExpressProviderConfig(params: Record<string, any>) {
    const { provider, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/express-provider/${provider}`, restParams)
}

// 快递查询测试
export function testExpressQuery(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/express-provider/test`, params)
}

