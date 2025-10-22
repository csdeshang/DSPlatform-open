import { RouterView, type RouteRecordRaw, type Router } from 'vue-router'


// 定义菜单项接口
export interface MenuItem {
  path: string
  name?: string
  component?: string
  title: string
  icon?: string
  permissions?: string[]
  is_show?: boolean  // 使用 is_show 替代 hidden
  keepAlive?: boolean
  sort?: number
  children?: MenuItem[]
  type?: 'menu' | 'directory' | 'button'
}

// 根据系统类型获取模块
import { getSystemType } from '@/utils/util'
const systemType = getSystemType()
let allModules = {}
if (systemType == 'admin') {
  // 匹配views里面所有的.vue文件，动态引入
  const sysyemModules = import.meta.glob('/src/pages-admin/main/views/**/*.vue')
  const mallModules = import.meta.glob('/src/pages-admin/platform/mall/views/**/*.vue')
  const foodModules = import.meta.glob('/src/pages-admin/platform/food/views/**/*.vue')
  const houseModules = import.meta.glob('/src/pages-admin/platform/house/views/**/*.vue')
  const kmsModules = import.meta.glob('/src/pages-admin/platform/kms/views/**/*.vue')

  allModules = { ...sysyemModules, ...mallModules,...foodModules, ...houseModules, ...kmsModules };
} else if (systemType == 'store') {
  // 匹配views里面所有的.vue文件，动态引入
  const sysyemModules = import.meta.glob('/src/pages-store/main/views/**/*.vue')
  const mallModules = import.meta.glob('/src/pages-store/platform/mall/views/**/*.vue')
  const foodModules = import.meta.glob('/src/pages-store/platform/food/views/**/*.vue')
  const houseModules = import.meta.glob('/src/pages-store/platform/house/views/**/*.vue')
  const kmsModules = import.meta.glob('/src/pages-store/platform/kms/views/**/*.vue')
  allModules = { ...sysyemModules, ...mallModules,...foodModules, ...houseModules, ...kmsModules };
}else if (systemType == 'merchant') {
  // 匹配views里面所有的.vue文件，动态引入
  const sysyemModules = import.meta.glob('/src/pages-merchant/main/views/**/*.vue')
  allModules = { ...sysyemModules };
}else if (systemType == 'consumer') {
  // 匹配views里面所有的.vue文件，动态引入
  const sysyemModules = import.meta.glob('/src/pages-consumer/main/views/**/*.vue')
  allModules = { ...sysyemModules };
}


// console.log('allModules', allModules)

/**
 * 将后端返回的菜单数据转换为路由配置
 * @param menus 后端返回的菜单数据
 * @returns 路由配置数组
 */
export function transformMenuToRoutes(menus: MenuItem[], firstRoute = true): RouteRecordRaw[] {
  return menus.map((menu) => {
    const routeRecord: RouteRecordRaw = {
      path: menu.path.startsWith('/') ? menu.path : `/${menu.path}`,
      name: menu.name || menu.path,
      meta: {
        title: menu.title,
        icon: menu.icon,
        permissions: menu.permissions,
        show: menu.is_show !== undefined ? menu.is_show : true,
        keepAlive: menu.keepAlive || false,
        type: menu.type
      }
    }


    switch (menu.type) {
      case 'directory':
        routeRecord.component = () => import('vue-router').then(({ RouterView }) => RouterView)
        break
      case 'menu':
        routeRecord.component = loadComponent(menu.component)
        break
    }

    // 处理子路由
    if (menu.children && menu.children.length > 0) {
      routeRecord.children = transformMenuToRoutes(menu.children, false)
    }
    return routeRecord
  })
}





// 动态加载组件
export function loadComponent(componentName: string) {
  if (!componentName) {
    return () => import('vue-router').then(({ RouterView }) => RouterView)
  }
  try {
    const key = Object.keys(allModules).find(key => key.includes(`/${componentName}.vue`))
    if (key) {
      return allModules[key]
    }
    console.warn(`组件 ${componentName} 未找到，将使用空白组件代替`)
    return () => import('vue-router').then(({ RouterView }) => RouterView)
  } catch (error) {
    console.error('加载组件出错:', error)
    return () => import('vue-router').then(({ RouterView }) => RouterView)
  }
}

/**
 * 动态添加路由
 * @param router 路由实例
 * @param routes 路由配置
 * @param parentName 父路由名称（可选）
 */
export function addRoutesRecursively(router: Router,routes: RouteRecordRaw[], parentName?: string) {
  try {
    routes.forEach((route: any) => {

      // 添加路由
      if (!route.children) {
        router.addRoute(parentName, route)
      } else {
        router.addRoute(route)
      }

      // 递归处理子路由
      if (route.children && route.children.length > 0) {
        addRoutesRecursively(router,route.children, parentName)
      }
    })
  } catch (error) {
    console.error('添加路由失败:', error)
  }
}





/**
 * 获取第一个可访问的路由
 * @param routes 路由配置
 * @returns 第一个可访问的路由名称
 */
export function findFirstAccessibleRoute(routes: RouteRecordRaw[]): string | undefined {
  for (const route of routes) {
    // 如果路由是菜单类型，并且显示，则返回路由名称
    if (route.meta?.type == 'menu' && route.meta?.show) {
      return route.name as string
    }
    if (route.children) {
      const childName = findFirstAccessibleRoute(route.children)
      if (childName) {
        return childName
      }
    }
  }
  return undefined
}


/**
 * 移除动态路由
 * @param router 路由实例
 * @param excludeNames 不需要移除的路由名称数组
 */
export function removeDynamicRoutes(router: Router, excludeNames: string[] = ['login', 'notFound', 'root']) {
  router.getRoutes().forEach(route => {
    if (route.name && !excludeNames.includes(route.name.toString())) {
      try {
        router.removeRoute(route.name)
        console.log(`成功移除路由: ${route.name}`)
      } catch (error) {
        console.error(`移除路由 ${route.name} 失败:`, error)
      }
    }
  })
}



/**
 * 根据权限过滤路由
 * @param routes 路由配置
 * @param userPermissions 用户权限列表
 * @returns 过滤后的路由配置
 */
export function filterRoutesByPermissions(
  routes: RouteRecordRaw[],
  userPermissions: string[]
): RouteRecordRaw[] {
  return routes.filter(route => {
    // 检查路由是否需要权限
    const routePermissions = route.meta?.permissions as string[] | undefined
    
    // 如果路由不需要权限，或者用户拥有所需权限，则保留该路由
    const hasPermission = !routePermissions || 
      routePermissions.length === 0 || 
      routePermissions.some(permission => userPermissions.includes(permission))
    
    // 如果有子路由，递归过滤
    if (route.children && route.children.length > 0) {
      route.children = filterRoutesByPermissions(route.children, userPermissions)
      
      // 如果过滤后没有子路由，且当前路由是目录类型，则不显示该路由
      if (route.children.length === 0 && route.meta?.type === 'directory') {
        return false
      }
    }
    
    return hasPermission
  })
}



/**
 * 获取路由的面包屑
 * @param route 当前路由
 * @param routes 所有路由
 * @returns 面包屑数组
 */
export function getBreadcrumb(route: RouteRecordRaw, routes: RouteRecordRaw[]): RouteRecordRaw[] {
  const breadcrumb: RouteRecordRaw[] = []
  const findPath = (path: string, routeList: RouteRecordRaw[], parent?: RouteRecordRaw) => {
    for (const item of routeList) {
      // 如果找到匹配的路由
      if (item.path === path) {
        if (parent) {
          breadcrumb.push(parent)
        }
        breadcrumb.push(item)
        return true
      }
      
      // 递归查找子路由
      if (item.children && item.children.length > 0) {
        if (findPath(path, item.children, item)) {
          return true
        }
      }
    }
    return false
  }
  
  findPath(route.path, routes)
  return breadcrumb
} 