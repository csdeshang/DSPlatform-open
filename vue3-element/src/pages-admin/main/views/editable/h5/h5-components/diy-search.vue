<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            
            <el-form-item label="显示Logo">
                <el-switch v-model="store.selectedElement.settings.logoSetting.is_show" :active-value="1" :inactive-value="0" />
            </el-form-item>
            <el-form-item label="Logo名称">
                <el-input v-model="store.selectedElement.settings.logoSetting.name" placeholder="请输入Logo名称" />
            </el-form-item>
            <el-form-item label="Logo图片">
                <PickerImage v-model="store.selectedElement.settings.logoSetting.image" :limit="1" type="image" />
            </el-form-item>
            <el-form-item label="显示lbs">
                <el-switch v-model="store.selectedElement.settings.lbsSetting.is_show" :active-value="1" :inactive-value="0" />
                <div class="text-gray-500 text-sm ml-[20px]">开启后请先配置好地图</div>
            </el-form-item>
            <el-form-item label="搜索提示词">
                <el-input v-model="store.selectedElement.settings.searchSetting.placeholder" placeholder="请输入搜索提示词" />
            </el-form-item>

        </div>

        <!-- 样式设置 -->
        <div v-show="store.selectedElementTab === 'style'">
            <BaseStyles />
        </div>
    </div>
</template>

<script setup>
import { watch } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import PickerImage from '@/components/attachment/picker-image.vue'


// 获取状态管理
const store = useEditableStore();



// 初始化数据
const initialFormData = {
    logoSetting: {
        is_show: true,
        image: '',
        name: '',
    },
    lbsSetting: {
        is_show: false,
    },
    searchSetting: {
        //搜索提示词
        placeholder: '',
    }
}


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