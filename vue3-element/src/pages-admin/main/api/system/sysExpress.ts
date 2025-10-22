import request, { API_BASE_URLS } from '@/utils/request'

export function getSysExpressPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/express/pages`, { params })
}

export function getSysExpressList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/express/list`, { params })
}

export function getSysExpressInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/express/${id}`);
}

export function createSysExpress(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/express`, params)
}

export function updateSysExpress(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/system/express/${params.id}`, params)
}

export function deleteSysExpress(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/system/express/${id}`, { showSuccessMessage: true })
}



