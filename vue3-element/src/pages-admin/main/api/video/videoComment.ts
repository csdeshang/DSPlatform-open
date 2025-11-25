import request, { API_BASE_URLS } from '@/utils/request'

// 视频评论列表
export function getVideoCommentPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/comments/pages`, { params })
}

// 切换视频评论字段状态
export function toggleVideoCommentField(params: Record<string, any>) {
    return request.patch(`${API_BASE_URLS.ADMIN}/video/comments/${params.id}/toggle-field`, { field: params.field })
}

