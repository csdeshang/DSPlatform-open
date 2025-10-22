import type { RouteRecordRaw } from 'vue-router'

// 消费者端路由配置
export const consumerRoutes: RouteRecordRaw[] = [
  // 404页面
  {
    path: '/:pathMatch(.*)*',
    component: () => import('@/pages-consumer/main/views/not-found/index.vue'),
    meta: { title: '404', show: false }
  },
  // 使用布局组件的路由
  {
    path: '/',
    component: () => import('@/pages-consumer/main/layout/BaseLayout.vue'),
    children: [
      // 首页
      {
        path: 'home',
        name: 'ConsumerHome',
        component: () => import('@/pages-consumer/main/views/index.vue'),
        meta: { title: '首页', show: true }
      },
      // 用户中心
      {
        path: 'user',
        name: 'UserCenter',
        component: () => import('@/pages-consumer/main/views/user/index/index.vue'),
        meta: { title: '用户中心', show: true },
        children: [
          {
            path: 'profile',
            name: 'UserProfile',
            component: () => import('@/pages-consumer/main/views/user/setting/profile.vue'),
            meta: { title: '个人资料', show: true }
          },
          {
            path: 'orders',
            name: 'UserOrders',
            component: () => import('@/pages-consumer/main/views/user/order/index.vue'),
            meta: { title: '我的订单', show: true }
          }
        ]
      },
      // 可以添加更多需要布局的页面...
    ]
  },
  // 根路径重定向到首页
  {
    path: '/',
    redirect: '/home'
  },
  // 登录页（无需使用通用布局）
  {
    path: '/login',
    name: 'ConsumerLogin',
    component: () => import('@/pages-consumer/main/views/home/login/index.vue'),
    meta: { title: '登录', show: false }
  }
]