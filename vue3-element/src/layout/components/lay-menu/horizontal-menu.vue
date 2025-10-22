<template>
  <div class="horizontal-menu-container">
    <el-menu 
      :router="themeConfigStore.layout !== 'header-sidebar'" 
      mode="horizontal" 
      :ellipsis="false"
      :default-active="activeMenu"
      class="horizontal-menu"
      @select="handleMenuSelect">
      
      <!-- 在header-sidebar布局下，只显示一级菜单 -->
      <template v-if="themeConfigStore.layout === 'header-sidebar'">
        <el-menu-item v-for="route in topLevelRoutes" :key="route.name" :index="route.name">
          <Icon v-if="route.meta && route.meta.icon" :icon="route.meta.icon" :size="20"/>
          <span>{{ route.meta ? route.meta.title : route.name }}</span>
        </el-menu-item>
      </template>
      
      <!-- 在header-only布局下，显示完整菜单 -->
      <template v-else>
        <sidebar-menu-item 
          v-for="route in routes" 
          :key="route.path" 
          :route="route" 
        />
      </template>
    </el-menu>
  </div>
</template>

<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router';
import { computed, watch } from 'vue';
import Icon from '@/components/icon/index.vue'
import SidebarMenuItem from './menu-item.vue'
import useUserInfoStore from '@/stores/modules/userInfo'
import useThemeConfigStore from '@/stores/modules/themeConfig'

const router = useRouter()
const route = useRoute()
const userInfoStore = useUserInfoStore()
const themeConfigStore = useThemeConfigStore()

// 所有菜单路由
const routes = computed(() => userInfoStore.menuRoutes)

// 只获取一级菜单
const topLevelRoutes = computed(() => {
  return routes.value.filter(route => route.meta && route.meta.title)
})

// 当前激活的菜单
const activeMenu = computed(() => {
  if (themeConfigStore.layout === 'header-sidebar') {
    if (themeConfigStore.currentTopMenu) {
      return themeConfigStore.currentTopMenu
    }
    
    // 如果没有设置当前顶部菜单，则根据当前路由查找对应的顶部菜单
    const currentPath = route.path
    const currentTopMenu = findTopMenuByPath(currentPath)
    return currentTopMenu || (topLevelRoutes.value.length > 0 ? topLevelRoutes.value[0].name : '')
  } else {
    return route.name
  }
})

// 根据路径查找对应的顶部菜单
const findTopMenuByPath = (path) => {
  for (const topRoute of topLevelRoutes.value) {
    if (path === topRoute.path) {
      return topRoute.name
    }
    
    if (topRoute.children) {
      const hasMatchChild = topRoute.children.some(child => {
        return path.startsWith(child.path) || path === child.path
      })
      
      if (hasMatchChild) {
        return topRoute.name
      }
    }
  }
  return null
}

// 处理菜单选择
const handleMenuSelect = (index) => {
  if (themeConfigStore.layout === 'header-sidebar') {
    // 在header-sidebar布局下，更新当前顶部菜单
    themeConfigStore.setThemeConfig('currentTopMenu', index)
    
    // 查找对应的路由
    const selectedRoute = topLevelRoutes.value.find(route => route.name === index)
    
    if (selectedRoute) {
      // 如果有子路由，找到第一个类型为MENU的子路由
      if (selectedRoute.children && selectedRoute.children.length > 0) {
        // 查找第一个类型为MENU的子路由
        const firstMenuChild = findFirstMenuChild(selectedRoute.children)
        if (firstMenuChild) {
          router.push({ name: firstMenuChild.name })
          return
        }
      }
      
      // 如果当前选中的路由自身是MENU类型，则导航到它
      if (selectedRoute.meta && selectedRoute.meta.type === 'menu') {
        router.push({ name: selectedRoute.name })
      }
    }
  }
  // 在其他布局下，el-menu的router属性会自动处理导航
}

// 递归查找第一个类型为MENU的子路由
const findFirstMenuChild = (routes) => {
  for (const route of routes) {
    if (route.meta && route.meta.type === 'menu') {
      return route
    }
    
    if (route.children && route.children.length > 0) {
      const childMenu = findFirstMenuChild(route.children)
      if (childMenu) {
        return childMenu
      }
    }
  }
  return null
}

// 监听路由变化，更新当前顶部菜单
watch(() => route.path, (newPath) => {
  if (themeConfigStore.layout === 'header-sidebar') {
    const topMenu = findTopMenuByPath(newPath)
    if (topMenu && topMenu !== themeConfigStore.currentTopMenu) {
      themeConfigStore.setThemeConfig('currentTopMenu', topMenu)
    }
  }
}, { immediate: true })
</script>

<style scoped>
/* 水平菜单容器 */
.horizontal-menu-container {
  height: 100%;
  display: flex;
  align-items: center;
}

/* 水平菜单样式 */
.horizontal-menu {
  border-bottom: none;
  background-color: transparent;
}

/* 水平菜单项样式 */
:deep(.el-menu--horizontal > .el-menu-item),
:deep(.el-menu--horizontal > .el-sub-menu .el-sub-menu__title) {
  height: var(--app-header-height);
  line-height: var(--app-header-height);
  color: var(--app-header-text);
  border-bottom: none;
}

/* 水平菜单悬停样式 */
:deep(.el-menu--horizontal > .el-menu-item:hover),
:deep(.el-menu--horizontal > .el-sub-menu:hover .el-sub-menu__title) {
  background-color: var(--app-header-hover-bg);
}

/* 水平菜单激活样式 */
:deep(.el-menu--horizontal > .el-menu-item.is-active),
:deep(.el-menu--horizontal > .el-sub-menu.is-active .el-sub-menu__title) {
  color: var(--app-sidebar-active-text);
  background-color: var(--app-sidebar-active-bg);
}

/* 水平菜单下拉样式 */
:deep(.el-menu--horizontal .el-sub-menu .el-menu) {
  background-color: var(--app-sidebar-bg);
  border: none;
  border-radius: 4px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}

/* 水平菜单下拉项样式 */
:deep(.el-menu--horizontal .el-sub-menu .el-menu-item) {
  color: var(--app-sidebar-text);
  background-color: var(--app-sidebar-bg);
}

:deep(.el-menu--horizontal .el-sub-menu .el-menu-item:hover) {
  background-color: var(--app-sidebar-hover-bg);
}

:deep(.el-menu--horizontal .el-sub-menu .el-menu-item.is-active) {
  color: var(--app-sidebar-active-text);
  background-color: var(--app-sidebar-active-bg);
}
</style>