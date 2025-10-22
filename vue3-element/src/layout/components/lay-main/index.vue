<template>

  <div class="flex flex-col">

    <multi-tags />
    <el-main class="h-full flex-1">

      <el-scrollbar class="p-[20px]">
        <router-view v-slot="{ Component, route }" v-if="themeConfigStore.isRefreshRouter">
          <keep-alive :include="multiTagsStore.getCacheTabList">
            <component :is="Component" :key="route.fullPath" />
          </keep-alive>
        </router-view>

        <lay-footer />
      </el-scrollbar>
    </el-main>
  </div>
</template>

<script lang="ts" setup>
import multiTags from './multi-tags.vue'
import layFooter from '../lay-footer/index.vue'

import useThemeConfigStore from '@/stores/modules/themeConfig'
const themeConfigStore = useThemeConfigStore()

import useMultiTagsStore from '@/stores/modules/multiTags'
const multiTagsStore = useMultiTagsStore()

</script>

