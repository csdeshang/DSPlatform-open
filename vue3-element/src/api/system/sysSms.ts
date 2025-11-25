
import request, { API_BASE_URLS } from '@/utils/request'



// 系统登录 注册 找回密码  修改绑定手机号 通用发送验证码
export function sendSms(params: any) {
    return request.post(`${API_BASE_URLS.USER}/system/sms`, params)
}




