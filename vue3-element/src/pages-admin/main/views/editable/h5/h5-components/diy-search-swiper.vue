<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            <el-form label-width="120px" class="px-[5px]">
                <el-form-item label="显示Logo">
                    <el-switch v-model="store.selectedElement.settings.logoSetting.is_show" :active-value="1"
                        :inactive-value="0" />
                </el-form-item>
                <el-form-item label="Logo名称">
                    <el-input v-model="store.selectedElement.settings.logoSetting.name" placeholder="请输入Logo名称" />
                </el-form-item>
                <el-form-item label="Logo图片">
                    <PickerImage v-model="store.selectedElement.settings.logoSetting.image" :limit="1" type="image" width="60px" height="60px" />
                </el-form-item>
                <el-form-item label="显示lbs">
                    <div>
                        <el-switch v-model="store.selectedElement.settings.lbsSetting.is_show" :active-value="1"
                            :inactive-value="0" />
                    </div>
                    <div class="text-gray-500 text-sm ml-[20px]">开启后请先配置好地图</div>
                </el-form-item>
                <el-form-item label="提示词">
                    <el-input v-model="store.selectedElement.settings.searchSetting.placeholder"
                        placeholder="请输入搜索提示词" />
                </el-form-item>

                <el-form-item label="轮播图高度">
                    <el-slider v-model="store.selectedElement.settings.swiperSetting.height" :min="100" :max="1000"
                        :step="10" show-input size="small" />
                </el-form-item>

                <el-form-item label="是否自动播放">
                    <el-switch v-model="store.selectedElement.settings.swiperSetting.autoplay" />
                </el-form-item>
                <el-form-item label="自动播放间隔">
                    <el-slider v-model="store.selectedElement.settings.swiperSetting.interval" :min="1000" :max="5000"
                        :step="1000" show-input size="small" />
                </el-form-item>

                <el-form-item label="是否显示指示点">
                    <el-switch v-model="store.selectedElement.settings.swiperSetting.indicator_dots" />
                </el-form-item>




            </el-form>

            <AddableUploadImage v-model="store.selectedElement.settings.imagesList" />

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

import AddableUploadImage from './editors/addable-upload-image.vue';
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
    },
    swiperSetting: {
        // 高度
        height: 300,
        // 是否自动播放
        autoplay: true,
        // 自动播放间隔
        interval: 3000,
        // 是否显示指示点
        indicator_dots: true,
    },
    imagesList: []
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