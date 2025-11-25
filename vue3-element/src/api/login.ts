import request, { API_BASE_URLS } from '@/utils/request'


import { getSystemType } from '@/utils/util'

const systemType = getSystemType()


// 登录
export function loginNormal(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/login`, params)
    } else if (systemType === 'store') {
        return request.post(`${API_BASE_URLS.USER}/login/normal`,  params )
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.USER}/login/normal`,  params )
    }
    return Promise.reject('loginNormal 系统类型错误')
}

//  refresh_token 刷新 access_token
export function refreshToken(params: Record<string, any>) {

    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/refresh-token`, { params })
    } else if (systemType === 'store') {
        return request.get(`${API_BASE_URLS.USER}/refresh-token`, {params})
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.USER}/refresh-token`, {params})
    }
    return Promise.reject('refreshToken 系统类型错误')
}

// 退出登录
export function logout(params?: Record<string, any>) {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/logout`, {params})
    } else if (systemType === 'store') {
        return request.get(`${API_BASE_URLS.USER}/logout`, {params})
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.USER}/logout`, {params})
    }
    return Promise.reject('logout 系统类型错误')
}





