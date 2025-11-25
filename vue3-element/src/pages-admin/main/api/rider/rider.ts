import request, { API_BASE_URLS } from '@/utils/request'


export function getRiderPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/riders/pages`, { params })
}

export function getRiderInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/rider/riders/${ id }`);
}


export function createRider(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/rider/riders`,   params )
}

export function updateRider(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/rider/riders/${ params.id }`,   params )
}





