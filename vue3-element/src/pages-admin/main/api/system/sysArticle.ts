import request, { API_BASE_URLS } from '@/utils/request'


// 文章列表
export function getSysArticlePages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article/pages`, { params })
}

export function getSysArticleInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article/${id}`);
}
export function createSysArticle(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/article/create`, params)
}

export function updateSysArticle(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/system/article/${params.id}`, params)
}

export function deleteBatchSysArticle(data: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/article/del-batch`, data)
}


// 文章分类

export function getSysArticleCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article/category/tree`, { params })
}

export function getSysArticleCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article/category/${id}`);
}

export function createSysArticleCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/article/category`, params)
}

export function updateSysArticleCategory(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/system/article/category/${params.id}`, params)
}

export function deleteSysArticleCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/system/article/category/${id}`, { showSuccessMessage: true })
}
