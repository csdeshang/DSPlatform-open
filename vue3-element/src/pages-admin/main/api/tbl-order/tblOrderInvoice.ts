import request, { API_BASE_URLS } from '@/utils/request'

export function getTblOrderInvoicePages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/invoices/pages`, { params })
}

export function getTblOrderInvoiceInfo(id: number) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-order/invoices/${id}`)
}
