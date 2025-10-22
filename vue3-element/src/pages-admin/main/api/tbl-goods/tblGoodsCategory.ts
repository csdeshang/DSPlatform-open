import request, { API_BASE_URLS } from '@/utils/request'


export function getTblGoodsCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/category/tree`, { params })
}


export function getTblGoodsCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/category/${id}`);
}


export function createTblGoodsCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-goods/category`, params)
}


export function updateTblGoodsCategory(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-goods/category/${params.id}`, params)
}


export function deleteTblGoodsCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/tbl-goods/category/${id}`)
}