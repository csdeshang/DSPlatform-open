import request, { API_BASE_URLS } from '@/utils/request'


export function getTblStoreCouponPages(params: Record<string, any>) {
  return request.get(`${API_BASE_URLS.ADMIN}/tbl-store/coupon/pages`, { params })
}