import request, { API_BASE_URLS } from '@/utils/request'


// 获取店铺分类树
export function getTblStoreCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.USER}/tbl-store/store-categories/tree`, { params })
}

// 获取店铺分类列表
export function getTblStoreCategoryList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.USER}/tbl-store/store-categories/list`, { params })
}