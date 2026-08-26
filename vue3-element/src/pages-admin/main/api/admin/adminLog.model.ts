export interface AdminLog {
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
  duration?: number
  http_code?: number
  code?: number
  result?: string
  create_at?: string
}

export interface AdminLogDetailItem {
  field: string
  value: any
}
