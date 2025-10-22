<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            <el-form-item label="类型">
                <el-radio-group v-model="store.selectedElement.settings.lineSetting.style">
                    <el-radio value="solid">实线</el-radio>
                    <el-radio value="dashed">虚线</el-radio>
                    <el-radio value="dotted">点线</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="高度">
                <el-slider v-model="store.selectedElement.settings.lineSetting.height" show-input size="small"
                    class="ml-[10px]" :max="50" />
            </el-form-item>
            <el-form-item label="颜色">
                <el-color-picker v-model="store.selectedElement.settings.lineSetting.color" />
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

// 获取状态管理
const store = useEditableStore();

// 初始化数据
const initialFormData = {
    lineSetting: {
        // 实线 虚线 点线
        style: 'solid',
        //高度
        height: 1,
        //颜色
        color: '#000',
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