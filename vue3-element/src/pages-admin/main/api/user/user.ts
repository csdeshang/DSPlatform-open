import request, { API_BASE_URLS } from '@/utils/request'

/**
 * 获取用户分页列表
 * @param params 查询参数（username, mobile, inviter_id, is_enabled, is_deleted 等）
 * @returns Promise
 */
export function getUserPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/users/pages`, { params })
}

/**
 * 获取用户详细信息
 * @param id 用户ID
 * @returns Promise
 */
export function getUserInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/users/${ id }`);
}

/**
 * 创建用户
 * @param params 用户信息（username, password, confirm_password 等）
 * @returns Promise
 */
export function createUser(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/user/users`,   params )
}

/**
 * 更新用户信息
 * @param params 用户信息（id, nickname, sex, birthday, email, mobile, is_enabled 等）
 * @returns Promise
 */
export function updateUser(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/user/users/${ params.id }`,   params )
}

/**
 * 删除用户（硬删除）
 * @param id 用户ID
 * @returns Promise
 */
export function deleteUser(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/user/users/${ id }`)
}

/**
 * 软删除用户
 * @param id 用户ID
 * @returns Promise
 */
export function softDeleteUser(id: number) {
  return request.patch(`${API_BASE_URLS.ADMIN}/user/users/${ id }/soft-delete`)
}

/**
 * 恢复已删除的用户
 * @param id 用户ID
 * @returns Promise
 */
export function restoreUser(id: number) {
  return request.patch(`${API_BASE_URLS.ADMIN}/user/users/${ id }/restore`)
}

/**
 * 获取用户推广关系列表
 * @param params 查询参数（inviter_id）
 * @returns Promise
 */
export function getUserRelationList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/users/relation/list`, { params })
}




