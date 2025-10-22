<template>
  <div>
    <div class="text-base mb-2 font-bold">基础样式</div>


    <el-form-item label="上外边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.margin.top" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="下外边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.margin.bottom" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="左外边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.margin.left" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="右外边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.margin.right" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>


    <el-form-item label="上内边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.padding.top" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="下内边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.padding.bottom" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="左内边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.padding.left" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>
    <el-form-item label="右内边距">
      <el-slider v-model="store.selectedElement.settings.baseStyles.padding.right" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>

    <div class="text-base mb-2 mt-4 font-bold">字体样式</div>

    <el-form-item label="字体颜色">
      <el-color-picker v-model="store.selectedElement.settings.baseStyles.font.color" show-alpha size="small"
        class="ml-[10px]" />
    </el-form-item>
    <el-form-item label="字体大小">
      <el-slider v-model="store.selectedElement.settings.baseStyles.font.size" show-input size="small" class="ml-[10px]"
        :max="100" />
    </el-form-item>
    <el-form-item label="字体粗细">
      <el-slider v-model="store.selectedElement.settings.baseStyles.font.weight" show-input size="small"
        class="ml-[10px]" :max="100" />
    </el-form-item>


    <el-form-item label="背景渐变">
      <el-color-picker v-model="store.selectedElement.settings.baseStyles.gbColor.from" show-alpha size="small"
        class="ml-[10px]" />
      <el-color-picker v-model="store.selectedElement.settings.baseStyles.gbColor.to" show-alpha size="small"
        class="ml-[10px]" />
    </el-form-item>

    <!-- 圆角设置 -->
    <div class="text-base mb-2 mt-4 font-bold">圆角设置</div>
    
    <el-form-item label="统一圆角">
      <el-switch v-model="store.selectedElement.settings.baseStyles.borderRadius.isAll" />
    </el-form-item>
    
    <!-- 统一圆角设置 -->
    <el-form-item v-if="store.selectedElement.settings.baseStyles.borderRadius.isAll" label="圆角大小">
      <el-slider v-model="store.selectedElement.settings.baseStyles.borderRadius.all" 
        show-input size="small" class="ml-[10px]" :max="50" />
    </el-form-item>
    
    <!-- 单独圆角设置 -->
    <template v-else>
      <el-form-item label="左上圆角">
        <el-slider v-model="store.selectedElement.settings.baseStyles.borderRadius.topLeft" 
          show-input size="small" class="ml-[10px]" :max="50" />
      </el-form-item>
      
      <el-form-item label="右上圆角">
        <el-slider v-model="store.selectedElement.settings.baseStyles.borderRadius.topRight" 
          show-input size="small" class="ml-[10px]" :max="50" />
      </el-form-item>
      
      <el-form-item label="左下圆角">
        <el-slider v-model="store.selectedElement.settings.baseStyles.borderRadius.bottomLeft" 
          show-input size="small" class="ml-[10px]" :max="50" />
      </el-form-item>
      
      <el-form-item label="右下圆角">
        <el-slider v-model="store.selectedElement.settings.baseStyles.borderRadius.bottomRight" 
          show-input size="small" class="ml-[10px]" :max="50" />
      </el-form-item>
    </template>
  </div>
</template>

<script setup>
import useEditableStore from '@/stores/modules/editable';
import { watch } from 'vue';

// 获取状态管理
const store = useEditableStore();




// 初始化数据
const initialFormData = {
  margin: { top: 0, bottom: 0, left: 0, right: 0 },
  padding: { top: 0, bottom: 0, left: 0, right: 0 },
  font: { color: '#333333', size: 13, weight: 400 },
  gbColor: { from: '#ffffff', to: '#ffffff' },
  borderRadius: {
    // 是否使用所有圆角
    isAll: false,
    // 所有圆角
    all: 0,
    // 左上角
    topLeft: 0,
    // 右上角
    topRight: 0,
    // 左下角
    bottomLeft: 0,
    // 右下角
    bottomRight: 0,
  },
}


// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
  if (!newVal.baseStyles || Object.keys(newVal.baseStyles).length === 0) {

    // 如果 baseStyles 不存在，初始化它
    store.selectedElement.settings.baseStyles = initialFormData;
  }
}, { immediate: true, deep: false });

</script>

<style scoped>
/* 样式可以根据需要进行调整 */
</style>
