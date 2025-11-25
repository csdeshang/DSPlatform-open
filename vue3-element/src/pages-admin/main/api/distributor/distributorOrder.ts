import request, { API_BASE_URLS } from '@/utils/request';


// 分销商订单列表
export const getDistributorOrderList = (params: any) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/orders/list`, { params });
};

// 分销商订单分页列表
export const getDistributorOrderPages = (params: any) => {
    return request.get(`${API_BASE_URLS.ADMIN}/distributor/orders/pages`, { params });
  };