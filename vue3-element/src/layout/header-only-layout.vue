<template>
  <el-container class="h-full">
    <el-header>
      <lay-header />
    </el-header>
    <lay-main class="flex-1 overflow-hidden" />
  </el-container>
</template>

<script setup lang="ts">
import LayHeader from './components/lay-header/index.vue'
import LayMain from './components/lay-main/index.vue'
</script>

<style scoped>
.el-header {
  height: var(--app-header-height);
  line-height: var(--app-header-height);
}

:deep(.el-container) {
  height: 100%;
}


</style>