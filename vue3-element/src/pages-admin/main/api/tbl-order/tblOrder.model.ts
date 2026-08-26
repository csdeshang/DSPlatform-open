/**
 * 订单（对齐 #__tbl_order 及详情接口关联字段）
 */
export interface TblOrder {
  id?: number
  platform?: string
  order_from?: string
  pay_merchant_id?: number
  pay_channel?: string
  pay_scene?: string
  order_merge_id?: number
  order_sn?: string
  order_status?: number
  order_status_desc?: string
  order_referrer_id?: number
  out_trade_no?: string
  trade_no?: string
  merchant_id?: number
  store_id?: number
  user_id?: number
  delivery_method?: string
  delivery_method_desc?: string
  original_amount?: number
  goods_amount?: number
  shipping_amount?: number
  discount_amount?: number
  order_amount?: number
  pay_amount?: number
  service_amount?: number
  invoice_status?: number
  invoice_status_desc?: string
  is_evaluate?: number
  refunding_count?: number
  refund_status?: number
  refund_amount?: number
  allow_refund_time?: string
  user_remark?: string
  store_remark?: string
  add_time?: string
  payment_time?: string
  delivery_time?: string
  shipping_time?: string
  finnshed_time?: string
  evaluate_time?: string
  close_time?: string
  cancel_time?: string
  is_deleted?: number
  store_available_actions?: string[]
  user?: Record<string, any>
  store?: Record<string, any>
  payMerchant?: Record<string, any>
  orderAddress?: Record<string, any>
  orderDelivery?: Record<string, any>
  orderFinance?: Record<string, any>
  orderGoodsList?: Record<string, any>[]
  orderLogList?: Record<string, any>[]
  orderRefundList?: Record<string, any>[]
}
