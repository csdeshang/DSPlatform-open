import request, { API_BASE_URLS } from '@/utils/request';


// 分销商申请列表
export const getDistributorApplyPages = (params: any) => {
  return request.get(`${API_BASE_URLS.ADMIN}/distributor/applies/pages`, { params });
};

// 审核申请
export const auditDistributorApply = (params: any) => {
  const { id, ...restParams } = params;
  return request.patch(`${API_BASE_URLS.ADMIN}/distributor/applies/${id}/audit`, restParams);
};




