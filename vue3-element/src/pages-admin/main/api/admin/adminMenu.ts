import request, { API_BASE_URLS } from '@/utils/request';


export function getAdminMenuTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/admin/menus/tree`, { params })
}

export function getAdminMenuInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/admin/menus/${id}`);
}

export function getAdminMenuOptions(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/admin/menus/options`, { params })
}


export function createAdminMenu(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/admin/menus`, params)
}

export function updateAdminMenu(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/admin/menus/${params.id}`, params)
}


export function deleteAdminMenu(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/admin/menus/${id}`)
}