/**
 * 用户（对齐 #__user 及 getUserInfo / getUserPage 返回字段）
 */
export interface User {
  id?: number
  username?: string
  nickname?: string
  avatar?: string
  sex?: number
  birthday?: string
  email?: string
  email_bind?: number
  mobile?: string
  mobile_bind?: number
  qq?: string
  password?: string
  pay_password?: string
  inviter_id?: number
  login_num?: number
  login_time?: string
  login_ip?: string
  old_login_time?: string
  old_login_ip?: string
  growth?: number
  growth_level_id?: number
  points?: number
  points_in?: number
  points_out?: number
  balance?: number
  balance_in?: number
  balance_out?: number
  idcard_status?: number
  idcard_name?: string
  idcard_number?: string
  idcard_image1?: string
  idcard_image2?: string
  idcard_image3?: string
  is_enabled?: number
  is_deleted?: number
  is_distributor?: number
  distributor_status?: number
  distributor_level_id?: number
  distributor_balance?: number
  distributor_balance_in?: number
  distributor_balance_out?: number
  distributor_addtime?: string
  create_at?: string
  update_at?: string
}
