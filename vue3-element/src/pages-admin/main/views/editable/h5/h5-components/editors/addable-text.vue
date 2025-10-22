<template>
    <div>

        <VueDraggable v-model="textList" :animation="150" ghostClass="ghost-nav-item" class="flex flex-col gap-2">
            <div v-for="item in textList" :key="item.id" class="bg-white p-3 mb-2 border rounded-md relative">

                <!-- 导航项设置 -->
                <div class="flex flex-row relative">
                    <div>
                        <!-- 名称设置 -->
                        <el-form-item label="名称">
                            <el-input v-model="item.title" placeholder="请输入文本标题"></el-input>
                        </el-form-item>

                        <!-- 链接设置 -->
                        <el-form-item label="链接">
                            <UniappLink v-model="item.link" />
                        </el-form-item>
                    </div>
                    <el-icon :disabled="textList.length <= 1" @click="removeText(item.id)"
                        class="absolute -top-[10px] -right-[10px]">
                        <Delete />
                    </el-icon>
                </div>
            </div>
        </VueDraggable>

        <!-- 添加导航按钮 -->
        <el-button type="primary" plain class="w-full" @click="addText" :disabled="textList.length >= 10">
            <el-icon class="mr-1">
                <Plus />
            </el-icon>添加文本
        </el-button>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { VueDraggable } from 'vue-draggable-plus';
import UniappLink from './uniapp-link/index.vue'

const props = defineProps({
    modelValue: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['update:modelValue']);


// 生成唯一ID
const generateId = () => `nav_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
// 创建默认导航项
const createDefaultNavItem = () => ({
    id: generateId(),
    title: '文本标题',
    link: ''
});

// 用于同步 
const textList = ref(props.modelValue.length > 0 ? props.modelValue : [createDefaultNavItem()]);

// 添加导航项
function addText() {

    textList.value.push(createDefaultNavItem());
}

// 移除导航项
function removeText(id) {
    if (textList.value.length > 1) {
        textList.value = textList.value.filter(item => item.id !== id);
    }
}



// 监听 textList 变化并同步到父组件
watch(textList, (newVal) => {
    emit('update:modelValue', newVal);
}, { deep: true });

// 监听 props.modelValue 变化并同步到本地
watch(() => props.modelValue, (newVal) => {
    if (newVal.length > 0) {
        textList.value = newVal;
    } else {
        textList.value = [createDefaultNavItem()]; // 确保有默认项
    }
}, { immediate: true, deep: false });





</script>
