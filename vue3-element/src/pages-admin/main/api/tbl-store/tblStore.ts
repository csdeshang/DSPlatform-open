import request, { API_BASE_URLS } from '@/utils/request'


export function getTblStorePages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/stores/pages`, { params })
}

// 获取店铺列表
export function getTblStoreList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/stores/list`, { params })
}


export function getTblStoreInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/stores/${ id }`);
}

// 创建店铺 需要根据平台类型，选择不同的接口
export function createTblStore(params: Record<string, any>) {
  // return request.post(`${API_BASE_URLS.ADMIN}/tbl-store/stores`,   params )
}

export function updateTblStore(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/tbl-store/stores/${params.id}`,   params )
}


export function deleteTblStore(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/tbl-store/stores/${id}`)
}

// 审核店铺申请
export function auditTblStore(params: Record<string, any>) {
  return request.patch(`${API_BASE_URLS.ADMIN}/tbl-store/stores/${params.id}/audit`, params)
}


