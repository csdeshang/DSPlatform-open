import request, { API_BASE_URLS } from '@/utils/request'


export function getUserIdentityList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/user/identity/list`, { params })
}




