import { ElMessage } from 'element-plus'
import { useConfigStore } from '@/stores/modules/config'

/** 与 C 端 goStoreIndexPage 一致：需 id，platform 用于区分 food */
export type StorePreviewRow = {
  id: number | string
  platform?: string
}

function joinSitePath(siteBase: string, pathAndQuery: string): string {
  const base = siteBase.trim().replace(/\/?$/, '/')
  const rel = pathAndQuery.replace(/^\//, '')
  return new URL(rel, base).href
}

/**
 * PC 店铺页（对齐 nuxt-consumer/app/utils/platform.ts goStoreIndexPage）
 */
export function buildConsumerStorePcUrl(pcSiteBase: string, store: StorePreviewRow): string {
  const isFood = (store.platform ?? '').trim() === 'food'
  const id = String(store.id)
  const path = isFood ? `food/store/${id}` : `store/${id}`
  return joinSitePath(pcSiteBase, path)
}

/**
 * H5 店铺页（对齐 uniapp/src/utils/platform.ts goStoreIndexPage）
 */
export function buildConsumerStoreH5Url(h5SiteBase: string, store: StorePreviewRow): string {
  const isFood = (store.platform ?? '').trim() === 'food'
  const qs = new URLSearchParams({ store_id: String(store.id) })
  const path = isFood
    ? `home/pages/quick-store/index?${qs}`
    : `home/pages/store/index?${qs}`
  return joinSitePath(h5SiteBase, path)
}

export type StorePreviewKind = 'pc' | 'h5'

async function fetchWebsiteBase(kind: StorePreviewKind): Promise<string | null> {
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
 * C 端店铺预览：读 website 的 pc_url / h5_url，新窗口打开用户端店铺首页。
 */
export async function openStorePreview(
  store: StorePreviewRow,
  kind: StorePreviewKind
): Promise<void> {
  const base = await fetchWebsiteBase(kind)
  if (!base) return
  const url =
    kind === 'pc' ? buildConsumerStorePcUrl(base, store) : buildConsumerStoreH5Url(base, store)
  window.open(url, '_blank', 'noopener,noreferrer')
}
