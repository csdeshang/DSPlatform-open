import { defineStore } from 'pinia'
import type { RouteRecordRaw } from 'vue-router'
import { transformMenuToRoutes, removeDynamicRoutes, filterRoutesByPermissions } from '@/router/utils'

import { getToken, setToken, removeToken } from '@/utils/auth'
import { getCurrentUserMenus, getCurrentUserInfo } from '@/api/menuRoutes'
import { loginNormal, logout } from '@/api/login'

import storage from '@/utils/storage'
import router from '@/router'
import useMultiTagsStore from '@/stores/modules/multiTags'

import { getSystemType } from '@/utils/util'

// 定义简化的用户信息接口
interface UserState {
  access_token: string;
  refresh_token: string;
  userInfo: any;
  // 菜单数据
  userMenus: any;
  // 菜单数据转换为路由配置
  menuRoutes: RouteRecordRaw[];
}

const useUserInfoStore = defineStore('userInfo', {
  state: (): UserState => ({
    access_token: getToken('access_token') || '',
    refresh_token: getToken('refresh_token') || '',
    userInfo: {},
    userMenus: [],
    menuRoutes: []
  }),

  actions: {
    // 登录
    login(payload: { username: string; password: string }) {
      const { username, password } = payload
      return new Promise((resolve, reject) => {
        loginNormal({
          username: username.trim(),
          password
        })
          .then(res => {
            // 存储token和用户信息
            setToken(res.data.access_token, 'access_token')
            setToken(res.data.refresh_token, 'refresh_token')
            if (res.data.userinfo.manage_store_list && res.data.userinfo.manage_store_list.length > 0) {
              // 设置默认店铺id
              storage.set('manage_store_id', res.data.userinfo.manage_store_list[0].id, 7 * 24 * 60 * 60)
            }

            this.access_token = res.data.access_token
            this.refresh_token = res.data.refresh_token
            this.userInfo = res.data.userinfo

            resolve(res)
          })
          .catch(error => reject(error))
      })
    },

    // 登出
    logout() {
      return new Promise((resolve, reject) => {
        // 获取refresh_token用于后端Token失效处理
        const refreshTokenValue = getToken('refresh_token')
        const logoutParams = refreshTokenValue ? { refresh_token: refreshTokenValue } : undefined
        
        logout(logoutParams)
          .then(async res => {
            // 重置状态
            this.access_token = ''
            this.refresh_token = ''
            this.userInfo = {}
            this.userMenus = []
            this.menuRoutes = []

            // 清除存储
            removeToken('access_token')
            removeToken('refresh_token')
            // 清除枚举数据
            storage.remove('enumData')
            // 清除店铺ID
            storage.remove('manage_store_id')

            // 重置路由和标签
            removeDynamicRoutes(router, ['login', 'notFound', 'root'])
            useMultiTagsStore().resetState()





            // 使用 window.location.href 强制刷新页面，替代 router.push
            const systemType = getSystemType()
            window.location.href = `/${systemType}/login`

            // resolve(res)
          })
          .catch(error => reject(error))
      })
    },


    // 获取用户信息
    fetchUserInfo(isRefresh: boolean = false) {
      return new Promise<void>((resolve) => {
        if (this.userInfo && Object.keys(this.userInfo).length > 0 && !isRefresh) {
          resolve(this.userInfo)
          return
        }
        getCurrentUserInfo().then(res => {
          this.userInfo = res.data
          resolve(res.data)
        }).catch(error => {
          console.error('获取用户信息失败:', error)
        })
      })
    },

    // 获取用户菜单
    fetchUserMenus() {
      return new Promise<void>((resolve, reject) => {
        if (this.userMenus && this.userMenus.length > 0) {
          resolve(this.userMenus)
          return
        }
        getCurrentUserMenus().then(res => {

          if (res.code === 10000) {
            resolve(res.data.userMenus)
          } else {
            // 处理其他响应码
            console.error('获取用户菜单失败:', res.message || '未知错误');
            // reject(new Error(res.message || '获取用户菜单失败'));
          }


        }).catch(error => {
          console.error('获取用户信息失败:', error)
        })
      })
    },


    // 获取菜单路由
    async getMenuRoutes() {

      // 获取用户菜单 
      const userMenus = await this.fetchUserMenus()

      // 获取用户权限
      const userInfo = await this.fetchUserInfo()
      // const userPermissions = userInfo.permissions

      // 将菜单转换为路由配置
      const asyncRoutes = transformMenuToRoutes(userMenus)


      // 根据用户权限过滤路由[待完善]
      // this.menuRoutes = filterRoutesByPermissions(asyncRoutes, userPermissions)
      this.menuRoutes = asyncRoutes

      return this.menuRoutes




      // // 从后端获取菜单数据
      // async function fetchUserMenus(): Promise<MenuItem[]> {
      //   try {
      //     // 这里应该是实际的 API 调用
      //     // const response = await api.getCurrentUserMenus()
      //     // return response.data

      //     // 模拟数据
      //     return [
      //       {
      //         path: 'system',
      //         name: 'System',
      //         component: 'RouterView',
      //         title: '系统管理',
      //         icon: 'setting',
      //         type: 'directory',
      //         children: [
      //           {
      //             path: 'user',
      //             name: 'UserManagement',
      //             component: 'UserList',
      //             title: '用户管理',
      //             icon: 'user',
      //             permissions: ['system:user:list']
      //           },
      //           {
      //             path: 'role',
      //             name: 'RoleManagement',
      //             component: 'RoleList',
      //             title: '角色管理',
      //             icon: 'peoples',
      //             permissions: ['system:role:list']
      //           },
      //           {
      //             path: 'menu',
      //             name: 'MenuManagement',
      //             component: 'MenuList',
      //             title: '菜单管理',
      //             icon: 'tree-table',
      //             permissions: ['system:menu:list']
      //           }
      //         ]
      //       }
      //     ]
      //   } catch (error) {
      //     console.error('获取菜单数据失败', error)
      //     return []
      //   }
      // }

      // // 获取用户权限
      // async function fetchUserPermissions(): Promise<string[]> {
      //   try {
      //     // 这里应该是实际的 API 调用
      //     // const response = await api.getUserPermissions()
      //     // return response.data

      //     // 模拟数据
      //     return ['system:user:list', 'system:role:list', 'system:menu:list']
      //   } catch (error) {
      //     console.error('获取用户权限失败', error)
      //     return []
      //   }
      // }


    },



  }
})






export default useUserInfoStore
