import request, { API_BASE_URLS } from '@/utils/request'


// 文章列表
export function getSysArticlePages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/articles/pages`, { params })
}

export function getSysArticleInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/articles/${id}`);
}
export function createSysArticle(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/articles`, params)
}

export function updateSysArticle(params: Record<string, any>) {
    const { id, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/articles/${id}`, restParams)
}

export function deleteBatchSysArticle(data: Record<string, any>) {
    return request.delete(`${API_BASE_URLS.ADMIN}/system/articles/batch`, { data })
}


// 文章分类

export function getSysArticleCategoryTree(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article-categories/tree`, { params })
}

export function getSysArticleCategoryInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/article-categories/${id}`);
}

export function createSysArticleCategory(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/article-categories`, params)
}

export function updateSysArticleCategory(params: Record<string, any>) {
    const { id, ...restParams } = params
    return request.put(`${API_BASE_URLS.ADMIN}/system/article-categories/${id}`, restParams)
}

export function deleteSysArticleCategory(id: number) {
    return request.delete(`${API_BASE_URLS.ADMIN}/system/article-categories/${id}`, { showSuccessMessage: true })
}
