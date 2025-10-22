import request, { API_BASE_URLS } from '@/utils/request';



/**
 * 获取可编辑列表
 * @param params
 * @returns
 */
export function getEditablePages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/editable/editable/pages`, { params })
}

/**
 * 创建页面
 * @param params
 * @returns
 */
export function createEditablePage(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/editable/editable`, params)
}

/**
 * 更新页面
 * @param params
 * @returns
 */
export function updateEditablePage(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/editable/editable/${params.id}`, params)
}


/**
 * 获取页面详情
 * @param id
 * @returns
 */
export function getEditablePageInfo(id: string) {
    return request.get(`${API_BASE_URLS.ADMIN}/editable/editable/${id}`)
}


/**
 * 删除页面
 * @param id
 * @returns
 */
export function deleteEditablePage(id: string) {
    return request.delete(`${API_BASE_URLS.ADMIN}/editable/editable/${id}`)
}



