import request, { API_BASE_URLS } from '@/utils/request'

/**
 * 获取用户行为日志分页列表
 * @param params
 */
export function getUserBehaviorLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/behavior-log/pages`, { params })
}

/**
 * 获取用户行为日志详情
 * @param id
 */
export function getUserBehaviorLogInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/behavior-log/${id}`)
}
