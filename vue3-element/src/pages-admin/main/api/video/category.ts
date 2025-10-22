import request, { API_BASE_URLS } from '@/utils/request'

// 获取视频分类树
export function getVideoCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/category/tree`, { params })
}

// 获取视频分类详情
export function getVideoCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/video/category/${id}`)
}

// 创建视频分类
export function createVideoCategory(data: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/video/category`, data)
}

// 更新视频分类
export function updateVideoCategory(data: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/video/category/${data.id}`, data)
}

// 删除视频分类
export function deleteVideoCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/video/category/${id}`)
}

// 切换视频分类字段状态
export function toggleVideoCategoryField(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/video/category/toggle-field`, params)
}
