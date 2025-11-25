import request, { API_BASE_URLS } from '@/utils/request'


export function getTechnicianPage(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/technician/technicians/pages`, { params })
}

export function getTechnicianInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/technician/technicians/${id}`)
}

export function updateTechnician(data: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/technician/technicians/${data.id}`, data)
}

// 师傅店铺绑定更换
export function updateTechnicianBindStore(data: Record<string, any>) {
  return request.put(`${API_BASE_URLS.ADMIN}/technician/technicians/${data.id}/bind-store`, data)
}



