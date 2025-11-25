

import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()







// 获取微信小程序消息模板列表
export function getWechatMiniTemplateList() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/mini/templates/list`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/mini/templates/list`)
    }
    return Promise.reject('getWechatMiniTemplateList 系统类型错误')
}


// 同步微信小程序模板
export function syncWechatMiniTemplate(keys: string[]) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/mini/templates/sync`, { keys })
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/mini/templates/sync`, { keys })
    }
    return Promise.reject('syncWechatMiniTemplate 系统类型错误')
}

// 删除微信小程序模板
export function deleteWechatMiniTemplate(key: string) {
    if (systemType === 'admin') {
        return request.delete(`${API_BASE_URLS.ADMIN}/wechat/mini/templates/${key}`)
    } else if (systemType === 'merchant') {
        return request.delete(`${API_BASE_URLS.MERCHANT}/wechat/mini/templates/${key}`)
    }
    return Promise.reject('deleteWechatMiniTemplate 系统类型错误')
}
