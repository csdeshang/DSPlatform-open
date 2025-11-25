

import request, { API_BASE_URLS } from '@/utils/request'

import { getSystemType } from '@/utils/util'
const systemType = getSystemType()

// 获取微信公众号模板列表
export function getWechatOfficialTemplateList() {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/wechat/official/templates/list`)
    } else if (systemType === 'merchant') {
        return request.get(`${API_BASE_URLS.MERCHANT}/wechat/official/templates/list`)
    }
    return Promise.reject('getWechatOfficialTemplateList 系统类型错误')
}

// 同步微信公众号模板
export function syncWechatOfficialTemplate(keys: string[]) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/wechat/official/templates/sync`, { keys })
    } else if (systemType === 'merchant') {
        return request.post(`${API_BASE_URLS.MERCHANT}/wechat/official/templates/sync`, { keys })
    }
    return Promise.reject('syncWechatOfficialTemplate 系统类型错误')
}

// 删除微信公众号模板
export function deleteWechatOfficialTemplate(key: string) {
    if (systemType === 'admin') {
        return request.delete(`${API_BASE_URLS.ADMIN}/wechat/official/templates/${key}`)
    } else if (systemType === 'merchant') {
        return request.delete(`${API_BASE_URLS.MERCHANT}/wechat/official/templates/${key}`)
    }
    return Promise.reject('deleteWechatOfficialTemplate 系统类型错误')
}

