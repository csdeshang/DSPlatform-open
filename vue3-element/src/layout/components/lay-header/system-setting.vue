<template>
    <div>
        <el-tooltip class="box-item" effect="dark" content="主题设置" placement="bottom">
            <div @click="isDrawer = true">
                <Icon icon="element Tools" :size="20" />
            </div>

        </el-tooltip>
        <el-drawer v-model="isDrawer" title="系统设置" size="350px" style="color:var(--el-color-primary)">
            <div class="flex flex-col h-full">
                <el-scrollbar class="pr-10 flex-1">
                    <el-divider content-position="left">系统设置</el-divider>
                    <div class="flex justify-between items-center">
                        <span class="text-tx-secondary">显示LOGO</span>
                        <div>
                            <el-switch v-model="isShowLogo" :active-value="true" :inactive-value="false" />
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-tx-secondary">暗黑模式</span>
                        <div>
                            <el-switch v-model="isDarkMode" :active-value="true" :inactive-value="false" />
                        </div>
                    </div>

                    <el-divider content-position="left" class="mt-10">侧边栏设置</el-divider>
                    <div class="flex justify-between items-center">
                        <span class="text-tx-secondary">唯一打开</span>
                        <div>
                            <el-switch v-model="isUniqueOpened" :active-value="true" :inactive-value="false" />
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-tx-secondary">侧边栏宽度</span>
                        <div>
                            <el-input-number v-model="sideWidth" :min="100" :max="1000" />
                        </div>
                    </div>

                    <!-- 布局设置 -->
                    <el-divider content-position="left" class="mt-10">布局方式</el-divider>
                    <div class="mt-10">
                        <div class="layout-selector">
                            <div 
                                v-for="layout in layouts" 
                                :key="layout.value" 
                                class="layout-item" 
                                :class="{ active: currentLayout === layout.value }"
                                @click="changeLayout(layout.value)"
                            >
                                <div class="layout-preview" :style="getLayoutPreviewStyle(layout.value)">
                                    <div v-if="layout.value !== 'sidebar-only'" class="preview-header"></div>
                                    <div class="preview-body">
                                        <div v-if="layout.value !== 'header-only'" class="preview-sidebar"></div>
                                        <div class="preview-content"></div>
                                    </div>
                                </div>
                                <div class="layout-name">{{ layout.label }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 主题风格选择 -->
                    <el-divider content-position="left" class="mt-10">主题风格</el-divider>
                    <div class="mt-10">
                        <div class="theme-selector">
                            <div 
                                v-for="theme in themes" 
                                :key="theme.value" 
                                class="theme-item" 
                                :class="{ active: currentTheme === theme.value }"
                                @click="changeTheme(theme.value)"
                            >
                                <div class="theme-preview" :style="getThemePreviewStyle(theme.value)">
                                    <div class="preview-header"></div>
                                    <div class="preview-body">
                                        <div class="preview-sidebar"></div>
                                        <div class="preview-content"></div>
                                    </div>
                                </div>
                                <div class="theme-name">{{ theme.label }}</div>
                            </div>
                        </div>
                    </div>
                </el-scrollbar>
                
                <!-- 固定在底部的重置按钮 -->
                <div class="sticky bottom-0 left-0 right-0 p-4 border-t" :style="{ backgroundColor: 'var(--el-bg-color)', borderColor: 'var(--el-border-color)' }">
                    <el-button @click="resetThemeConfig" class="w-full" type="primary">重置主题</el-button>
                </div>
            </div>
        </el-drawer>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue'
import Icon from '@/components/icon/index.vue'
import useThemeConfigStore from '@/stores/modules/themeConfig'
import { useDark, useToggle } from '@vueuse/core'

const themeConfigStore = useThemeConfigStore()
const isDrawer = ref(false)

// 布局选项
const layouts = [
    { label: '侧边栏模式', value: 'sidebar-only' },
    { label: '顶栏侧边栏', value: 'header-sidebar' },
    { label: '顶栏模式', value: 'header-only' }
]

// 当前布局
const currentLayout = computed({
    get() {
        return themeConfigStore.layout || 'sidebar-only'
    },
    set(value) {
        themeConfigStore.setThemeConfig('layout', value)
    }
})

// 主题选项
const themes = [
    { label: '默认主题', value: 'light' },
    { label: '暗黑主题', value: 'dark' },
    { label: '黑色主题', value: 'black' },
    { label: '蓝色主题', value: 'blue' },
    { label: '绿色主题', value: 'green' },
    { label: '橙色主题', value: 'orange' },
    { label: '紫色主题', value: 'purple' }
]

// 当前主题
const currentTheme = computed({
    get() {
        return themeConfigStore.theme || 'light'
    },
    set(value) {
        themeConfigStore.setThemeConfig('theme', value)
    }
})

// 显示LOGO
const isShowLogo = computed({
    get() {
        return themeConfigStore.isShowLogo
    },
    set(value) {
        themeConfigStore.setThemeConfig('isShowLogo', value)
    }
})
// 唯一打开
const isUniqueOpened = computed({
    get() {
        return themeConfigStore.isUniqueOpened
    },
    set(value) {
        themeConfigStore.setThemeConfig('isUniqueOpened', value)
    }
})
// 暗黑模式
const isDark = useDark()
const toggleDark = useToggle(isDark)
const isDarkMode = computed({
    get() {
        return themeConfigStore.isDarkMode
    },
    set(value) {
        toggleDark(value)
        themeConfigStore.setThemeConfig('isDarkMode', value)
    }
})

// 侧边栏宽度
const sideWidth = computed({
    get() {
        return themeConfigStore.sideWidth
    },
    set(value) {
        themeConfigStore.setThemeConfig('sideWidth', value)
    }
})

// 切换布局
const changeLayout = (layout) => {
    currentLayout.value = layout
}

// 切换主题
const changeTheme = (theme) => {
    // 移除所有主题类
    document.documentElement.classList.remove('dark', 'theme-blue', 'theme-green', 'theme-orange', 'theme-purple', 'theme-black')
    
    // 添加当前主题类
    if (theme !== 'light') {
        document.documentElement.classList.add(theme === 'dark' ? 'dark' : `theme-${theme}`)
    }
    
    // 更新主题配置
    currentTheme.value = theme
    
    // 如果切换到dark主题，同时更新暗黑模式状态
    if (theme === 'dark') {
        isDarkMode.value = true
    } else if (isDarkMode.value && theme !== 'dark') {
        isDarkMode.value = false
    }
}

// 获取布局预览样式
const getLayoutPreviewStyle = (layout) => {
    // 使用当前主题的颜色
    const theme = currentTheme.value
    const styles = getThemeColors(theme)
    
    return {
        '--preview-header-bg': styles.header,
        '--preview-sidebar-bg': styles.sidebar,
        '--preview-content-bg': styles.content
    }
}

// 获取主题预览样式
const getThemePreviewStyle = (theme) => {
    const styles = getThemeColors(theme)
    
    return {
        '--preview-header-bg': styles.header,
        '--preview-sidebar-bg': styles.sidebar,
        '--preview-content-bg': styles.content
    }
}

// 获取主题颜色
const getThemeColors = (theme) => {
    const styles = {
        light: {
            header: '#ffffff',
            sidebar: '#ffffff',
            content: '#f1f5f9'
        },
        dark: {
            header: '#141414',
            sidebar: '#1f1f1f',
            content: '#0a0a0a'
        },
        black: {
            header: '#1e293b',
            sidebar: '#1e293b',
            content: '#f1f5f9'
        },
        blue: {
            header: '#1976d2',
            sidebar: '#1565c0',
            content: '#f5f7fa'
        },
        green: {
            header: '#388e3c',
            sidebar: '#2e7d32',
            content: '#f1f8e9'
        },
        orange: {
            header: '#ef6c00',
            sidebar: '#e65100',
            content: '#fff8e1'
        },
        purple: {
            header: '#7b1fa2',
            sidebar: '#6a1b9a',
            content: '#f5f0f7'
        }
    }
    
    return styles[theme] || styles.light
}

// 重置主题配置
const resetThemeConfig = () => {
    themeConfigStore.resetThemeConfig()
    // 移除所有主题类
    document.documentElement.classList.remove('dark', 'theme-blue', 'theme-green', 'theme-orange', 'theme-purple', 'theme-black')
    window.location.reload();
}
</script>


<style lang="scss" scoped>
.theme-selector,
.layout-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 15px;
}

.theme-item,
.layout-item {
    width: calc(33.33% - 8px);
    cursor: pointer;
    border-radius: 4px;
    overflow: hidden;
    transition: all 0.3s;
    border: 2px solid transparent;
    
    &.active {
        border-color: var(--el-color-primary);
    }
    
    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
    }
}

.theme-preview,
.layout-preview {
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}

.theme-preview .preview-header,
.layout-preview .preview-header {
    height: 20%;
    background-color: var(--preview-header-bg);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.theme-preview .preview-body,
.layout-preview .preview-body {
    display: flex;
    height: 80%;
}

.theme-preview .preview-sidebar,
.layout-preview .preview-sidebar {
    width: 30%;
    background-color: var(--preview-sidebar-bg);
}

.theme-preview .preview-content,
.layout-preview .preview-content {
    flex: 1;
    background-color: var(--preview-content-bg);
}

.theme-name,
.layout-name {
    text-align: center;
    font-size: 12px;
    margin-top: 4px;
    color: var(--el-text-color-regular);
}

/* 特殊布局预览样式 */
.layout-preview .preview-body {
    height: 100%;
}

.layout-item[data-layout="header-only"] .layout-preview .preview-header {
    height: 30%;
}
</style>