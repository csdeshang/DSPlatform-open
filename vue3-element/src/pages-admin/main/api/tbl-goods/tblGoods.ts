import request, { API_BASE_URLS } from '@/utils/request';


// 获取商品列表
export function getTblGoodsPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/pages`, { params })
}

// 获取商品信息
export function getTblGoodsInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-goods/info/${id}`);
}

// 更新商品信息
export function updateTblGoods(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-goods/update/${params.id}`, params)
}

// 修改状态  包含  审核 以 下架， 修改  tbl_goods 表的 sys_status 字段
export function updateTblGoodsSysStatus(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-goods/update-sys-status/${params.id}`, params)
}

// 修改推荐状态  包含  推荐 以 取消推荐
export function updateTblGoodsSysRecommend(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/tbl-goods/update-sys-recommend/${params.id}`, params)
}


