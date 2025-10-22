<template>
  <div class="header-breadcrumb">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item v-for="(item, index) in breadcrumbs" :key="index" :to="item.path">
        {{ item.meta && item.meta.title }}
      </el-breadcrumb-item>
    </el-breadcrumb>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useRoute, RouteLocationMatched } from 'vue-router'

const route = useRoute()
const breadcrumbs = ref<RouteLocationMatched[]>([])

// 过滤路由，只保留有 meta.title 的路由
const getBreadcrumbs = () => {
  // 过滤掉没有 meta.title 的路由
  const matched = route.matched.filter(item => item.meta && item.meta.title)
  
  // 如果是首页，则只显示首页
  if (matched.length === 1 && matched[0].path === '/') {
    breadcrumbs.value = matched
    return
  }
  
  // 如果不是首页，则添加首页作为第一个面包屑
  const firstRoute = { path: '/', meta: { title: '首页' } }
  
  // 如果当前路由是首页的子路由，则不需要添加首页
  if (matched.length > 0 && matched[0].path === '/') {
    breadcrumbs.value = matched
  } else {
    breadcrumbs.value = [firstRoute as any, ...matched]
  }
}

// 监听路由变化，更新面包屑
watch(
  () => route.path,
  () => getBreadcrumbs(),
  { immediate: true }
)
</script>

<style scoped>
.header-breadcrumb {
  display: flex;
  align-items: center;
  height: 100%;
  margin-left: 16px;
}

:deep(.el-breadcrumb) {
  font-size: 14px;
}

:deep(.el-breadcrumb__item) {
  display: inline-flex;
  align-items: center;
}

:deep(.el-breadcrumb__inner) {
  color: var(--app-header-text);
  opacity: 0.7;
}

:deep(.el-breadcrumb__item:last-child .el-breadcrumb__inner) {
  color: var(--app-header-text);
  opacity: 1;
}

:deep(.el-breadcrumb__separator) {
  color: var(--app-header-text);
  opacity: 0.7;
}
</style> 