<template>
  <el-container class="h-full">
    <el-header>
      <lay-header />
    </el-header>
    <el-container class="flex flex-row flex-1 overflow-hidden">
      <lay-sidebar />
      <lay-main class="flex-1" />
    </el-container>
  </el-container>
</template>

<script setup lang="ts">
import LaySidebar from './components/lay-sidebar/index.vue'
import LayHeader from './components/lay-header/index.vue'
import LayMain from './components/lay-main/index.vue'
import { useRoute } from 'vue-router'
import { onMounted, watch } from 'vue'
import useThemeConfigStore from '@/stores/modules/themeConfig'
import useUserInfoStore from '@/stores/modules/userInfo'

const route = useRoute()
const themeConfigStore = useThemeConfigStore()
const userInfoStore = useUserInfoStore()

// 根据当前路由查找对应的顶部菜单
const findTopMenuByPath = (path) => {
  const menuRoutes = userInfoStore.menuRoutes
  
  for (const topRoute of menuRoutes) {
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
  
  // 如果没有匹配的菜单，默认选择第一个
  return menuRoutes.length > 0 ? menuRoutes[0].name : ''
}

// 初始化当前顶部菜单
const initCurrentTopMenu = () => {
  if (themeConfigStore.layout === 'header-sidebar') {
    // 如果已经有currentTopMenu且有效，则不需要重新设置
    if (themeConfigStore.currentTopMenu) {
      const exists = userInfoStore.menuRoutes.some(
        route => route.name === themeConfigStore.currentTopMenu
      )
      if (exists) return
    }
    
    // 根据当前路由查找对应的顶部菜单
    const currentPath = route.path
    const topMenu = findTopMenuByPath(currentPath)
    
    if (topMenu) {
      themeConfigStore.setThemeConfig('currentTopMenu', topMenu)
    }
  }
}

// 组件挂载时，初始化当前顶部菜单
onMounted(() => {
  initCurrentTopMenu()
})

// 监听路由变化，更新当前顶部菜单
watch(() => route.path, () => {
  initCurrentTopMenu()
})

// 监听布局变化，更新当前顶部菜单
watch(() => themeConfigStore.layout, () => {
  initCurrentTopMenu()
})

// 监听菜单数据变化，更新当前顶部菜单
watch(() => userInfoStore.menuRoutes, () => {
  initCurrentTopMenu()
}, { deep: true })
</script>

<style scoped>
.el-header {
  height: var(--app-header-height);
  line-height: var(--app-header-height);
}

:deep(.el-container) {
  height: 100%;
}

:deep(.el-aside) {
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.el-main) {
  transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);

}
</style>