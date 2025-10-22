<template>
    <div class="flex flex-row w-full tabs-wrapper">
        <div class="flex flex-1 tabs-container">
            <el-tabs 
                :model-value="multiTagsStore.getActiveTab" 
                closable 
                @tab-remove="removeTab" 
                @tab-change="handleChange" 
                class="w-full custom-tabs"
            >
                <el-tab-pane 
                    v-for="item in multiTagsStore.tabList" 
                    :key="item.name" 
                    :label="item.title"
                    :name="item.fullPath"
                >
                </el-tab-pane>
            </el-tabs>
        </div>
        <div class="dropdown-container">
            <el-dropdown @command="handleCommand">
                <span class="flex dropdown-trigger">
                    <Icon icon="element ArrowDown" :size="20"/>
                </span>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item command="closeCurrent">关闭当前</el-dropdown-item>
                        <el-dropdown-item command="closeOther">关闭其他</el-dropdown-item>
                        <el-dropdown-item command="closeAll">关闭全部</el-dropdown-item>
                        <el-dropdown-item command="refresh" divided>刷新当前页</el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import Icon from '@/components/icon/index.vue'
import useMultiTagsStore, { getRouteParams } from '@/stores/modules/multiTags'
import useThemeConfigStore from '@/stores/modules/themeConfig'

const route = useRoute()
const router = useRouter()
const multiTagsStore = useMultiTagsStore()
const themeConfigStore = useThemeConfigStore()

/**
 * 处理标签切换
 * @param fullPath 路由完整路径
 */
const handleChange = (fullPath: string) => {
    const tabItem = multiTagsStore.tabMap[fullPath]
    if (tabItem) {
        router.push(getRouteParams(tabItem))
        multiTagsStore.setActiveTab(fullPath)
    }
}

/**
 * 处理下拉菜单命令
 * @param command 命令
 */
const handleCommand = (command: string) => {
    switch (command) {
        case 'closeCurrent':
            removeTab(route.fullPath)
            break
        case 'closeOther':
            removeOtherTabs()
            break
        case 'closeAll':
            removeAllTabs()
            break
        case 'refresh':
            refreshCurrentPage()
            break
    }
}

/**
 * 移除标签
 * @param fullPath 路由完整路径
 */
function removeTab(fullPath?: string) {
    fullPath = fullPath ?? route.fullPath
    multiTagsStore.removeTab(fullPath, router)
}

/**
 * 移除其他标签
 */
function removeOtherTabs() {
    multiTagsStore.removeOtherTabs(route)
}

/**
 * 移除所有标签
 */
function removeAllTabs() {
    multiTagsStore.removeAllTabs(router)
}

/**
 * 刷新当前页面
 */
function refreshCurrentPage() {
    multiTagsStore.refreshCurrentTab()
}
</script>

<style scoped>
.tabs-wrapper {
    position: relative;
    border-bottom: 1px solid var(--el-border-color-light);
    background-color: var(--el-bg-color);
}

.tabs-container {
    width: calc(100% - 40px); /* 为下拉菜单预留空间 */
    overflow: hidden;
}

.dropdown-container {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dropdown-trigger {
    cursor: pointer;
    padding: 6px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.dropdown-trigger:hover {
    background-color: var(--el-fill-color-light);
}

:deep(.el-tabs__header) {
    margin: 0;
}

:deep(.el-tabs__nav-wrap) {
    margin-left: 15px;
    margin-right: 40px; /* 确保标签不会与下拉菜单重叠 */
}

/* 修复底部线条重叠问题 */
:deep(.el-tabs__nav-wrap::after) {
    display: none !important; /* 移除默认的底部线条 */
}

:deep(.el-tabs__nav) {
    border-bottom: none !important; /* 移除导航栏底部边框 */
}

:deep(.el-tabs__item) {
    border-bottom: none !important; /* 移除标签项底部边框 */
}

/* 自定义活动标签样式 */
:deep(.el-tabs__item.is-active) {
    color: var(--el-color-primary);
    font-weight: 500;
}

:deep(.el-tabs__item:hover) {
    color: var(--el-color-primary);
}
</style>

