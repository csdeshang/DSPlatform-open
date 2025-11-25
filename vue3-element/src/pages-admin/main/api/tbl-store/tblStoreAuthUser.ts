
import request, { API_BASE_URLS } from '@/utils/request'

// 获取店铺授权用户列表
export function getTblStoreAuthUserList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/store-auths`, { params })
}

// 添加店铺授权用户
export function createTblStoreAuthUser(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-store/store-auths`, params)
}

// 删除店铺授权用户
export function deleteTblStoreAuthUser(params: Record<string, any>) {
    return request.delete(`${API_BASE_URLS.ADMIN}/tbl-store/store-auths`, { params })
}









