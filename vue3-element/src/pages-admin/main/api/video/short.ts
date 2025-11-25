import request, { API_BASE_URLS } from '@/utils/request'

// 获取短视频分页列表
export function getVideoShortPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/shorts/pages`, { params })
}

// 获取短视频详情
export function getVideoShortInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/shorts/${id}`)
}

// 更新短视频信息
export function updateVideoShort(id: number, params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/video/shorts/${id}`, params)
}

// 切换短视频字段状态
export function toggleVideoShortField(params: Record<string, any>) {
    return request.patch(`${API_BASE_URLS.ADMIN}/video/shorts/${params.id}/toggle-field`, { field: params.field })
}

// 审核短视频
export function auditVideoShort(params: Record<string, any>) {
    return request.patch(`${API_BASE_URLS.ADMIN}/video/shorts/${params.id}/audit`, { 
        audit_status: params.audit_status, 
        audit_remark: params.audit_remark 
    })
} 