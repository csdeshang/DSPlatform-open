import request, { API_BASE_URLS } from '@/utils/request'

// 获取师傅评论列表
export function getTechnicianCommentPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/technician/comment/pages`, { params })
}

// 切换师傅评论字段状态
export function toggleTechnicianCommentField(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/technician/comment/toggle-field`, params)
} 