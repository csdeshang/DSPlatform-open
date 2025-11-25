import request, { API_BASE_URLS } from '@/utils/request'



export function getSysExpressList(params: Record<string, any>) {
    // 使用用户接口 获取系统快递公司列表
    return request.get(`${API_BASE_URLS.USER}/system/expresses/list`, { params })
}
