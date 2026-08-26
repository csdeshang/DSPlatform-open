/**
 * 商户关联用户（getMerchantInfo / getMerchantPages 的 user 关联）
 */
export interface MerchantUser {
  id?: number
  username?: string
  nickname?: string
  avatar?: string
}

/**
 * 商户（对齐 #__merchant 及 getMerchantInfo / getMerchantPages 返回字段）
 */
export interface Merchant {
  id?: number
  user_id?: number
  name?: string
  is_allow_payment?: number
  balance?: number
  balance_in?: number
  balance_out?: number
  contact_name?: string
  contact_phone?: string
  contact_address?: string
  is_enabled?: number
  sort?: number
  allowed_store_count?: number
  apply_status?: number
  apply_status_desc?: string
  apply_time?: string
  apply_remark?: string
  audit_time?: string
  audit_remark?: string
  version?: number
  create_at?: string
  update_at?: string
  is_deleted?: number
  deleted_at?: string
  user?: MerchantUser
}
