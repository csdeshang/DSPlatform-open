import request, { API_BASE_URLS } from '@/utils/request'

// 获取短视频分页列表
export function getVideoShortPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/short/pages`, { params })
}

// 获取短视频详情
export function getVideoShortInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/short/${id}`)
}

// 更新短视频信息
export function updateVideoShort(id: number, params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/video/short/${id}`, params)
}

// 切换短视频字段状态
export function toggleVideoShortField(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/video/short/toggle-field`, params)
}

// 审核短视频
export function auditVideoShort(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/video/short/audit`, params)
} 