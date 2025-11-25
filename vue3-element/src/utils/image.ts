/**
 * ============================================================================
 * 重要说明：此文件与 uniapp/src/utils/image.ts 的核心逻辑完全一致
 * ============================================================================
 * 
 * 两个文件的函数实现、接口定义、处理逻辑完全相同
 * 唯一的区别在于 ThumbnailPresets 的尺寸参数：
 * - PC端（vue-element-admin）：使用较大的尺寸以适应大屏幕
 *   small: 150x150, medium: 500x500, large: 1200x1200
 * - 移动端（uniapp）：使用较小的尺寸以节省流量
 *   small: 100x100, medium: 360x360, large: 750x750
 * 
 * 修改此文件时，请同步更新 uniapp/src/utils/image.ts
 * ============================================================================
 */

// vue-element-admin/src/utils/image.ts

/**
 * 图片URL处理和缩略图工具
 * 支持阿里云OSS、腾讯云COS等云存储的URL参数处理
 * 
 * 注意：此文件与 uniapp/src/utils/image.ts 的核心逻辑完全一致
 * 唯一的区别在于 ThumbnailPresets 的尺寸参数：
 * - PC端（vue-element-admin）：使用较大的尺寸以适应大屏幕（small: 150x150, medium: 500x500, large: 1200x1200）
 * - 移动端（uniapp）：使用较小的尺寸以节省流量（small: 100x100, medium: 360x360, large: 750x750）
 */

/**
 * 存储类型
 */
export type StorageType = 'local' | 'aliyun' | 'tencent' | 'qiniu';

/**
 * 缩略图配置接口
 */
export interface ThumbnailOptions {
  /** 缩略图宽度（像素），只指定宽度时自动按比例缩放 */
  width?: number;
  /** 缩略图高度（像素），只指定高度时自动按比例缩放 */
  height?: number;
  /** 缩放模式 */
  mode?: "lfit" | "fixed" | "fill" | "pad" | "mfit";
  /** 图片质量 1-100 */
  quality?: number;
  /** 图片格式转换 */
  format?: "jpg" | "png" | "webp" | "bmp" | "gif";
  /** 是否启用渐进式JPEG */
  progressive?: boolean;
  /** 是否启用锐化 */
  sharpen?: boolean;
  /** 是否使用原图（不缩放，只应用质量、格式等参数） */
  useOriginal?: boolean;
}

/**
 * 图片类型（用于默认图片）
 */
export type ImageType = 'default' | 'store_logo' | 'avatar' | 'goods' | 'banner';

/**
 * 获取服务器ICON地址
 * @param path 图片路径
 * @returns 完整的图片URL
 */
export function fetchRemoteIconUrl(path: string): string {
  const baseUrl = import.meta.env.VITE_APP_BASE_URL + '/static/';
  if (typeof path !== 'string' || path.trim() === '') {
    throw new Error('图片路径不能为空');
  }
  const normalizedPath = path.startsWith('/') ? path : `/${path}`;
  return `${baseUrl}${normalizedPath}`;
}

/**
 * 获取默认图片URL
 * @param imageType 图片类型
 * @returns 默认图片URL
 */
function getDefaultImageUrl(imageType: ImageType): string {
  const defaultImages: Record<ImageType, string> = {
    default: 'uniapp/empty/default_goods_image.png',
    store_logo: 'uniapp/empty/default_store_logo.jpg',
    avatar: 'uniapp/empty/default_avatar.png',
    goods: 'uniapp/empty/default_goods_image.png',
    banner: 'uniapp/empty/default_goods_image.png',
  };
  return fetchRemoteIconUrl(defaultImages[imageType] || defaultImages.default);
}

/**
 * 处理图片URL，根据存储类型补全完整路径并支持缩略图
 * @param url 原始图片地址
 * @param thumbnail 缩略图配置（可选）
 * @param imageType 图片类型（用于空URL时返回默认图片，默认值为 'default'）
 * @returns 完整的图片URL（带缩略图参数）
 * 
 * @example
 * // 基础用法（不生成缩略图）
 * formatImageUrl('/path/to/image.jpg')
 * 
 * @example
 * // 生成200x200的缩略图（使用默认 imageType）
 * formatImageUrl('/path/to/image.jpg', { width: 200, height: 200 })
 * 
 * @example
 * // 使用预设配置（使用默认 imageType）
 * import { ThumbnailPresets } from '@/utils/image'
 * formatImageUrl('/path/to/image.jpg', ThumbnailPresets.medium)
 * 
 * @example
 * // 生成缩略图并转换格式为webp（使用默认 imageType）
 * formatImageUrl('/path/to/image.jpg', {
 *   width: 200,
 *   height: 200,
 *   format: 'webp',
 *   quality: 85
 * })
 * 
 * @example
 * // 只指定宽度，高度自动按比例缩放（使用默认 imageType）
 * formatImageUrl('/path/to/image.jpg', { width: 400 })
 * 
 * @example
 * // 使用原图，只优化质量和格式（使用默认 imageType）
 * formatImageUrl('/path/to/image.jpg', {
 *   useOriginal: true,
 *   format: 'webp',
 *   quality: 85
 * })
 * 
 * @example
 * // 指定缩略图和图片类型
 * formatImageUrl('/path/to/image.jpg', ThumbnailPresets.medium, 'goods')
 * 
 * @example
 * // 只指定图片类型（不生成缩略图）
 * formatImageUrl('/path/to/image.jpg', undefined, 'goods')
 */
export function formatImageUrl(
  url: string,
  thumbnail?: ThumbnailOptions,
  imageType: ImageType = 'default'
): string {
  // 处理空URL情况，返回默认图片
  if (!url) {
    return getDefaultImageUrl(imageType);
  }

  let fullUrl: string;

  // 如果已经是完整URL
  if (url.startsWith('http://') || url.startsWith('https://')) {
    fullUrl = url;
  } else {
    // 获取环境变量中的基础URL，确保有默认值
    const fileBaseUrl = import.meta.env.VITE_FILE_BASE_URL || '';
    
    // 处理URL路径，确保正确添加斜杠
    fullUrl = fileBaseUrl + (url.startsWith('/') ? url : '/' + url);
  }

  // 如果需要缩略图，处理图片URL
  if (thumbnail) {
    return processImageUrl(fullUrl, thumbnail);
  }

  return fullUrl;
}

/**
 * 获取存储类型（从环境变量）
 * @returns 存储类型
 */
export function getStorageType(): StorageType {
  const fileType = (import.meta.env.VITE_FILE_TYPE || 'local').toLowerCase() as StorageType;
  
  // 验证存储类型是否有效
  const validTypes: StorageType[] = ['local', 'aliyun', 'tencent', 'qiniu'];
  if (validTypes.includes(fileType)) {
    return fileType;
  }
  
  // 默认返回 local
  return 'local';
}


/**
 * 为阿里云OSS URL添加缩略图处理参数
 * @param url 原始OSS URL
 * @param options 缩略图配置
 * @returns 带缩略图参数的URL
 */
export function addOssThumbnail(
  url: string,
  options: ThumbnailOptions
): string {
  try {
    const urlObj = new URL(url);

    // 移除可能已存在的处理参数，避免重复
    urlObj.searchParams.delete("x-oss-process");

    const processParams: string[] = [];

    // 1. 缩放参数（如果不需要缩放，跳过）
    if (!options.useOriginal && (options.width || options.height)) {
      // 如果只指定宽度或高度，自动使用 lfit 模式（同比例缩放）
      const mode = options.mode || (options.width && options.height ? "lfit" : "lfit");
      const modeMap: Record<string, string> = {
        lfit: "m_lfit", // 等比缩放，限制在指定w与h的矩形内的最大图片（推荐）
        fixed: "m_fixed", // 固定宽高，强制缩放（可能变形）
        fill: "m_fill", // 等比缩放，短边优先（长边裁剪）
        pad: "m_pad", // 等比缩放，填充背景色
        mfit: "m_mfit", // 等比缩放，宽高都小于指定值
      };

      const resizeParams: string[] = [`image/resize,${modeMap[mode]}`];

      if (options.width) {
        resizeParams.push(`w_${options.width}`);
      }
      if (options.height) {
        resizeParams.push(`h_${options.height}`);
      }

      // 添加limit_0参数，允许处理大于4096px的图片
      resizeParams.push("limit_0");

      processParams.push(resizeParams.join(","));
    }

    // 2. 质量参数（可选）
    if (options.quality && options.quality >= 1 && options.quality <= 100) {
      processParams.push(`image/quality,q_${options.quality}`);
    }

    // 3. 格式转换（可选）
    if (options.format) {
      processParams.push(`image/format,${options.format}`);
    }

    // 4. 渐进式JPEG（可选）
    if (options.progressive) {
      processParams.push("image/interlace,1");
    }

    // 5. 锐化（可选）
    if (options.sharpen) {
      processParams.push("image/sharpen,100");
    }

    // 如果没有处理参数，直接返回原URL
    if (processParams.length === 0) {
      return url;
    }

    // 拼接处理参数
    const processString = processParams.join("/");
    urlObj.searchParams.set("x-oss-process", processString);

    return urlObj.toString();
  } catch (error) {
    // URL解析失败，返回原URL
    console.warn("OSS URL处理失败:", error);
    return url;
  }
}

/**
 * 为腾讯云COS URL添加缩略图处理参数
 * @param url 原始COS URL
 * @param options 缩略图配置
 * @returns 带缩略图参数的URL
 */
export function addCosThumbnail(
  url: string,
  options: ThumbnailOptions
): string {
  try {
    const urlObj = new URL(url);

    // 移除可能已存在的处理参数
    urlObj.searchParams.delete("imageView2");
    urlObj.searchParams.delete("imageMogr2");

    const params: string[] = [];

    // 腾讯云COS使用 imageView2 参数
    if (!options.useOriginal && (options.width || options.height)) {
      // 如果只指定宽度或高度，自动使用 lfit 模式（同比例缩放）
      const mode = options.mode || (options.width && options.height ? "lfit" : "lfit");
      // 腾讯云COS模式：1=限定宽高最小值，2=限定宽高最大值，3=限定宽度，4=限定高度
      const modeMap: Record<string, number> = {
        lfit: 2, // 等比缩放，限制在指定宽高内（推荐）
        fixed: 1, // 固定宽高（可能变形）
        fill: 1, // 填充
        mfit: 1, // 最小边
        pad: 1, // 填充
      };

      // 如果只指定宽度，使用模式3；只指定高度，使用模式4
      let cosMode: number;
      if (options.width && !options.height) {
        cosMode = 3; // 限定宽度
      } else if (!options.width && options.height) {
        cosMode = 4; // 限定高度
      } else {
        cosMode = modeMap[mode] || 2;
      }

      params.push(`${cosMode}`);

      if (options.width) {
        params.push(`w/${options.width}`);
      }
      if (options.height) {
        params.push(`h/${options.height}`);
      }
    }

    // 质量参数
    if (options.quality && options.quality >= 1 && options.quality <= 100) {
      params.push(`q/${options.quality}`);
    }

    // 格式转换
    if (options.format) {
      params.push(`format/${options.format}`);
    }

    if (params.length > 0) {
      urlObj.searchParams.set("imageView2", params.join("/"));
    }

    return urlObj.toString();
  } catch (error) {
    console.warn("COS URL处理失败:", error);
    return url;
  }
}

/**
 * 为七牛云URL添加缩略图处理参数
 * @param url 原始七牛云URL
 * @param options 缩略图配置
 * @returns 带缩略图参数的URL
 */
export function addQiniuThumbnail(
  url: string,
  options: ThumbnailOptions
): string {
  try {
    const urlObj = new URL(url);

    // 移除可能已存在的处理参数
    urlObj.searchParams.delete("imageView2");
    urlObj.searchParams.delete("imageMogr2");

    const params: string[] = [];

    // 七牛云使用 imageView2 参数
    if (!options.useOriginal && (options.width || options.height)) {
      // 如果只指定宽度或高度，自动使用 lfit 模式（同比例缩放）
      const mode = options.mode || (options.width && options.height ? "lfit" : "lfit");
      // 七牛云模式：1=限定宽高最小值，2=限定宽高最大值，3=限定宽度，4=限定高度
      const modeMap: Record<string, number> = {
        lfit: 2, // 等比缩放，限制在指定宽高内（推荐）
        fixed: 1, // 固定宽高（可能变形）
        fill: 1, // 填充
        mfit: 1, // 最小边
        pad: 1, // 填充
      };

      // 如果只指定宽度，使用模式3；只指定高度，使用模式4
      let qiniuMode: number;
      if (options.width && !options.height) {
        qiniuMode = 3; // 限定宽度
      } else if (!options.width && options.height) {
        qiniuMode = 4; // 限定高度
      } else {
        qiniuMode = modeMap[mode] || 2;
      }

      params.push(`${qiniuMode}`);

      if (options.width) {
        params.push(`w/${options.width}`);
      }
      if (options.height) {
        params.push(`h/${options.height}`);
      }
    }

    // 质量参数
    if (options.quality && options.quality >= 1 && options.quality <= 100) {
      params.push(`q/${options.quality}`);
    }

    // 格式转换
    if (options.format) {
      params.push(`format/${options.format}`);
    }

    if (params.length > 0) {
      urlObj.searchParams.set("imageView2", params.join("/"));
    }

    return urlObj.toString();
  } catch (error) {
    console.warn("七牛云URL处理失败:", error);
    return url;
  }
}

/**
 * 为Nginx URL添加缩略图处理参数
 * @param url 原始URL
 * @param options 缩略图配置
 * @returns 带缩略图参数的URL
 */
export function addNginxThumbnail(
  url: string,
  options: ThumbnailOptions
): string {
  try {
    const urlObj = new URL(url);

    // 移除可能已存在的处理参数
    urlObj.searchParams.delete("w");
    urlObj.searchParams.delete("h");
    urlObj.searchParams.delete("q");
    urlObj.searchParams.delete("format");

    // 如果使用原图，只添加质量和格式参数
    if (options.useOriginal) {
      if (options.quality && options.quality >= 1 && options.quality <= 100) {
        urlObj.searchParams.set("q", options.quality.toString());
      }
      if (options.format) {
        urlObj.searchParams.set("format", options.format);
      }
      return urlObj.toString();
    }

    // 缩放参数（只指定宽度或高度时，Nginx会自动按比例缩放）
    if (options.width) {
      urlObj.searchParams.set("w", options.width.toString());
    }
    if (options.height) {
      urlObj.searchParams.set("h", options.height.toString());
    }

    // 质量参数
    if (options.quality && options.quality >= 1 && options.quality <= 100) {
      urlObj.searchParams.set("q", options.quality.toString());
    }

    // 格式转换（如果Nginx支持）
    if (options.format) {
      urlObj.searchParams.set("format", options.format);
    }

    return urlObj.toString();
  } catch (error) {
    console.warn("Nginx URL处理失败:", error);
    return url;
  }
}

/**
 * 处理图片URL，根据配置的存储类型添加缩略图参数
 * @param url 原始图片URL
 * @param thumbnail 缩略图配置
 * @returns 处理后的URL
 */
export function processImageUrl(
  url: string,
  thumbnail?: ThumbnailOptions
): string {
  // 如果没有缩略图配置，直接返回原图
  if (
    !thumbnail ||
    (!thumbnail.width &&
      !thumbnail.height &&
      !thumbnail.quality &&
      !thumbnail.format &&
      !thumbnail.useOriginal)
  ) {
    return url;
  }

  // 从环境变量获取存储类型
  const type = getStorageType();

  // 根据存储类型处理（必须通过环境变量配置）
  switch (type) {
    case 'aliyun':
      return addOssThumbnail(url, thumbnail);
      
    case 'tencent':
      return addCosThumbnail(url, thumbnail);
      
    case 'qiniu':
      return addQiniuThumbnail(url, thumbnail);
      
    case 'local':
      // 本地存储：使用Nginx参数处理（如果配置了Nginx image_filter模块）
      // 即使没有配置Nginx，添加参数也不会影响原图访问
      return addNginxThumbnail(url, thumbnail);
      
    default:
      // 环境变量未配置或配置错误，直接返回原URL（不处理缩略图）
      return url;
  }
}

/**
 * 预设的缩略图配置（PC端）
 * 
 * 使用说明：
 * - small: 小缩略图（150x150），适用于头像、图标等小尺寸场景（PC端）
 * - compact: 紧凑型缩略图（200x200），适用于Logo、品牌标识等
 * - medium: 中等缩略图（500x500），适用于列表页、卡片等场景（PC端）
 * - large: 大缩略图（1200x1200），适用于详情页、预览等场景（PC端）
 * - original: 原图（不缩放），只优化质量和格式，适用于详情页大图
 * 
 * 尺寸标准说明（PC端）：
 * - 150px：PC端头像、图标标准尺寸
 * - 200px：Logo、品牌标识标准尺寸
 * - 500px：PC端列表页标准尺寸，兼顾清晰度和加载速度
 * - 1200px：PC端详情页标准尺寸，适配大屏幕显示
 * 
 * 自定义尺寸示例：
 * - 列表页：使用 medium（500x500）或自定义 { width: 500, height: 500 }
 * - 详情页：使用 large（1200x1200）或自定义 { width: 1200, height: 1200 }
 * - 头像：使用 small（150x150）或自定义 { width: 120, height: 120, mode: 'fixed' }
 * - 店铺Logo：使用 compact（200x200）或自定义 { width: 200, height: 200, mode: 'fixed' }
 * - 只指定宽度：{ width: 500 } （高度自动按比例）
 * - 只指定高度：{ height: 1200 } （宽度自动按比例）
 * 
 * 注意：此配置与移动端（uniapp）的区别在于尺寸更大，以适应PC端大屏幕显示
 */
export const ThumbnailPresets = {
  /** 小缩略图：150x150，适用于头像、图标等（PC端） */
  small: { width: 150, height: 150, mode: "lfit" as const },
  /** 紧凑型缩略图：200x200，适用于Logo、品牌标识等 */
  compact: { width: 200, height: 200, mode: "lfit" as const },
  /** 中等缩略图：500x500，适用于列表页、卡片等（PC端） */
  medium: { width: 500, height: 500, mode: "lfit" as const },
  /** 大缩略图：1200x1200，适用于详情页、预览等（PC端） */
  large: { width: 1200, height: 1200, mode: "lfit" as const },
  /** 原图：不缩放，只优化质量和格式，适用于详情页大图 */
  original: { useOriginal: true as const },
};
