<template>
    <div>


        <span @click="openDialog" class="cursor-pointer">
            <slot></slot>
        </span>

        <el-dialog v-model="dialogVisible" title="素材" width="70%" class="attachment-dialog" :destroy-on-close="true">

            <attachment-manage-list :limit="limit" :type="type" ref="attachmentRef" />

            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogVisible = false">取消</el-button>
                    <el-button type="primary" @click="confirm">确认</el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script lang="ts" setup>
import { ref, toRaw } from 'vue'

import AttachmentManageList from '@/components/attachment/manage/list.vue'

const props = defineProps({
    // 选择数量限制
    limit: {
        type: Number,
        default: 1
    },
    type: {
        type: String,
        default: 'image'
    }
})




const dialogVisible = ref(false)
const attachmentRef: Record<string, any> | null = ref(null)


const openDialog = () => {
    dialogVisible.value = true
}

const emit = defineEmits(['confirm'])


const confirm = () => {
    dialogVisible.value = false

    let filesObj = attachmentRef?.value.selectedFile || {};
    let filesIndexObj = attachmentRef?.value.selectedFileIndex || {};


    let arr: Array<any> = [];  // 显式声明类型
    Object.values(filesIndexObj).forEach((item, index) => {
        for (let key in filesObj) {
            if (item == key) {
                arr.push(filesObj[key])
            }
        }
    })

    emit('confirm', props.limit == 1 ? arr[0] ?? null : arr)
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
})

</script>