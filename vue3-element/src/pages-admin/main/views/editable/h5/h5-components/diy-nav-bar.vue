<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">
            <div class="text-sm text-gray-500 mb-4">导航设置</div>


            <AddableUploadImage v-model="store.selectedElement.settings.navList" />

            <!-- 导航布局设置 -->
            <div class="mt-4">
                <el-form-item label="每行显示">
                    <el-radio-group v-model="store.selectedElement.settings.navSettings.itemsPerRow">
                        <el-radio-button :value="3">3个</el-radio-button>
                        <el-radio-button :value="4">4个</el-radio-button>
                        <el-radio-button :value="5">5个</el-radio-button>
                    </el-radio-group>
                </el-form-item>
            </div>
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

import AddableUploadImage from './editors/addable-upload-image.vue';

// 获取状态管理
const store = useEditableStore();







// 初始化数据
const initialFormData = {
    navList: [],
    navSettings: { itemsPerRow: 4 }
}
// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
    if (!newVal || Object.keys(newVal).length === 0) {
        store.selectedElement.settings = initialFormData
    }
}, { immediate: true, deep: false });



</script>

<style scoped>
.ghost-nav-item {
    opacity: 0.5;
    background: #c8ebfb;
}

.drag-handle {
    cursor: move;
}

/* 拖拽时的样式 */
.sortable-chosen {
    background-color: #f0f9ff;
    border: 1px dashed #409eff;
}

/* 拖拽时的占位符样式 */
.sortable-ghost {
    opacity: 0.5;
    background: #e6f7ff;
    border: 1px dashed #1890ff;
}

/* 拖拽动画 */
.flip-list-move {
    transition: transform 0.3s;
}
</style>