import { defineStore } from 'pinia'
import type { RouteLocationNormalized, Router } from 'vue-router'
import useThemeConfigStore from '@/stores/modules/themeConfig'

// 定义标签项接口
export interface TabItem {
  name: string
  path: string
  fullPath: string
  title?: string
  icon?: string
  query?: Record<string, any>
  params?: Record<string, any>
  show?: boolean
}

// 定义多标签状态接口
export interface MultiTagsState {
  cacheTabList: Set<string>  // 缓存的组件名称列表
  tabList: TabItem[]         // 标签列表
  tabMap: Record<string, TabItem>  // 标签映射表，用于快速查找
  activeTab: string          // 当前激活的标签
}

// 不需要添加到标签的路径
const EXCLUDED_PATHS = ['/login', '/404','/admin/login','/store/login','/merchant/login']

/**
 * 获取路由参数
 * @param tabItem 标签项
 * @returns 路由参数对象
 */
export function getRouteParams(tabItem: TabItem) {
  const { params, path, query } = tabItem
  return {
    params: params || {},
    path,
    query: query || {}
  }
}

/**
 * 获取组件名称
 * @param route 路由对象
 * @returns 组件名称
 */
function getComponentName(route: RouteLocationNormalized): string | undefined {
  return route.name as string
}

/**
 * 判断路由是否可以添加为标签
 * @param route 路由对象
 * @returns 是否可以添加
 */
function canAddRoute(route: RouteLocationNormalized): boolean {
  const { path, meta } = route
  
  // 如果路由没有路径，不添加
  if (!path) return false
  
  // 如果路由被标记为不显示，不添加
  if (meta?.show === false) return false
  
  // 如果是排除的路径，不添加
  if (EXCLUDED_PATHS.includes(path)) return false
  
  return true
}

const useMultiTagsStore = defineStore('multiTags', {
  state: (): MultiTagsState => ({
    cacheTabList: new Set(),
    tabList: [],
    tabMap: {},
    activeTab: ''
  }),
  
  getters: {
    // 获取缓存的组件名称列表
    getCacheTabList(): string[] {
      return Array.from(this.cacheTabList)
    },
    
    // 获取当前激活的标签
    getActiveTab(): string {
      return this.activeTab
    }
  },
  
  actions: {
    /**
     * 设置当前激活的标签
     * @param fullPath 路由完整路径
     */
    setActiveTab(fullPath: string) {
      this.activeTab = fullPath
    },
    
    /**
     * 添加组件到缓存
     * @param componentName 组件名称
     */
    addCache(componentName?: string) {
      if (componentName) this.cacheTabList.add(componentName)
    },
    
    /**
     * 从缓存中移除组件
     * @param componentName 组件名称
     */
    removeCache(componentName?: string) {
      if (componentName && this.cacheTabList.has(componentName)) {
        this.cacheTabList.delete(componentName)
      }
    },
    
    /**
     * 清空缓存
     */
    clearCache() {
      this.cacheTabList.clear()
    },
    
    /**
     * 重置状态
     */
    resetState() {
      this.cacheTabList.clear()
      this.tabList = []
      this.tabMap = {}
      this.activeTab = ''
    },
    
    /**
     * 添加标签
     * @param route 路由对象
     */
    addTab(route: RouteLocationNormalized) {
      if (!canAddRoute(route)) return
      
      const { name, query, meta, params, fullPath, path } = route
      
      // 如果标签已存在，不重复添加
      if (this.tabMap[fullPath]) {
        this.setActiveTab(fullPath)
        return
      }
      
      // 创建标签项
      const tabItem: TabItem = {
        name: name as string,
        path,
        fullPath,
        title: meta?.title as string,
        icon: meta?.icon as string,
        query,
        params,
        show: meta?.show as boolean
      }
      
      // 添加到标签列表和映射表
      this.tabList.push(tabItem)
      this.tabMap[fullPath] = tabItem
      
      // 添加到缓存
      const componentName = getComponentName(route)
      this.addCache(componentName)
      
      // 设置当前激活的标签
      this.setActiveTab(fullPath)
    },
    
    /**
     * 移除标签
     * @param fullPath 路由完整路径
     * @param router 路由实例
     */
    removeTab(fullPath: string, router: Router) {
      const { currentRoute, push } = router
      const currentPath = currentRoute.value.fullPath
      
      // 找到标签索引
      const index = this.tabList.findIndex(item => item.fullPath === fullPath)
      if (index === -1) return
      
      // 从缓存中移除
      if (this.tabMap[fullPath]) {
        const componentName = this.tabMap[fullPath].name
        this.removeCache(componentName)
      }
      
      // 从标签列表和映射表中移除
      this.tabList.splice(index, 1)
      delete this.tabMap[fullPath]
      
      // 如果删除的不是当前标签，不需要跳转
      if (fullPath !== currentPath) return
      
      // 如果没有标签了，跳转到首页
      if (this.tabList.length === 0) {
        push('/')
        return
      }
      
      // 删除当前标签后，跳转到前一个标签
      let toTab: TabItem
      if (index === 0) {
        toTab = this.tabList[0]
      } else {
        toTab = this.tabList[index - 1]
      }
      
      this.setActiveTab(toTab.fullPath)
      push(getRouteParams(toTab))
    },
    
    /**
     * 移除其他标签
     * @param route 当前路由
     */
    removeOtherTabs(route: RouteLocationNormalized) {
      const fullPath = route.fullPath
      const currentTab = this.tabMap[fullPath]
      
      if (!currentTab) return
      
      // 清空缓存
      this.clearCache()
      
      // 只保留当前标签
      this.tabList = [currentTab]
      this.tabMap = { [fullPath]: currentTab }
      
      // 重新添加到缓存
      const componentName = getComponentName(route)
      this.addCache(componentName)
      
      // 设置当前激活的标签
      this.setActiveTab(fullPath)
    },
    
    /**
     * 移除所有标签
     * @param router 路由实例
     */
    removeAllTabs(router: Router) {
      this.tabList = []
      this.tabMap = {}
      this.clearCache()
      
      // 跳转到首页
      router.push('/')
    },
    
    /**
     * 刷新当前页面
     */
    refreshCurrentTab() {
      const themeConfigStore = useThemeConfigStore()
      themeConfigStore.refreshRouterView()
    }
  }
})

export default useMultiTagsStore
