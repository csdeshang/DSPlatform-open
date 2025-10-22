<template>
  <div 
    v-if="themeConfigStore.isShowLogo" 
    class="logo-container"
    :class="{ 
      'is-collapsed': isCollapsed,
      'is-dark': isDarkMode,
      'is-horizontal': isHorizontalMode
    }"
  >
    <router-link to="/" class="logo-link">
      <!-- Logo 图片 -->
      <img 
        :src="formatFileUrl(logoSrc)" 
        class="logo-img"
        :alt="siteName"
      >
    </router-link>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import useThemeConfigStore from '@/stores/modules/themeConfig'
import { getSystemType,formatFileUrl } from '@/utils/util'
import { useConfigStore } from '@/stores/modules/config'

const themeConfigStore = useThemeConfigStore()
const configStore = useConfigStore()

// 侧边栏是否折叠
const isCollapsed = computed(() => themeConfigStore.isCollapsed)

// 是否暗黑模式
const isDarkMode = computed(() => themeConfigStore.isDarkMode)

// 布局模式
const layout = computed(() => themeConfigStore.layout || 'sidebar-only')

// 是否为水平模式
const isHorizontalMode = computed(() => 
  layout.value === 'horizontal' || 
  layout.value === 'horizontal-vertical'
)

// 获取系统类型
const systemType = getSystemType()

// 网站配置
const websiteConfig = ref<Record<string, string>>({})

// Logo 图片源
const logoSrc = computed(() => {
  // 根据系统类型选择对应的 logo
  return systemType === 'admin' 
    ? websiteConfig.value.admin_site_logo 
    : websiteConfig.value.pc_site_logo
})

// 站点名称
const siteName = computed(() => {
  // 根据系统类型选择对应的站点名称
  return systemType === 'admin' 
    ? websiteConfig.value.admin_site_name 
    : websiteConfig.value.pc_site_name
})

// 获取配置信息
async function fetchWebsiteConfig() {
  try {
    websiteConfig.value = await configStore.fetchConfigsByType('website')
  } catch (error) {
    console.error('获取网站配置失败:', error)
  }
}

// 组件挂载时获取配置
onMounted(() => {
  fetchWebsiteConfig()
})
</script>

<style scoped lang="scss">
.logo-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: var(--logo-height, 64px);
  padding: 0 16px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
  width: v-bind('isCollapsed ? "64px" : themeConfigStore.sideWidth + "px"');
  box-sizing: border-box;
  
  &.is-collapsed {
    width: 64px;
    padding: 0;
  }
  
  &.is-dark {
    border-color: var(--el-border-color, #434343);
  }
  
  &.is-horizontal {
    height: 100%;
    border-bottom: none;
    padding: 0 12px;
    width: auto;
  }
  
  .logo-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    text-decoration: none;
    overflow: hidden;
  }
  
  .logo-img {
    width: auto;
    height: auto;
    max-height: 40px;
    max-width: 100%;
    object-fit: contain;
    transition: all 0.3s;
    
    .is-collapsed & {
      max-width: 32px;
      max-height: 32px;
    }
    
    @media (max-width: 768px) {
      max-width: 32px;
      max-height: 32px;
    }
  }
}
</style>