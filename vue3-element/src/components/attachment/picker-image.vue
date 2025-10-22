<template>

    <div class="picker-image-list flex flex-row justify-start">

        <VueDraggable v-model="imagesList.data" :animation="150" ghostClass="ghost-nav-item"  @end="handleChange" class="flex flex-row">
            <div class="item" v-for="(item, index) in imagesList.data" :key="index">
                <div :style="{ maxWidth: width, maxHeight: height }"
                    class="aspect-square flex items-center justify-center relative group border border-gray-300 rounded-lg mr-[10px] mb-[10px]">
                    <el-image v-if="type == 'image'" :src="formatFileUrl(item)" :alt="item" fit="cover"
                        :style="{ width: width, height: height }">
                    </el-image>
                    <video v-if="type == 'video'" :src="formatFileUrl(item)" controls :style="{ width: width, height: height }"
                        @click="previewVideo(item)"/>
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div
                        class="absolute inset-0 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <Icon icon="element View" :size="18" class="text-white cursor-pointer" @click="handlePreview(index)" />
                        <Icon icon="element Delete" :size="18" @click="removeImage(index)"
                            class="text-white ml-[10px] cursor-pointer" />
                    </div>
                </div>
            </div>
        </VueDraggable>


        <div v-if="imagesList.data.length < limit">
            <attachment-manage-index :limit="limit" @confirm="confirmSelect" :type="type">
                <div :style="{ width: width, height: height }"
                    class="flex items-center justify-center flex-col content-wrap aspect-square group border border-gray-300 rounded-lg">
                    <Icon icon="element Plus" :size="20" />
                </div>
            </attachment-manage-index>
        </div>

    </div>

    <!-- 图片预览组件 -->
    <el-image-viewer
        v-if="showImageViewer"
        :url-list="previewImageList"
        :initial-index="currentPreviewIndex"
        @close="showImageViewer = false"
    />

    <!-- 视频预览弹窗 -->
    <el-dialog v-model="videoPreviewVisible" :title="'视频预览'" width="600px" destroy-on-close>
        <video :src="previewVideoUrl" controls autoplay ></video>
    </el-dialog>

</template>

<script lang="ts" setup>
import { reactive, watch, ref, computed } from 'vue';

import { VueDraggable } from 'vue-draggable-plus';

import { formatFileUrl } from '@/utils/util'
import AttachmentManageIndex from '@/components/attachment/manage/index.vue'

import Icon from '@/components/icon/index.vue'
import { ElMessageBox, ElImageViewer } from 'element-plus';
import { toRaw } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Array],
        default: ''
    },
    limit: {
        type: Number,
        default: 1
    },
    width: {
        type: String,
        default: '100px'
    },
    height: {
        type: String,
        default: '100px'
    },
    type: {
        type: String,
        default: 'image'
    }

})


const imagesList: Record<string, any> = reactive({
    data: []
})

// 图片预览相关
const showImageViewer = ref(false);
const currentPreviewIndex = ref(0);
const previewImageList = computed(() => {
    return imagesList.data.map((url) => {
        return typeof url === 'string' && url.indexOf('data:image') !== -1 ? url : formatFileUrl(url);
    });
});

// 视频预览相关
const videoPreviewVisible = ref(false);
const previewVideoUrl = ref('');

watch(() => props.modelValue, () => {
    imagesList.data = Array.isArray(props.modelValue) ? props.modelValue : props.modelValue === '' ? [] : [props.modelValue]
}, { immediate: true })



const emit = defineEmits(['update:modelValue', 'change'])
const handleChange = () => {
    const image = props.limit === 1 ? imagesList.data[0] : imagesList.data
    emit('update:modelValue', image)
    emit('change', image)
}

// 处理预览
const handlePreview = (index) => {
    if (props.type === 'image') {
        currentPreviewIndex.value = index;
        showImageViewer.value = true;
    } else if (props.type === 'video') {
        previewVideo(imagesList.data[index]);
    }
}

// 视频预览处理
const previewVideo = (item: string) => {
    previewVideoUrl.value = formatFileUrl(item);
    videoPreviewVisible.value = true;
}

const confirmSelect = (data: Record<string, any>) => {
    if (props.limit == 1) {
        imagesList.data.splice(0, 1)
        data && imagesList.data.push(data.path)
    } else {
        data.forEach((item: any) => {
            if (imagesList.data.length < props.limit) imagesList.data.push(item.path)
        })
    }
    handleChange()
}

/**
 * 删除图片
 * @param index
 */
const removeImage = (index: number = 0) => {
    imagesList.data.splice(index, 1)
    handleChange()
}




</script>