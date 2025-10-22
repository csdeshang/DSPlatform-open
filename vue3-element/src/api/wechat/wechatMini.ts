
import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()






// 获取微信小程序配置
export function getWechatMiniSetting() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/mini/setting`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/mini/setting`)
    }
    return Promise.reject('getWechatMiniSetting 系统类型错误')
}

// 更新微信小程序配置
export function updateWechatMiniSetting(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/mini/setting`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/mini/setting`, params)
    }
    return Promise.reject('updateWechatMiniSetting 系统类型错误')
}
