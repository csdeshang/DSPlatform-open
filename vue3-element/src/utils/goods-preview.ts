import { ElMessage } from 'element-plus'
import { useConfigStore } from '@/stores/modules/config'

/** 与 C 端 goGoodsDetailPage 入参一致：至少 id，可选 platform / spec_id */
export type GoodsPreviewRow = {
  id: number | string
  platform?: string
  spec_id?: number | string
}

function joinSitePath(siteBase: string, pathAndQuery: string): string {
  const base = siteBase.trim().replace(/\/?$/, '/')
  const rel = pathAndQuery.replace(/^\//, '')
  return new URL(rel, base).href
}

function goodsDetailQuery(goods: Pick<GoodsPreviewRow, 'id' | 'spec_id'>): string {
  const qs = new URLSearchParams({ goods_id: String(goods.id) })
  const sid = goods.spec_id
  if (sid != null && sid !== '' && Number(sid) !== 0) {
    qs.set('spec_id', String(sid))
  }
  return qs.toString()
}

/**
 * PC 商品详情地址（对齐 nuxt-consumer/app/utils/platform.ts goGoodsDetailPage）
 * platform = goods.platform || 'mall'，query：goods_id、可选 spec_id
 */
export function buildConsumerGoodsPcDetailUrl(
  pcSiteBase: string,
  goods: GoodsPreviewRow
): string {
  const p = (goods.platform ?? '').trim() || 'mall'
  const qs = goodsDetailQuery(goods)
  return joinSitePath(pcSiteBase, `${p}/goods/detail?${qs}`)
}

/**
 * H5 商品详情地址（对齐 uniapp/src/utils/platform.ts goGoodsDetailPage）
 * 有 platform → home/platform/{platform}/pages/goods/detail/index
 * 无 → home/pages/goods/detail；query 与列表行一致（含可选 spec_id）
 */
export function buildConsumerGoodsH5DetailUrl(
  h5SiteBase: string,
  goods: GoodsPreviewRow
): string {
  const p = (goods.platform ?? '').trim()
  const qs = goodsDetailQuery(goods)
  if (p) {
    return joinSitePath(
      h5SiteBase,
      `home/platform/${p}/pages/goods/detail/index?${qs}`
    )
  }
  return joinSitePath(h5SiteBase, `home/pages/goods/detail?${qs}`)
}

export type GoodsPreviewKind = 'pc' | 'h5'

async function fetchWebsiteBase(kind: GoodsPreviewKind): Promise<string | null> {
  const configStore = useConfigStore()
  const cfg = await configStore.fetchConfigsByType('website')
  const base = (kind === 'pc' ? cfg.pc_url : cfg.h5_url)?.trim()
  if (!base) {
    ElMessage.warning(
      kind === 'pc'
        ? '请先在后台「网站基础配置」中填写 PC 端地址（pc_url）'
        : '请先在后台「网站基础配置」中填写 H5 端地址（h5_url）'
    )
    return null
  }
  return base
}

/**
 * C 端商品预览：读 website 的 pc_url / h5_url，新窗口打开详情。
 * kind：'pc' 对齐 nuxt goGoodsDetailPage，'h5' 对齐 uniapp goGoodsDetailPage。
 */
export async function openGoodsPreview(
  row: GoodsPreviewRow,
  kind: GoodsPreviewKind
): Promise<void> {
  const base = await fetchWebsiteBase(kind)
  if (!base) return
  const url =
    kind === 'pc'
      ? buildConsumerGoodsPcDetailUrl(base, row)
      : buildConsumerGoodsH5DetailUrl(base, row)
  window.open(url, '_blank', 'noopener,noreferrer')
}
