
import request, { API_BASE_URLS } from '@/utils/request'



// 获取系统信息
export function getSystemInfo() {
    return request.get(`${API_BASE_URLS.ADMIN}/system/system-info`)
}




