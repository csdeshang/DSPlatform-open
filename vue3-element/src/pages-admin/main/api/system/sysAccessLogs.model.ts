export interface SysAccessLog {
  id?: number
  user_id?: number
  username?: string
  ip?: string
  method?: string
  root?: string
  controller?: string
  action?: string
  url?: string
  params?: string
  result?: string
  duration?: number
  http_code?: string
  code?: string
  create_at?: string
}

export interface SysAccessLogDetailItem {
  field: string
  value: any
}
