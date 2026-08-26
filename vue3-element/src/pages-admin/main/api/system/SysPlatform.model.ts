/**
 * 系统平台（对齐 #__sys_platform 及 getSysPlatformList 返回字段）
 */
export interface SysPlatform {
  id?: number
  name?: string
  platform?: string
  scene?: string
  icon?: string
  version?: string
  description?: string
  sort?: number
  is_enable?: number
  create_at?: string
  create_by?: string
  update_at?: string
  update_by?: string
}
