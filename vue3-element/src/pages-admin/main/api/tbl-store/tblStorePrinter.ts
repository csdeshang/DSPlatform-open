import request, { API_BASE_URLS } from '@/utils/request'

// 获取店铺打印机列表（管理员查看）
export function getTblStorePrinterPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/printers/pages`, { params })
}

// 获取店铺打印机信息（管理员查看）
export function getTblStorePrinterInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/printers/${id}`)
}

// 获取店铺打印机日志列表（管理员查看）
export function getTblStorePrinterLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/printer-logs/pages`, { params })
} 