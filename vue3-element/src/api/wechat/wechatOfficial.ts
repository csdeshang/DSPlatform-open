
import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()


// 获取微信公众平台配置
export function getWechatOfficialSetting() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/official/settings`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/official/settings`)
    }
    return Promise.reject('getWechatOfficialSetting 系统类型错误')
}

// 更新微信公众平台配置
export function updateWechatOfficialSetting(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/official/settings`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/official/settings`, params)
    }
    return Promise.reject('updateWechatOfficialSetting 系统类型错误')
}



// 保存微信公众号菜单
export function updateWechatOfficialMenu(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/official/menus`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/official/menus`, params)
    }
    return Promise.reject('updateWechatOfficialMenu 系统类型错误')
}

// 发布微信公众号菜单
export function publishWechatOfficialMenu(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/official/menus/publish`, params)
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/official/menus/publish`, params)
    }
    return Promise.reject('publishWechatOfficialMenu 系统类型错误')
}

// 获取微信公众号菜单
export function getWechatOfficialMenu() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/official/menus`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/official/menus`)
    }
    return Promise.reject('getWechatOfficialMenu 系统类型错误')
}

