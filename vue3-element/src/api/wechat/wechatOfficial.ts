
import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()


// 获取微信公众平台配置
export function getWechatOfficialSetting() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/official/setting`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/official/setting`)
    }
    return Promise.reject('getWechatOfficialSetting 系统类型错误')
}

// 更新微信公众平台配置
export function updateWechatOfficialSetting(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/official/setting`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/official/setting`, params)
    }
    return Promise.reject('updateWechatOfficialSetting 系统类型错误')
}



// 保存微信公众号菜单
export function updateWechatOfficialMenu(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/official/menu/update`, params)
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/official/menu/update`, params)
    }
    return Promise.reject('updateWechatOfficialMenu 系统类型错误')
}

// 发布微信公众号菜单
export function publishWechatOfficialMenu(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/official/menu/publish`, params)
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/official/menu/publish`, params)
    }
    return Promise.reject('publishWechatOfficialMenu 系统类型错误')
}

// 获取微信公众号菜单
export function getWechatOfficialMenu() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/official/menu`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/official/menu`)
    }
    return Promise.reject('getWechatOfficialMenu 系统类型错误')
}

