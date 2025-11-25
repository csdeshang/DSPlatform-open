import request, { API_BASE_URLS } from '@/utils/request'

import { getSystemType } from '@/utils/util'

const systemType = getSystemType()

// 获取异步路由
export function getCurrentUserMenus() {
    if(systemType === 'admin'){
        // 获取当前管理员的加载路由信息
        return request.get(`${API_BASE_URLS.ADMIN}/admin/current/menus`)
    }else if(systemType === 'store'){
        // 获取当前店铺的加载路由信息
        return request.get(`${API_BASE_URLS.STORE}/tbl-store/info/menus`)
    }else if(systemType === 'merchant'){
        // 获取当前商户的加载路由信息
        return request.get(`${API_BASE_URLS.MERCHANT}/merchant/info/menus`)
    }else if(systemType === 'consumer'){
        // 获取当前消费者的加载路由信息
        
    }
    return Promise.reject('getCurrentUserMenus 系统类型错误')
}


// 获取用户信息 (角色分为  后台管理员用户和普通用户 ， 普通用户下带其他角色信息)
export function getCurrentUserInfo() {
    if(systemType === 'admin'){
        // 获取当前管理员的信息
        return request.get(`${API_BASE_URLS.ADMIN}/admin/current/info`)
    }else if(systemType === 'store'){
        // 获取当前店铺的信息
        return request.get(`${API_BASE_URLS.USER}/user/user/info-with-roles`)
        // return request.get(`${API_BASE_URLS.STORE}/tbl-store/info/info`)
    }else if(systemType === 'merchant'){
        // 获取当前商户的信息
        return request.get(`${API_BASE_URLS.USER}/user/user/info-with-roles`)
        // return request.get(`${API_BASE_URLS.MERCHANT}/merchant/info/info`)
    }else if(systemType === 'consumer'){
        // 获取当前消费者的加载路由信息
        return request.get(`${API_BASE_URLS.USER}/user/user/info-with-roles`)
    }
    return Promise.reject('getCurrentUserInfo 系统类型错误')
}


// 修改当前用户密码
export function editCurrentUserPassword(data: Record<string, any>) {
    if(systemType === 'admin'){
        // 修改管理员密码
        return request.put(`${API_BASE_URLS.ADMIN}/admin/current/password`, data)
    }else{
        // 修改用户密码
        return request.put(`${API_BASE_URLS.USER}/user/user/password`, data)
    }
}


export function getEnumData(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.USER}/enums/data`, { params })
}



