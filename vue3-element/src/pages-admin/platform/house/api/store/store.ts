import request, { API_BASE_URLS } from '@/utils/request';


// 默认使用 tblStore.ts 公共处理



// 创建家政店铺
export function createHouseStore(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/house/store/createStore`, params )
}





