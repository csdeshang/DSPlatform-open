import request, { API_BASE_URLS } from '@/utils/request'


export function getRiderPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/rider/pages`, { params })
}

export function getRiderInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/rider/${ id }`);
}


export function createRider(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/rider/rider`,   params )
}

export function updateRider(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/rider/rider/${ params.id }`,   params )
}





