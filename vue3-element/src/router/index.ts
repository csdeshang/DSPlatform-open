import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css'

import useUserInfoStore from '@/stores/modules/userInfo'
import useMultiTagsStore from '@/stores/modules/multiTags'
import { findFirstAccessibleRoute, addRoutesRecursively } from './utils'

export const DEFAULT_LAYOUT = () => import('@/layout/index.vue')

// 导入消费者端路由
import { consumerRoutes } from './modules/consumer'

// 根据系统类型获取默认路由
import { getSystemType } from '@/utils/util'
const systemType = getSystemType()


// 根据系统类型获取默认路由
function getDefaultRoutes() {
  
  if (systemType == 'admin') {
    return [
      {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages-admin/main/views/not-found/index.vue'),
        meta: { title: '', show: false }
      },
      {
        path: '/admin/login',
        name: 'login',
        component: () => import('@/pages-admin/main/views/login/index.vue'),
        meta: { title: '登录', show: false }

      }
    ]
  } else if (systemType == 'store') {
    return [
      {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages-store/main/views/not-found/index.vue'),
        meta: { title: '', show: false }
      },
      {
        path: '/store/login',
        name: 'login',
        component: () => import('@/pages-store/main/views/login/index.vue'),
        meta: { title: '登录', show: false }

      }
    ]
  } else if (systemType == 'merchant') {
    return [
      {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages-merchant/main/views/not-found/index.vue'),
        meta: { title: '', show: false }
      },
      {
        path: '/merchant/login',
        name: 'login',
        component: () => import('@/pages-merchant/main/views/login/index.vue'),
        meta: { title: '登录', show: false }

      }
    ]
  }else if (systemType == 'consumer') {
    return consumerRoutes
  } else {
    console.log('systemType error')
    return []
  }
}

const router = createRouter({
  history: createWebHistory(),
  routes: getDefaultRoutes(),
  // 平滑滚动
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

/** 路由白名单 */
const whiteList = ['admin_login','store_login','merchant_login']
const loginPath = `/${systemType}/login`

// 注册一个全局前置守卫
router.beforeEach(async (to, from, next) => {
  // NProgress Configuration
  NProgress.configure({ showSpinner: false })
  // 开始进度条
  NProgress.start()
  
  // 设置页面标题
  const appName = import.meta.env.VITE_APP_TITLE || '管理系统'
  document.title = to.meta.title ? `${to.meta.title} - ${appName}` : appName
  
  const userInfoStore = useUserInfoStore()
  const multiTagsStore = useMultiTagsStore()

  if (whiteList.includes(to.path) || systemType == 'consumer') {
    // 白名单路由直接放行  或是消费者访问
    next()
  } else if (userInfoStore.refresh_token) {
    if (userInfoStore.menuRoutes.length) {
      if (to.path === loginPath) {
        next({ path: '/' })
      } else {
        // 如果路由已加载，添加到标签页
        if (to.name && to.meta.show !== false) {
          multiTagsStore.addTab(to)
        }
        next()
      }
    } else {
      try {
        await userInfoStore.getMenuRoutes()

        // 找到第一个可访问的路由作为默认路由
        const firstRouteName = findFirstAccessibleRoute(userInfoStore.menuRoutes)
        if (!firstRouteName) {
          console.log('firstRouteName error')
          return
        }
        
        // 动态添加index路由
        const INDEX_ROUTE: RouteRecordRaw = {
          path: '/',
          component: DEFAULT_LAYOUT,
          name: 'root',
          redirect: { name: firstRouteName }
        }
        router.addRoute(INDEX_ROUTE)

        // 动态添加其余路由
        addRoutesRecursively(router, userInfoStore.menuRoutes, 'root')

        // 如果当前路由需要添加到标签页
        if (to.name && to.meta.show !== false) {
          multiTagsStore.addTab(to)
        }

        next({ ...to, replace: true })
      } catch (error) {
        if (error.response && error.response.status === 401) {
          // window.location.reload(); // 刷新页面
        } else {
          // 如果获取授权信息失败，则跳转到登录页面
          next({ path: loginPath, query: { redirect: to.fullPath } })
        }
      }
    }
  } else {
    if (to.path === loginPath) {
      next()
    } else {
      next({ path: loginPath, query: { redirect: to.fullPath } })
    }
  }
})

router.afterEach(() => {
  NProgress.done()
})

export default router
