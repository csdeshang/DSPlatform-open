import request, { API_BASE_URLS } from '@/utils/request';


// 默认使用 tblStore.ts 公共处理



// 创建快卖店铺
export function createKmsStore(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/kms/stores`, params )
}





