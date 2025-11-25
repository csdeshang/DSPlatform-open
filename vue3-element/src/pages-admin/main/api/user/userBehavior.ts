import request, { API_BASE_URLS } from '@/utils/request'

/**
 * 获取用户行为日志分页列表
 * @param params
 */
export function getUserBehaviorLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/behavior-logs/pages`, { params })
}

/**
 * 获取用户行为日志详情
 * @param id
 */
export function getUserBehaviorLogInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/behavior-logs/${id}`)
}

/**
 * 删除用户行为日志
 * @param id
 */
export function deleteUserBehaviorLog(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/user/behavior-logs/${id}`)
}