import { ref, computed, onMounted } from 'vue'
import useEnumStore from '@/stores/modules/enum'

/**
 * 枚举数据 Hook
 * @param type 枚举类型，使用 module.table.field 格式
 * @param immediate 是否立即加载
 * @returns 枚举相关方法和数据
 */
export function useEnum(type: string, immediate = true) {
  const enumStore = useEnumStore()
  const loading = ref(false)
  const options = ref<Array<{label: string, value: string | number}>>([])
  
  // 确保从本地存储加载
  if (!enumStore.isLoaded) {
    enumStore.loadFromStorage()
  }
  
  // 更新选项
  const updateOptions = (data: Record<string | number, string>) => {
    options.value = Object.entries(data).map(([value, label]) => ({
      label: label as string,
      value: /^\d+$/.test(value) ? parseInt(value, 10) : value
    }))
  }
  
  // 如果本地已有数据，直接使用
  if (enumStore.enumData[type]) {
    updateOptions(enumStore.enumData[type])
  }

  
  // 加载枚举数据
  const loadEnum = async (forceRefresh = false) => {
    if (loading.value) return enumStore.enumData[type] || {}
    
    // 如果已有数据且不强制刷新，直接返回
    if (!forceRefresh && enumStore.enumData[type]) {
      return enumStore.enumData[type]
    }
    
    loading.value = true
    try {
      const data = await enumStore.getEnum(type, forceRefresh)
      updateOptions(data)
      return data
    } catch (error) {
      console.error(`加载枚举[${type}]失败:`, error)
      return {}
    } finally {
      loading.value = false
    }
  }
  
  // 获取文本方法
  const getText = (value: string | number): string => {
    return enumStore.getText(type, value)
  }
  
  // 刷新枚举
  const refresh = async () => {
    return await loadEnum(true)
  }
  
  // 初始加载 - 只有在本地没有数据时才调用接口
  if (immediate && !enumStore.enumData[type]) {
    // 使用 onMounted 确保在组件挂载后加载
    onMounted(() => {
      loadEnum()
    })
  }
  
  return {
    loading,
    options,
    getText,
    loadEnum,
    refresh,
    rawData: computed(() => enumStore.enumData[type] || {})
  }
}
