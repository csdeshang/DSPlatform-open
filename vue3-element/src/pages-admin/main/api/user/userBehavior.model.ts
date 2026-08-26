export interface UserBehaviorLog {
  id?: number
  user_id?: number
  username?: string
  behavior_type?: string
  behavior_type_desc?: string
  behavior_scene?: string
  ip_address?: string
  user_agent?: string
  device_type?: string
  browser?: string
  os?: string
  behavior_status?: number
  behavior_status_desc?: string
  failure_reason?: string
  is_abnormal?: number
  is_abnormal_desc?: string
  abnormal_reason?: string
  risk_level?: number
  risk_level_desc?: string
  extra_data?: string
  create_at?: string
}

export interface UserBehaviorLogDetailItem {
  field: string
  value: any
}
