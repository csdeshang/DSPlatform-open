import request, { API_BASE_URLS } from '@/utils/request';


export function getAdminRoleList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/admin/roles/list`, { params })
}

export function getAdminRoleInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/admin/roles/${id}`);
}

export function createAdminRole(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/admin/roles`, params)
}

export function updateAdminRole(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/admin/roles/${params.id}`, params)
}


export function deleteAdminRole(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/admin/roles/${id}`)
}


export function updateAdminRoleRules(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/admin/roles/${params.id}/rules`, params)
}