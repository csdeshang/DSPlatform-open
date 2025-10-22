<template>

  <HeaderSidebarLayout v-if="containerStyle === 'header-sidebar'" />
  <SidebarOnlyLayout v-if="containerStyle === 'sidebar-only'" />
  <HeaderOnlyLayout v-if="containerStyle === 'header-only'" />


</template>

<script setup lang="ts">
import { computed } from 'vue'
import HeaderSidebarLayout from './header-sidebar-layout.vue'
import SidebarOnlyLayout from './sidebar-only-layout.vue'
import HeaderOnlyLayout from './header-only-layout.vue'
import useThemeConfigStore from '@/stores/modules/themeConfig'

const themeConfigStore = useThemeConfigStore()

// 从主题配置中获取布局样式
const containerStyle = computed(() => {
  return themeConfigStore.layout || 'sidebar-only'
})



</script>
