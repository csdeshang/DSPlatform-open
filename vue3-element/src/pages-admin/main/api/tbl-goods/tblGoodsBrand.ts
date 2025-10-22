import request, { API_BASE_URLS } from '@/utils/request'


export function getTblGoodsBrandTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/brand/tree`, { params })
}


export function getTblGoodsBrandInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/brand/${id}`);
}


export function createTblGoodsBrand(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/tbl-goods/brand`, params)
}


export function updateTblGoodsBrand(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-goods/brand/${params.id}`, params)
}



export function deleteTblGoodsBrand(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/tbl-goods/brand/${id}`)
}


