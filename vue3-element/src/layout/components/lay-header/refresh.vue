<template>
    <el-tooltip effect="light" content="刷新" placement="bottom">
        <div @click="refreshRouterView">
            <Icon icon="element Refresh" :size="18"/>
        </div>
    </el-tooltip>
</template>

<script setup lang="ts">
import useThemeConfigStore from '@/stores/modules/themeConfig'
import Icon from '@/components/icon/index.vue'
const themeConfigStore = useThemeConfigStore()
// 页面刷新
const refreshRouterView = () => {
    themeConfigStore.refreshRouterView()
}
</script>
