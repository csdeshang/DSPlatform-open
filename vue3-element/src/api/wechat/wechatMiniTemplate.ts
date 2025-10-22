

import request, { API_BASE_URLS } from '@/utils/request'



import { getSystemType } from '@/utils/util'
const systemType = getSystemType()







// 获取微信小程序消息模板列表
export function getWechatMiniTemplateList() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/mini/template/list`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/mini/template/list`)
    }
    return Promise.reject('getWechatMiniTemplateList 系统类型错误')
}


// 同步微信小程序模板
export function syncWechatMiniTemplate(keys: string[]) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/mini/template/sync`, { keys })
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/mini/template/sync`, { keys })
    }
    return Promise.reject('syncWechatMiniTemplate 系统类型错误')
}

// 删除微信小程序模板
export function deleteWechatMiniTemplate(key: string) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/mini/template/delete`, { key })
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/mini/template/delete`, { key })
    }
    return Promise.reject('deleteWechatMiniTemplate 系统类型错误')
}
