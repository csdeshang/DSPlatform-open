<template>
 
    <el-sub-menu v-if="route.children" :index="route.name">
        <template #title>
            <Icon :icon="route.meta.icon" :size="20"/>
            <span>{{ route.meta.title }}</span>
        </template>
        <sidebar-menu-item v-for="item in route.children" :key="item.path" :route="item" />
    </el-sub-menu>
    <el-menu-item v-else :index="route.name" @click="router.push({ name: route.name })">
        <Icon :icon="route.meta.icon" :size="20"/>
        <template #title>
            <span>{{ route.meta.title }}</span>
        </template>
    </el-menu-item>

</template>

<script setup lang="ts">
import { useRouter } from "vue-router";

const router = useRouter()

import SidebarMenuItem from './menu-item.vue'
import Icon from '@/components/icon/index.vue'


const props = defineProps({
    route: {
        type: Object,
        required: true
    },
})


</script>

<style scoped>
/* 菜单项样式 - 简化版 */
:deep(.el-menu-item), :deep(.el-sub-menu__title) {
  transition: background-color 0.3s, color 0.3s;
}

/* 图标样式 */
:deep(.icon) {
  margin-right: 10px;
  font-size: 18px;
}
</style>