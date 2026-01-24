<template>
  <div>
    <!-- 内容设置 -->
    <div v-show="store.selectedElementTab === 'content'">
      <el-form label-width="120px" class="px-[5px]">
        <!-- 分类设置 -->
        <el-card shadow="never" class="!border-none w-full mb-2">
          <template #header>
            <span>分类设置</span>
          </template>
          <el-form-item label="平台" required>
            <el-radio-group v-model="store.selectedElement.settings.categorySetting.platform">
              <el-radio v-for="item in platformList" :key="item.id" :label="item.platform"
                  :value="item.platform" border class="mb-[10px]">
                  {{ item.name }}
              </el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="显示数量">
            <el-slider 
              v-model="store.selectedElement.settings.categorySetting.category_nums" 
              :min="5" 
              :max="20" 
              :step="1"
              show-input 
              size="small"
            />
          </el-form-item>
        </el-card>

        <!-- 轮播图设置 -->
        <el-card shadow="never" class="!border-none w-full mb-2">
          <template #header>
            <span>轮播图设置</span>
          </template>
          <el-form-item label="高度">
            <el-slider 
              v-model="store.selectedElement.settings.swiperSetting.height" 
              :min="200" 
              :max="800" 
              :step="10" 
              show-input 
              size="small"
            />
          </el-form-item>
          <el-form-item label="是否自动播放">
            <el-switch v-model="store.selectedElement.settings.swiperSetting.autoplay" />
          </el-form-item>
          <el-form-item label="自动播放间隔">
            <el-slider 
              v-model="store.selectedElement.settings.swiperSetting.interval" 
              :min="1000" 
              :max="5000"
              :step="1000" 
              show-input 
              size="small"
            />
          </el-form-item>
          <el-form-item label="是否显示指示点">
            <el-switch v-model="store.selectedElement.settings.swiperSetting.indicator_dots" />
          </el-form-item>
        </el-card>

        <!-- 图片上传 -->
        <el-card shadow="never" class="!border-none w-full">
          <template #header>
            <span>轮播图片</span>
          </template>
          <AddableUploadImage v-model="store.selectedElement.settings.imagesList" />
        </el-card>
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
import AddableUploadImage from './editors/addable-upload-image.vue';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform';

const store = useEditableStore();

// 平台列表
const platformList = ref([])
// 获取平台列表
const fetchSysPlatformList = async () => {
    const res = await getSysPlatformList({ scene: 'store' })
    platformList.value = res.data
}
fetchSysPlatformList()

const initialFormData = {
  categorySetting: {
    platform: platformList.value.length > 0 ? platformList.value[0].platform : '',
    category_nums: 10,
  },
  swiperSetting: {
    height: 400,
    autoplay: true,
    interval: 3000,
    indicator_dots: true,
  },
  imagesList: [{ 
    image: '', 
    link: '',
    title: ''
  }],
};

watch(() => store.selectedElement?.settings, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    store.selectedElement.settings = initialFormData;
  } else {
    // 确保所有必需的字段都存在
    if (!newVal.categorySetting) {
      store.selectedElement.settings.categorySetting = {
        platform: platformList.value.length > 0 ? platformList.value[0].platform : '',
        category_nums: 10
      };
    } else {
      // 确保平台有值
      if (!newVal.categorySetting.platform && platformList.value.length > 0) {
        store.selectedElement.settings.categorySetting.platform = platformList.value[0].platform;
      }
    }
    if (!newVal.swiperSetting) {
      store.selectedElement.settings.swiperSetting = initialFormData.swiperSetting;
    }
    if (!Array.isArray(newVal.imagesList)) {
      store.selectedElement.settings.imagesList = initialFormData.imagesList;
    }
  }
}, { immediate: true, deep: false });

// 监听平台列表加载完成，设置默认平台
watch(platformList, (newVal) => {
  if (newVal.length > 0 && store.selectedElement?.settings?.categorySetting) {
    if (!store.selectedElement.settings.categorySetting.platform) {
      store.selectedElement.settings.categorySetting.platform = newVal[0].platform;
    }
  }
}, { immediate: true });
</script>

<style scoped>
/* 样式可以根据需要进行调整 */
</style>

