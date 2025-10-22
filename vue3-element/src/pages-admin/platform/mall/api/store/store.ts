import request, { API_BASE_URLS } from '@/utils/request';


// 默认使用 tblStore.ts 公共处理



// 创建Mall商城店铺
export function createMallStore(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/mall/store/createStore`, params )
}





