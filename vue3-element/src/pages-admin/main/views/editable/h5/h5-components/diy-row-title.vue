<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            <el-form-item label="标题">
                <el-input v-model="store.selectedElement.settings.titleSetting.title" type="text"
                    placeholder="请输入标题" />

            </el-form-item>
            <el-form-item label="链接">
                <UniappLink v-model="store.selectedElement.settings.titleSetting.link" />
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
import UniappLink from './editors/uniapp-link/index.vue'

// 获取状态管理
const store = useEditableStore();

// 初始化数据
const initialFormData = {
    titleSetting: {
        // 标题名称
        title: '标题',
        // 链接
        link: '',
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