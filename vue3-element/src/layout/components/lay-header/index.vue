<template>
  <el-header>
    <div class="flex items-center justify-between h-full">
      <!-- 左侧区域 -->
      <div class="flex items-center h-full">
        <logo v-if="themeConfigStore.layout === 'header-only' || themeConfigStore.layout === 'header-sidebar'"></logo>
        <div class="header-item">
          <!--折叠-->
          <collapse v-if="themeConfigStore.layout !== 'header-only'"></collapse>
        </div>

        <!-- 水平菜单 - 在header-only显示所有菜单，在header-sidebar只显示一级菜单 -->
        <horizontal-menu v-if="themeConfigStore.layout === 'header-only' || themeConfigStore.layout === 'header-sidebar'"></horizontal-menu>
        
        <!-- 面包屑导航 -->
        <breadcrumb v-if="themeConfigStore.layout === 'sidebar-only'" />
      </div>

      <!-- 右侧区域 -->
      <div class="flex items-center h-full">
        <div class="header-item">
          <!--刷新-->
          <refresh></refresh>
        </div>
        <div class="header-item">
          <!--全屏-->
          <fullscreen></fullscreen>
        </div>
        <div class="header-item">
          <!--用户中心-->
          <userinfo></userinfo>
        </div>
        <div class="header-item" v-if="userInfo && userInfo.manage_store_list && userInfo.manage_store_list.length > 0">
          <!--商户  进行切换管理的店铺 -->
          <merchant-store-switch></merchant-store-switch>
        </div>

        <div class="header-item" v-if="userInfo && userInfo.merchant_id && userInfo.merchant.merchant_id > 0">
          
            <el-button type="primary" @click="handleStoreSwitch(currentStore)">商户管理</el-button>
        </div>
        
        <div class="header-item">
          <!--系统设置-->
          <system-setting></system-setting>
        </div>
      </div>
    </div>
    {{ userInfo }}
  </el-header>
</template>

<script setup lang="ts">
import { computed, onMounted } from 'vue'
import Logo from '../logo.vue'
import collapse from './collapse.vue'
import refresh from './refresh.vue'
import fullscreen from './fullscreen.vue'
import userinfo from './userinfo.vue'
import systemSetting from './system-setting.vue'
import Breadcrumb from './breadcrumb.vue'
import MerchantStoreSwitch from './merchant-store-switch.vue'

import HorizontalMenu from '../lay-menu/horizontal-menu.vue'

import useThemeConfigStore from '@/stores/modules/themeConfig'

import useUserInfoStore from '@/stores/modules/userInfo'

const userInfoStore = useUserInfoStore()

const themeConfigStore = useThemeConfigStore()

// 使用计算属性获取并追踪用户信息变化
const userInfo = computed(() => userInfoStore.userInfo)

onMounted(async () => {
    await userInfoStore.fetchUserInfo()
})
</script>

<style scoped>
/* 头部样式 */
.el-header {
  background-color: var(--app-header-bg);
  color: var(--app-header-text);
  height: var(--app-header-height);
  line-height: var(--app-header-height);
}

/* 头部项目样式 */
.header-item {
  display: flex;
  align-items: center;
  height: 100%;
  padding: 0 10px;
}

/* 确保图标垂直居中 */
:deep(.el-icon) {
  display: flex;
  align-items: center;
  justify-content: center;
}

/* 修复el-scrollbar样式 */
:deep(.el-scrollbar) {
  height: 100%;
  width: 100%;
}

:deep(.el-scrollbar__wrap) {
  overflow-x: hidden;
}

:deep(.el-scrollbar__view) {
  height: 100%;
}
</style>