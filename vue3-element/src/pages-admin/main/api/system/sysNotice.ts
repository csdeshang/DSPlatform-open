import request, { API_BASE_URLS } from '@/utils/request'


// 消息通知模板列表
export function getSysNoticeTplList(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/tpl/list`, { params })
}

// 消息通知模板详情
export function getSysNoticeTplInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/tpl/${id}`);
}

// 消息通知模板更新 
export function updateSysNoticeTpl(params: Record<string, any>) {
    return request.put(`${API_BASE_URLS.ADMIN}/system/notice/tpl/${params.id}`, params)
}


// 消息通知日志分页
export function getSysNoticeLogPages(params: Record<string, any>) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/log/pages`, { params })
}

// 消息通知日志详情
export function getSysNoticeLogInfo(id: number) {
    return request.get(`${API_BASE_URLS.ADMIN}/system/notice/log/${id}`);
}

// 测试消息通知模板
export function testNoticeTemplate(params: Record<string, any>) {
    return request.post(`${API_BASE_URLS.ADMIN}/system/notice/tpl/test`, params)
}

