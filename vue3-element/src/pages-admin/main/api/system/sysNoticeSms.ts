import request, { API_BASE_URLS } from '@/utils/request'

// 短信记录分页
export function getSysNoticeSmsLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/sms/log/pages`, { params })
}

// 短信记录详情
export function getSysNoticeSmsLogInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/sms/log/${id}`);
}


