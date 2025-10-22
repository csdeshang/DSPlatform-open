<template>
    <el-tooltip effect="light" :content="isCollapsed ? '展开菜单' : '收起菜单'" placement="bottom">
        <div @click="toggleMenuCollapsed">
            <Icon :icon="`${isCollapsed ? 'element Expand' : 'element Fold'}`" :size="18"/>
        </div>

    </el-tooltip>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import useThemeConfigStore from '@/stores/modules/themeConfig'
import Icon from '@/components/icon/index.vue'

const themeConfigStore = useThemeConfigStore()
const isCollapsed = computed(() => themeConfigStore.isCollapsed)
// 折叠菜单
const toggleMenuCollapsed = () => {
    themeConfigStore.toggleMenuCollapsed(!themeConfigStore.isCollapsed)
}
</script>