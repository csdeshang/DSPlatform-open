import request, { API_BASE_URLS } from '@/utils/request'


export function getTblStoreCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/category/tree`, { params })
}


export function getTblStoreCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/category/${id}`);
}


export function createTblStoreCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-store/category`, params)
}


export function updateTblStoreCategory(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-store/category/${params.id}`, params)
}


export function deleteTblStoreCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/tbl-store/category/${id}`)
}