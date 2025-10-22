import request, { API_BASE_URLS } from '@/utils/request'

// 获取博主分页列表
export function getBloggerPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/blogger/blogger/pages`, { params })
}

// 获取博主详情
export function getBloggerInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/blogger/blogger/${id}`)
}

// 更新博主信息
export function updateBlogger(id: number, params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/blogger/blogger/${id}`, params)
}

// 切换博主字段状态
export function toggleBloggerField(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/blogger/blogger/toggle-field`, params)
}
