import request, { API_BASE_URLS } from '@/utils/request'



export function getSysConfigList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/config/getSysConfigList`, { params })
}
// 单个配置修改
export function updateSysConfigInfo(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/updateSysConfigInfo`, params)
}
// 根据key获取单个系统配置
export function getSysConfigInfoByKey(key: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/config/info/${key}`)
}
// 更新网站配置
export function editWebsite(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editWebsite`, params)
}

// 更新成长配置
export function editGrowthRules(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editGrowthRules`, params)
}

// 更新积分配置
export function editPointsRules(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editPointsRules`, params)
}

// 更新商品配置
export function editGoodsRules(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editGoodsRules`, params)
}


// 更新邮件配置
export function editEmailConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editEmailConfig`, params)
}

// 测试邮件发送
export function testEmailSend(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/testEmailSend`, params)
}

// 更新订单自动配置
export function editOrderAutoConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editOrderAutoConfig`, params)
}

// 更新登录配置
export function editLoginConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editLoginConfig`, params)
}

// 更新用户提现配置
export function editUserWithdrawalRules(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editUserWithdrawalRules`, params)
}

// 更新分销商配置
export function editDistributorConfig(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/config/editDistributorConfig`, params)
}