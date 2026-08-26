/**
 * 系统快递公司（对齐 #__sys_express 及 getSysExpressList 返回字段）
 */
export interface SysExpress {
  id?: number
  code?: string
  kdniao_code?: string
  kd100_code?: string
  name?: string
  logo?: string
  url?: string
  sort?: number
  is_show?: number
  create_at?: string
  update_at?: string
}
