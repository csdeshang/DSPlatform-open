import request, { API_BASE_URLS } from '@/utils/request'


export function getUserGrowthLevelList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/growth-levels/list`, { params })
}

export function getUserGrowthLevelInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/growth-levels/${ id }`);
}


export function createUserGrowthLevel(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/user/growth-levels`,   params )
}

export function updateUserGrowthLevel(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/user/growth-levels/${ params.id }`,   params )
}

export function deleteUserGrowthLevel(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/user/growth-levels/${ id }`)
}





