<template>
    <el-tooltip effect="light" :content="isFullscreen ? '退出全屏' : '全屏模式'" placement="bottom">
        <div @click="toggleFullscreen">
            <Icon :icon="`${isFullscreen ? 'element FullScreen' : 'element FullScreen'}`" :size="18"/>
        </div>
    </el-tooltip>
</template>

<script setup lang="ts">
import { useFullscreen } from '@vueuse/core'
import Icon from '@/components/icon/index.vue'
const { toggle: toggleFullscreen, isFullscreen } = useFullscreen()
</script>
