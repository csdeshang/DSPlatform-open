<template>
  <div class="h-full">
    <el-scrollbar>
      <el-menu :router="true" :unique-opened="themeConfigStore.isUniqueOpened" :collapse="themeConfigStore.isCollapsed"
        :default-active="route.name" class="sidebar-menu">

        <!-- 在header-sidebar布局下，只显示当前选中一级菜单的子菜单 -->
        <template v-if="themeConfigStore.layout === 'header-sidebar'">
          <template
            v-if="currentTopLevelMenu && currentTopLevelMenu.children && currentTopLevelMenu.children.length > 0">
            <sidebar-menu-item v-for="childRoute in currentTopLevelMenu.children" :key="childRoute.path"
              :route="childRoute" />
          </template>
          <el-empty v-else description="当前菜单没有子项" :image-size="64" />
        </template>

        <!-- 在sidebar-only布局下，显示完整菜单 -->
        <template v-else>
          <sidebar-menu-item v-for="route in routes" :key="route.path" :route="route" />
        </template>

      </el-menu>
    </el-scrollbar>

  </div>
</template>

<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router';
const router = useRouter()
const route = useRoute()

import { computed } from 'vue';
import SidebarMenuItem from './menu-item.vue'

// 主题配置
import useThemeConfigStore from '@/stores/modules/themeConfig'
const themeConfigStore = useThemeConfigStore()


import useUserInfoStore from '@/stores/modules/userInfo'
const userInfoStore = useUserInfoStore()
const routes = computed(() => userInfoStore.menuRoutes)

// 当前选中的一级菜单
const currentTopLevelMenu = computed(() => {
  if (!themeConfigStore.currentTopMenu) return null

  return routes.value.find(route => route.name === themeConfigStore.currentTopMenu)
})

// console.log(router.getRoutes())

</script>

<style scoped>
/* 侧边栏菜单样式 */
.sidebar-menu {
  border-right: none;
}

/* 优化子菜单样式 */
:deep(.el-sub-menu .el-menu-item) {
  min-width: 180px;
}

/* 优化菜单项间距 */
:deep(.el-menu-item),
:deep(.el-sub-menu__title) {
  padding: 0 20px;
}
</style>