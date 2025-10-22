import request, { API_BASE_URLS } from '@/utils/request'


import { getSystemType } from '@/utils/util'
const systemType = getSystemType()

//附件分类  分为 admin 与  user 接口， 店铺 使用的是管理员绑定的用户接口使用图片
export function getAttachmentCateList(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/attachment/category/list`, { params })
    } else {
        return request.get(`${API_BASE_URLS.USER}/attachment/category/list`, { params })
    }

}
export function createAttachmentCate(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/attachment/category`, params)
    } else {
        return request.post(`${API_BASE_URLS.USER}/attachment/category`, params)
    }
}
export function updateAttachmentCate(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.put(`${API_BASE_URLS.ADMIN}/attachment/category/${params.id}`, params)

    }else{
        return request.put(`${API_BASE_URLS.USER}/attachment/category/${params.id}`, params)
    }
}
export function deleteAttachmentCate(id: number) {
    if (systemType === 'admin') {
        return request.delete(`${API_BASE_URLS.ADMIN}/attachment/category/${id}`)
    }else{
        return request.delete(`${API_BASE_URLS.USER}/attachment/category/${id}`)
    }
}


//附件
export function getAttachmentFilePages(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.get(`${API_BASE_URLS.ADMIN}/attachment/file/pages`, { params })
    }else{
        return request.get(`${API_BASE_URLS.USER}/attachment/file/pages`, { params })
    }
}

// 附件重命名 以及移动分类
export function updateBatchAttachmentFile(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/attachment/file/update-batch`, params)
    }else{
        return request.post(`${API_BASE_URLS.USER}/attachment/file/update-batch`, params)
    }
}

export function deleteAttachmentFile(params: Record<string, any>) {
    if (systemType === 'admin') {
        return request.post(`${API_BASE_URLS.ADMIN}/attachment/file/del-batch`, params, { showSuccessMessage: true })
    }else{
        return request.post(`${API_BASE_URLS.USER}/attachment/file/del-batch`, params, { showSuccessMessage: true })
    }
}


