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


// 获取服务器ICON地址
export function fetchRemoteIconUrl(path: string): string {
  // 服务器ICON地址
  const baseUrl = import.meta.env.VITE_APP_BASE_URL + '/static/';
  // 参数校验
  if (typeof path !== 'string' || path.trim() === '') {
    throw new Error('图片路径不能为空');
  }
  // 确保 path 以斜杠开头
  const normalizedPath = path.startsWith('/') ? path : `/${path}`;
  // 手动拼接完整的 URL
  const imageUrl = `${baseUrl}${normalizedPath}`;
  // 返回完整的 URL
  return imageUrl;
}


/**
 * 处理文件附件URL，根据存储类型补全完整路径
 * @param url 原始地址
 * @returns 完整的文件附件URL
 */
export function formatFileUrl(url: string, fileType: string = 'default'): string {
  // 处理空URL情况
  if (!url && fileType === 'default') return fetchRemoteIconUrl('uniapp/empty/default_goods_image.png');
  if (!url && fileType === 'store_logo') return fetchRemoteIconUrl('uniapp/empty/default_store_logo.jpg');
  
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url;
  }
  
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




