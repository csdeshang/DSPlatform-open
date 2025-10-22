import request, { API_BASE_URLS } from '@/utils/request'

// 获取骑手轨迹分页列表
export function getRiderTrackPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/rider/track/pages`, { params })
}

// 获取骑手轨迹详情
export function getRiderTrackInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/rider/track/${id}`)
}

// 删除骑手轨迹记录
export function deleteRiderTrack(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/rider/track/${id}`)
}

// 清空骑手轨迹记录
export function clearRiderTrack(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/rider/track/clear`, params)
}
