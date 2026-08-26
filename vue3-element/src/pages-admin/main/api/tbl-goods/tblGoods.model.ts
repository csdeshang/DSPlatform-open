/**
 * 商品（对齐 #__tbl_goods 及 getTblGoodsInfo 返回字段）
 */
export interface TblGoods {
  id?: number
  platform?: string
  goods_name?: string
  goods_advword?: string
  goods_minprice?: number
  goods_status?: number
  goods_status_desc?: string
  store_id?: number
  brand_id?: number
  store_goods_cid?: number
  cover_image?: string
  stock_num?: number
  goods_sort?: number
  click_num?: number
  sales_num?: number
  virtual_sales_num?: number
  collect_num?: number
  evaluate_num?: number
  avg_goods_score?: number
  is_distributor_goods?: number
  sys_status?: number
  sys_status_desc?: string
  sys_status_reason?: string
  sys_recommend_status?: number
  create_at?: string
  update_at?: string
}
