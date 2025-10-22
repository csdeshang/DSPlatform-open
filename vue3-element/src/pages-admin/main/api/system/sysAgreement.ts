import request, { API_BASE_URLS } from '@/utils/request'

export function getSysAgreementList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/agreement/list`, { params })
}

export function getSysAgreementInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/system/agreement/${id}`)
}

export function createSysAgreement(data: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/system/agreement`, data)
}

export function updateSysAgreement(data: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/system/agreement/${data.id}`, data)
}
