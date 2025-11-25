import request, { API_BASE_URLS } from '@/utils/request'

// 骑手评论列表
export function getRiderCommentPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/rider/comments/pages`, { params })
}

// 切换骑手评论字段状态
export function toggleRiderCommentField(params: Record<string, any>) {
    return request.patch(`${API_BASE_URLS.ADMIN}/rider/comments/${params.id}/toggle-field`, params)
}


