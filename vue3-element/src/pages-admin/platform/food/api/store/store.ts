import request, { API_BASE_URLS } from '@/utils/request';


// 默认使用 tblStore.ts 公共处理



// 创建外卖店铺
export function createFoodStore(params: Record<string, any>) {
  return request.post(`${API_BASE_URLS.ADMIN}/food/store/createStore`, params )
}





