import request, { API_BASE_URLS } from '@/utils/request'


export function getTechnicianPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/technician/technician/pages`, { params })
}

export function getTechnicianInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/technician/technician/${id}`)
}

export function updateTechnician(data: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/technician/technician/${data.id}`, data)
}

// 师傅店铺绑定更换
export function updateTechnicianBindStore(data: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/technician/technician/${data.id}/bind-store`, data)
}



