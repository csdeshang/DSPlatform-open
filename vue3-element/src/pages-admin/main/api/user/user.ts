import request, { API_BASE_URLS } from '@/utils/request'


export function getUserPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/user/pages`, { params })
}

export function getUserInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/user/${ id }`);
}


export function createUser(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/user/user`,   params )
}

export function updateUser(params: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/user/user/${ params.id }`,   params )
}

export function deleteUser(id: number) {
  return request.delete(`${API_BASE_URLS.ADMIN}/user/user/${ id }`)
}

//获取用户推广关系列表
export function getUserRelationList(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/user/user/relation/list`, { params })
}




