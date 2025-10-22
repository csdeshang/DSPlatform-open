import request, { API_BASE_URLS } from '@/utils/request'

// 师傅余额日志
export function getTechnicianBalanceLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/technician/balance-log/pages`, { params })
}

// 师傅余额调整
export function modifyTechnicianBalance(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/technician/balance/modifyTechnicianBalance`, params)
} 