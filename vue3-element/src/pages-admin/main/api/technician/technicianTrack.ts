import request, { API_BASE_URLS } from '@/utils/request'

// 获取师傅轨迹分页列表
export function getTechnicianTrackPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/technician/track/pages`, { params })
}

// 获取师傅轨迹详情
export function getTechnicianTrackInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/technician/track/${id}`)
}

// 删除师傅轨迹记录
export function deleteTechnicianTrack(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/technician/track/${id}`)
}

// 清空师傅轨迹记录
export function clearTechnicianTrack(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/technician/track/clear`, params)
} 