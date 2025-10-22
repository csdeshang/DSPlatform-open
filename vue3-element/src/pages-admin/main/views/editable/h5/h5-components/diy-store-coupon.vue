<template>
  <div>
    <!-- 内容设置 -->
    <div v-show="store.selectedElementTab === 'content'">

      <el-form :model="store.selectedElement.settings.couponSetting" label-width="120px">

        <el-form-item label="平台">
          <el-radio-group v-model="store.selectedElement.settings.couponSetting.platform">
            <el-radio label="all" border value="" class="mb-[10px]">全部</el-radio>
            <el-radio v-for="item in platformList" :key="item.id" :label="item.platform" :value="item.platform" border
              class="mb-[10px]">
              {{ item.name }}
            </el-radio>
          </el-radio-group>
        </el-form-item>


        <!-- 头部设置 -->
        <el-form-item label="显示头部标题">
          <el-switch v-model="store.selectedElement.settings.couponSetting.is_show_header_title"></el-switch>
        </el-form-item>

        <el-form-item label="头部标题">
          <el-input v-model="store.selectedElement.settings.couponSetting.header_title"></el-input>
        </el-form-item>

        <el-form-item label="头部更多链接">
          <UniappLink v-model="store.selectedElement.settings.couponSetting.header_more_link" />
        </el-form-item>

        <el-form-item label="显示数量">
          <el-slider v-model="store.selectedElement.settings.displayCount" show-input size="small" class="ml-[10px]"
            :max="50" />
        </el-form-item>


      </el-form>

    </div>

    <!-- 样式设置 -->
    <div v-show="store.selectedElementTab === 'style'">
      <BaseStyles />
    </div>
  </div>
</template>

<script setup>
import { watch, ref } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform';
import UniappLink from './editors/uniapp-link/index.vue'
// 获取状态管理
const store = useEditableStore();

// 初始化数据
const initialFormData = {
  couponSetting: {
    // 平台 all:全部 platform:平台
    platform: 'all',
    // 显示数量
    displayCount: 10,
    // 是否显示头部标题
    is_show_header_title: false,
    // 头部标题
    header_title: '头部标题自定义',
    // 头部更多链接
    header_more_link: '',
  }
}


// 平台列表
const platformList = ref([])
// 获取平台列表 store 类型
const fetchSysPlatformList = async () => {
  const res = await getSysPlatformList({ scene: 'store' })
  platformList.value = res.data
}
fetchSysPlatformList()




// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    store.selectedElement.settings = initialFormData;
  }
}, { immediate: true, deep: false });




</script>

<style scoped>
/* 样式可以根据需要进行调整 */
</style>