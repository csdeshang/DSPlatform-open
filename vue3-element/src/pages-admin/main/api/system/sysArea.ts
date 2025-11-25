import request, { API_BASE_URLS } from '@/utils/request'


export function getSysAreaList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/areas/list`, { params })
}

export function getSysAreaInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/areas/${id}`);
}

export function getSysAreaOptions(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/areas/options`, { params })
}


export function createSysArea(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/areas`, params)
}

export function updateSysArea(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/system/areas/${params.id}`, params)
}

export function deleteSysArea(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/system/areas/${id}`, { showSuccessMessage: true })
}

