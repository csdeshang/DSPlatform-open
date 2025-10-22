<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            <el-form-item label="行间距">
                <el-slider v-model="store.selectedElement.settings.blankSetting.blankHeight" show-input size="small"
                    class="ml-[10px]" :max="500" />

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
    blankSetting: {
        // 行间距
        blankHeight: 10,
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