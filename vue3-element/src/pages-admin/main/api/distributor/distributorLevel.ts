import request, { API_BASE_URLS } from '@/utils/request';


// 分销商等级列表
export const getDistributorLevelList = (params: any) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/levels/list`, { params });
};

// 分销商等级详情
export const getDistributorLevelInfo = (id: number) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/levels/${id}`);
};

// 新增
export const createDistributorLevel = (params: any) => {
  return request.post(`${API_BASE_URLS.ADMIN}/distributor/levels`, params);
};

// 编辑
export const updateDistributorLevel = (params: any) => {
  const { id, ...restParams } = params;
  return request.put(`${API_BASE_URLS.ADMIN}/distributor/levels/${id}`, restParams);
};

// 删除
export const deleteDistributorLevel = (id: number) => {
  return request.delete(`${API_BASE_URLS.ADMIN}/distributor/levels/${id}`);
};





