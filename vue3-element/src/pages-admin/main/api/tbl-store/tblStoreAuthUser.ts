
import request, { API_BASE_URLS } from '@/utils/request'

// 获取店铺授权用户 用户列表
export function getTblStoreAuthUserList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/auth/users`, { params })
}




// 添加授权管理用户
export function createTblStoreAuthUser(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-store/auth/user/create`,  params )
}

// 删除授权管理用户
export function deleteTblStoreAuthUser(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-store/auth/user/delete`, params )
}









