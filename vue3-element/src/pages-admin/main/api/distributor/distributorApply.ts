import request, { API_BASE_URLS } from '@/utils/request';


// 分销商申请列表
export const getDistributorApplyPages = (params: any) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/apply/pages`, { params });
};

// 审核申请
export const auditDistributorApply = (params: any) => {
  return request.post(`${API_BASE_URLS.ADMIN}/distributor/apply/audit`, params);
};




