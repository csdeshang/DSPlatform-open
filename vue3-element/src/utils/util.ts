import { cloneDeep } from 'lodash'



// 通过方法获取类型 方便后期使用多入口
export function getSystemType() {
  return import.meta.env.VITE_SYSTEM_TYPE;
}



/**
 * 处理数字精度，避免浮点数计算误差
 * @param num 需要处理的数字
 * @param precision 精度位数，默认2位小数
 */
export function toFixed(num: number, precision: number = 2): number {
  return Number(Number(num).toFixed(precision));
}


/**
 * 判断是否是url
 * @param str
 * @returns
 */
export function isUrl(str: string): boolean {
  return str.indexOf('http://') != -1 || str.indexOf('https://') != -1
}


/**
 * 处理非图片文件URL（视频、音频、文档等），根据存储类型补全完整路径
 * 注意：图片文件请使用 formatImageUrl 函数，以获得缩略图处理能力
 * @param url 原始地址（视频、音频、文档等文件URL）
 * @returns 完整的文件附件URL
 */
export function formatFileUrl(url: string): string {
  // 处理空URL情况
  if (!url) return '';

  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url;
  }

  // 获取环境变量中的基础URL，确保有默认值
  const fileBaseUrl = import.meta.env.VITE_FILE_BASE_URL || '';

  // 处理URL路径，确保正确添加斜杠
  return fileBaseUrl + (url.startsWith('/') ? url : '/' + url);
}



/**
 * @description 树转数组，队列实现广度优先遍历
 */

export const treeToArray = (data: any[], props = { children: 'children' }) => {
  data = cloneDeep(data)
  const { children } = props
  const newData = []
  const queue: any[] = []
  data.forEach((child: any) => queue.push(child))
  while (queue.length) {
    const item: any = queue.shift()
    if (item[children]) {
      item[children].forEach((child: any) => queue.push(child))
      delete item[children]
    }
    newData.push(item)
  }
  return newData
}

/**
 * @description 数组转

 */

export const arrayToTree = (
  data: any[],
  props = { id: 'id', parentId: 'pid', children: 'children' }
) => {
  data = cloneDeep(data)
  const { id, parentId, children } = props
  const result: any[] = []
  const map = new Map()
  data.forEach((item) => {
    map.set(item[id], item)
    const parent = map.get(item[parentId])
    if (parent) {
      parent[children] = parent[children] ?? []
      parent[children].push(item)
    } else {
      result.push(item)
    }
  })
  return result
}




/**
 * 防抖函数
 * @param {Function} func - 需要防抖处理的函数
 * @param {number} wait - 延迟时间，单位为毫秒
 * @param {boolean} immediate - 是否立即执行
 * @returns {Function} - 返回一个新的防抖处理函数
 */
export function debounce<T extends (...args: any[]) => any>(
  func: T,
  wait: number = 300,
  immediate: boolean = false
): T & { cancel: () => void } {
  let timeout: ReturnType<typeof setTimeout> | null = null;

  const debounced = function (this: any, ...args: Parameters<T>) {
    const context = this;

    if (timeout) clearTimeout(timeout);

    if (immediate && !timeout) {
      func.apply(context, args);
    }

    timeout = setTimeout(() => {
      if (!immediate) {
        func.apply(context, args);
      }
      timeout = null;
    }, wait);
  };

  debounced.cancel = () => {
    if (timeout) {
      clearTimeout(timeout);
      timeout = null;
    }
  };

  return debounced as T & { cancel: () => void };
}




