import { defineStore } from 'pinia'
import { nextTick } from 'vue'
import storage from '@/utils/storage'

// 从本地存储获取主题配置
const themeConfig = storage.get('themeConfig') ?? {}

const useThemeConfigStore = defineStore('themeConfig', {
    state: () => {
        return {
            isMobile: false,
            //是否折叠
            isCollapsed: themeConfig.isCollapsed ?? false,
            //是否刷新
            isRefreshRouter: true,
            //侧边栏宽度
            sideWidth: themeConfig.sideWidth ?? 200,
            //是否显示logo
            isShowLogo: themeConfig.isShowLogo ?? true,
            //是否唯一打开
            isUniqueOpened: themeConfig.isUniqueOpened ?? true,
            //是否暗黑模式
            isDarkMode: themeConfig.isDarkMode ?? false,
            // 默认主题
            theme: themeConfig.theme ?? 'light',
            // 布局
            layout: themeConfig.layout ?? 'sidebar-only',
            // 当前选中的顶部菜单
            currentTopMenu: themeConfig.currentTopMenu ?? ''
        }
    },
    actions: {
        toggleMenuCollapsed(value: boolean) {
            this.isCollapsed = value
        },
        refreshRouterView() {
            this.isRefreshRouter = false
            nextTick(() => {
                this.isRefreshRouter = true
            })
        },
        setThemeConfig(key: string, value: any) {
            if (key in this.$state) {
                this.$state[key] = value
                
                // 更新本地存储中的配置
                const currentConfig = storage.get('themeConfig') ?? {}
                currentConfig[key] = value
                storage.set('themeConfig', currentConfig)
            } else {
                console.warn(`Key ${key} does not exist in the state`)
            }
        },
        resetThemeConfig() {
            // 重置所有配置
            this.isCollapsed = false
            this.sideWidth = 200
            this.isShowLogo = true
            this.isUniqueOpened = true
            this.isDarkMode = false
            this.theme = 'light'
            this.layout = 'sidebar-only'
            this.currentTopMenu = ''
            
            // 清空本地存储
            storage.set('themeConfig', {})
        }
    }
})

export default useThemeConfigStore