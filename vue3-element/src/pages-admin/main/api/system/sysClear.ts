import request, { API_BASE_URLS } from '@/utils/request'


// 清理系统缓存
export function clearCache() {
    return request.post(`${API_BASE_URLS.ADMIN}/system/clear/cache`)
}

// 清理系统日志
export function clearLogs() {
    return request.post(`${API_BASE_URLS.ADMIN}/system/clear/logs`)
}

// 清理系统访问日志
export function clearSysAccessLogs() {
    return request.post(`${API_BASE_URLS.ADMIN}/system/clear/access-logs`)
}

// 清理系统错误日志
export function clearSysErrorLogs() {
    return request.post(`${API_BASE_URLS.ADMIN}/system/clear/error-logs`)
}

// 清理管理员日志
export function clearAdminLogs() {
    return request.post(`${API_BASE_URLS.ADMIN}/system/clear/admin-logs`)
}