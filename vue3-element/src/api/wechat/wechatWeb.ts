
import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()




// 获取微信网站应用配置
export function getWechatWebSetting() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/web/settings`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/web/settings`)
    }
    return Promise.reject('getWechatWebSetting 系统类型错误')
}

// 更新微信网站应用配置
export function updateWechatWebSetting(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/wechat/web/settings`, params)
    } else if (systemType === 'merchant') {
        return request.put(`${API_BASE_URLS.MERCHANT}/wechat/web/settings`, params)
    }
    return Promise.reject('updateWechatWebSetting 系统类型错误')
}
