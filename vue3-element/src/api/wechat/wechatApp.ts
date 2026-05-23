
import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()




// 获取微信移动应用配置
export function getWechatAppSetting() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/app/settings`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/app/settings`)
    }
    return Promise.reject('getWechatAppSetting 系统类型错误')
}

// 更新微信移动应用配置
export function updateWechatAppSetting(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/app/settings`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/app/settings`, params)
    }
    return Promise.reject('updateWechatAppSetting 系统类型错误')
}
