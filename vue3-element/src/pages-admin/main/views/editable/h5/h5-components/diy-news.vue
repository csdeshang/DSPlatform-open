<template>
    <div v-show="store.selectedElementTab === 'content'">
        <el-form-item label="类型">
            <el-radio-group v-model="store.selectedElement.settings.newsSetting.type">
                <el-radio-button label="图片" value="image"></el-radio-button>
                <el-radio-button label="文本" value="text"></el-radio-button>
            </el-radio-group>
        </el-form-item>

        <el-form-item label="图片">
            <PickerImage v-model="store.selectedElement.settings.newsSetting.image" :limit="1" type="image" />
        </el-form-item>

        <el-form-item label="标题">
            <el-input v-model="store.selectedElement.settings.newsSetting.title" placeholder="请输入标题" />
        </el-form-item>


        <el-form-item label="是否显示右侧按钮">
            <el-switch v-model="store.selectedElement.settings.newsSetting.is_show_right_btn" active-value="1"
                inactive-value="0" />
        </el-form-item>

        <AddableText v-model="store.selectedElement.settings.newsList" />
      


    </div>
    <div v-show="store.selectedElementTab === 'style'">
        <BaseStyles />
    </div>
</template>

<script setup>
import { watch } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';

import AddableText from './editors/addable-text.vue'

import PickerImage from '@/components/attachment/picker-image.vue'

// 获取状态管理
const store = useEditableStore();


// 初始化数据
const initialFormData = {
    newsList: [],
    // 
    newsSetting: {
        type: 'image',
        image: '',
        // 标题
        title: '',
        // 右侧按钮图标
        is_show_right_btn: false,


    }
}


// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
    if (!newVal || Object.keys(newVal).length === 0) {
        store.selectedElement.settings = initialFormData;
    }
}, { immediate: true, deep: false });





</script>