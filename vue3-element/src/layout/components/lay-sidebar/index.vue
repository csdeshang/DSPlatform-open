<template>
    <el-aside class="flex flex-col" :width="asideWidth" v-if="showSidebar">
        <logo v-if="themeConfigStore.isShowLogo && themeConfigStore.layout === 'sidebar-only'"></logo>
        <vertical-menu></vertical-menu>
    </el-aside>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import Logo from '../logo.vue'
import VerticalMenu from '../lay-menu/vertical-menu.vue'
import useThemeConfigStore from '@/stores/modules/themeConfig'
import useUserInfoStore from '@/stores/modules/userInfo'

const themeConfigStore = useThemeConfigStore()
const userInfoStore = useUserInfoStore()

// 计算侧边栏宽度
const asideWidth = computed(() => {
    if (themeConfigStore.isMobile) {
        return 'auto'
    } else if(themeConfigStore.isCollapsed){
        return 'auto'
    }else{
        return themeConfigStore.sideWidth + 'px'
    }
})

// 当前选中的一级菜单
const currentTopLevelMenu = computed(() => {
    if (!themeConfigStore.currentTopMenu) return null
    
    return userInfoStore.menuRoutes.find(route => route.name === themeConfigStore.currentTopMenu)
})

// 是否显示侧边栏
const showSidebar = computed(() => {
    // header-only布局不显示侧边栏
    if (themeConfigStore.layout === 'header-only') {
        return false
    }
    
    // 在header-sidebar布局下，只有当前选中的一级菜单有子菜单时才显示侧边栏
    if (themeConfigStore.layout === 'header-sidebar') {
        const menu = currentTopLevelMenu.value
        return menu && menu.children && menu.children.length > 0
    }
    
    // 在sidebar-only布局下始终显示侧边栏
    return true
})

</script>

<style scoped>
.el-aside {
  background-color: var(--app-sidebar-bg);
  color: var(--app-sidebar-text);
  transition: width 0.3s;
  overflow: hidden;
}
</style>
