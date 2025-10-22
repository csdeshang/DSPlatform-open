import request, { API_BASE_URLS } from '@/utils/request';


// 分销商等级列表
export const getDistributorOrderList = (params: any) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/order/list`, { params });
};


// 分销商等级列表
export const getDistributorOrderPages = (params: any) => {
    return request.get(`${API_BASE_URLS.ADMIN}/distributor/order/pages`, { params });
  };