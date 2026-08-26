/**
 * 骑手（对齐 #__rider 及 getRiderInfo 返回字段）
 */
export interface Rider {
  id?: number
  user_id?: number
  name?: string
  mobile?: string
  status?: number
  comment_count?: number
  avg_score?: number
  service_count?: number
  balance?: number
  balance_in?: number
  balance_out?: number
  is_enabled?: number
  apply_status?: number
  apply_status_desc?: string
  apply_remark?: string
  audit_time?: string
  audit_remark?: string
  create_at?: string
  update_at?: string
  is_deleted?: number
}
