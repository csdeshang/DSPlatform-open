// stores/modules/enum.ts
import { defineStore } from 'pinia'
import storage from '@/utils/storage'
import { getEnumData } from '@/api/menuRoutes'

/**
 * 枚举数据接口
 */
interface EnumData {
  [key: string]: Record<string | number, string>
}

// 固定的存储键名
const STORAGE_KEY = 'enumData'

/**
 * 枚举数据存储
 */
const useEnumStore = defineStore('enum', {
  state: () => ({
    // 枚举数据缓存
    enumData: {} as EnumData,
    // 加载状态记录
    loadingStates: {} as Record<string, boolean>,
    // 是否已从本地存储加载
    isLoaded: false
  }),
  
  actions: {
    /**
     * 从本地存储加载枚举数据
     */
    loadFromStorage(): void {
      if (this.isLoaded) return
      
      // 从本地存储加载所有枚举数据
      const storedData = storage.get(STORAGE_KEY)
      if (storedData && typeof storedData === 'object') {
        this.enumData = storedData
      }
      
      this.isLoaded = true
    },
    
    /**
     * 保存所有枚举数据到本地存储
     */
    saveEnumData(): void {
      storage.set(STORAGE_KEY, this.enumData)
    },
    
    /**
     * 获取枚举数据
     * @param type 枚举类型，使用 module.table.field 格式
     * @param forceRefresh 是否强制刷新
     * @returns 枚举数据对象
     */
    async getEnum(type: string, forceRefresh = false): Promise<Record<string | number, string>> {
      // 确保从本地存储加载
      if (!this.isLoaded) {
        this.loadFromStorage()
      }
      
      // 如果已加载且不强制刷新，直接返回
      if (!forceRefresh && this.enumData[type]) {
        return this.enumData[type]
      }
      
      // 如果正在加载中，等待加载完成
      if (this.loadingStates[type]) {
        return new Promise((resolve) => {
          const checkInterval = setInterval(() => {
            if (!this.loadingStates[type]) {
              clearInterval(checkInterval)
              resolve(this.enumData[type] || {})
            }
          }, 50)
        })
      }
      
      // 标记为加载中
      this.loadingStates[type] = true
      
      try {
        // 请求最新数据
        const response = await getEnumData({ type: type })
        
        if (response.code === 10000) {
          const { data } = response
          
          // 更新数据
          if (data && data[type]) {
            // 处理数组格式的枚举
            if (Array.isArray(data[type])) {
              const enumObj: Record<number, string> = {}
              data[type].forEach((value: string, index: number) => {
                enumObj[index] = value
              })
              this.enumData[type] = enumObj
            } else {
              this.enumData[type] = data[type] as Record<string | number, string>
            }
            
            // 更新本地存储
            this.saveEnumData()
          }
        }
        
        return this.enumData[type] || {}
      } catch (error) {
        console.error(`加载枚举数据[${type}]失败:`, error)
        return this.enumData[type] || {}
      } finally {
        // 无论成功失败，都标记为加载完成
        this.loadingStates[type] = false
      }
    },
    
    /**
     * 获取枚举文本
     * @param type 枚举类型
     * @param value 枚举值
     * @returns 枚举文本
     */
    getText(type: string, value: string | number): string {
      // 确保从本地存储加载
      if (!this.isLoaded) {
        this.loadFromStorage()
      }
      
      return this.enumData[type]?.[value] || ''
    },
    
    /**
     * 清除缓存
     * @param type 指定清除的类型，不传则清除所有
     */
    clearCache(type?: string): void {
      if (type) {
        delete this.enumData[type]
      } else {
        this.enumData = {}
      }
      
      // 更新本地存储
      this.saveEnumData()
    },
    
    /**
     * 强制刷新枚举数据
     * @param type 枚举类型
     * @returns 最新的枚举数据
     */
    async refreshEnum(type: string): Promise<Record<string | number, string>> {
      return await this.getEnum(type, true)
    }
  },
  
  getters: {
    /**
     * 获取已加载的所有枚举数据
     */
    getAllLoadedEnums(): EnumData {
      return this.enumData
    },
    
    /**
     * 检查指定枚举是否已加载
     */
    isEnumLoaded(): (type: string) => boolean {
      return (type: string) => !!this.enumData[type]
    }
  }
})

export default useEnumStore