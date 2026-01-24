<template>
    <div>

        <VueDraggable v-model="imageList" :animation="150" ghostClass="ghost-nav-item" class="flex flex-col gap-2">
            <div v-for="item in imageList" :key="item.id" class="bg-white p-3 mb-2 border rounded-md relative">

                <!-- 导航项设置 -->
                <div class="flex flex-row relative">
                    <!-- 图片选择 -->
                    <div class="mr-[10px]">
                        <PickerImage v-model="item.image" :limit="1" type="image" width="80px" height="80px"></PickerImage>
                    </div>
                    <div>
                        <!-- 名称设置 -->
                        <div class="flex flex-row gap-2 mb-[10px] items-center">
                            <div class="w-[60px] text-left text-gray-500">
                                名称：
                            </div>
                            <div>
                                <el-input v-model="item.title" placeholder="请输入导航名称"></el-input>
                            </div>
                         </div>   
                         <div class="flex flex-row gap-2 items-center">
                            <div class="w-[60px] text-left text-gray-500">
                                链接：
                            </div>
                            <div>
                                <UniappLink v-model="item.link" />
                            </div>
                         </div>   
                    </div>
                    <el-icon :disabled="imageList.length <= 1" @click="removeImage(item.id)"
                        class="absolute -top-[10px] -right-[10px]">
                        <Icon icon="element CircleCloseFilled" :size="25" color="#666" />
                    </el-icon>
                </div>
            </div>
        </VueDraggable>

        <!-- 添加导航按钮 -->
        <el-button type="primary" plain class="w-full" @click="addImage" :disabled="imageList.length >= 20">
            <el-icon class="mr-1">
                <Plus />
            </el-icon>添加图片
        </el-button>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import PickerImage from '@/components/attachment/picker-image.vue'
import { VueDraggable } from 'vue-draggable-plus';
import UniappLink from './uniapp-link/index.vue'
import Icon from '@/components/icon/index.vue';

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
    image: '',
    title: '图片标题',
    link: ''
});

// 用于同步 PickerImage 的值
const imageList = ref(props.modelValue.length > 0 ? props.modelValue : [createDefaultNavItem()]);

// 添加导航项
function addImage() {

    imageList.value.push(createDefaultNavItem());
}

// 移除导航项
function removeImage(id) {
    if (imageList.value.length > 1) {
        imageList.value = imageList.value.filter(item => item.id !== id);
    }
}



// 监听 imageValue 变化并同步到父组件
watch(imageList, (newVal) => {
    emit('update:modelValue', newVal);
}, { deep: true });

// 监听 props.modelValue 变化并同步到本地
watch(() => props.modelValue, (newVal) => {
    if (newVal.length > 0) {
        imageList.value = newVal;
    } else {
        imageList.value = [createDefaultNavItem()]; // 确保有默认项
    }
}, { immediate: true, deep: false });





</script>
