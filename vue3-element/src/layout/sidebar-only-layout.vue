<template>
    <el-container class="h-full">
        <lay-sidebar />
        <el-container class="flex flex-col">
            <lay-header />
            <lay-main class="flex-1 overflow-hidden" />
        </el-container>
    </el-container>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import LaySidebar from './components/lay-sidebar/index.vue'
import LayHeader from './components/lay-header/index.vue'
import LayMain from './components/lay-main/index.vue'
</script>

<style scoped>
/* 确保布局容器占满整个高度 */
:deep(.el-container) {
  height: 100%;
}

/* 优化侧边栏与主内容区的过渡效果 */
:deep(.el-aside) {
  transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

:deep(.el-main) {
  transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);

}
</style>