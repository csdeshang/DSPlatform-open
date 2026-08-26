export interface SysErrorLog {
  id?: number
  file?: string
  line?: number
  message?: string
  code?: string
  exception_class?: string
  ip?: string
  method?: string
  root?: string
  controller?: string
  action?: string
  url?: string
  params?: string
  duration?: number
  previous?: string
  create_at?: string
}

export interface SysErrorLogDetailItem {
  field: string
  value: any
}
