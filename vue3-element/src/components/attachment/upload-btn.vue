<template>


    <div class="upload">

        <el-upload v-model:file-list="fileList" ref="uploadRef" :action="action" :multiple="multiple"
            :limit="uploadLimit" :show-file-list="false" :headers="headers" :data="uploadData"
            :on-progress="handleProgress" :on-success="handleSuccess" :on-exceed="handleExceed" :on-error="handleError"
            :accept="getAccept">
            <slot></slot>
        </el-upload>
        <el-dialog v-if="showProgress && fileList.length" v-model="visible" title="上传进度" :close-on-click-modal="false"
            width="500px" :modal="false" @close="handleClose">
            <div class="file-list p-4">
                <template v-for="(item, index) in fileList" :key="index">
                    <div class="mb-5">
                        <div>{{ item.name }}</div>
                        <div class="flex-1">
                            <el-progress :percentage="parseInt(item.percentage)" />
                        </div>
                    </div>
                </template>
            </div>
        </el-dialog>


    </div>

</template>

<script lang="ts">


import { ElMessage, type ElUpload } from 'element-plus'
import { computed, defineComponent, ref, shallowRef } from 'vue'


import { getToken } from '@/utils/auth'

import { getSystemType } from '@/utils/util'

export default defineComponent({
    components: {},
    props: {
        // 上传文件类型
        type: {
            type: String,
            default: 'image'
        },
        // 是否支持多选
        multiple: {
            type: Boolean,
            default: true
        },
        // 多选时最多选择几条
        uploadLimit: {
            type: Number,
            default: 10
        },
        // 上传时的额外参数
        uploadData: {
            type: Object,
            default: () => ({})
        },
        // 是否显示上传进度
        showProgress: {
            type: Boolean,
            default: false
        }
    },
    emits: ['change', 'error', 'success', 'allSuccess'],
    setup(props, { emit }) {

        const uploadRef = shallowRef<InstanceType<typeof ElUpload>>()

        // 计算 action URL
        const action = computed(() => {
            const baseUrl = import.meta.env.VITE_APP_BASE_URL
            return getSystemType() === 'admin'
                ? `${baseUrl}/adminapi/attachment/file/${props.type}`
                : `${baseUrl}/api/attachment/file/${props.type}`
        })


        const headers: Record<string, any> = {}
        headers['access-token'] = getToken('access_token')

        const visible = ref(false)
        const fileList = ref<any[]>([])

        const handleProgress = () => {
            visible.value = true
        }
        let uploadLen = 0
        const handleSuccess = (response: any, file: any) => {
            uploadLen++
            if (uploadLen == fileList.value.length) {
                resetUploadState();
            }
            emit('change', file)
            if (response.code == 10000) {
                emit('success', response)
            }
            if (response.code == 10001 && response.message) {
                ElMessage.error(response.message)
            }
        }
        const handleError = (event: any, file: any) => {
            uploadLen++
            if (uploadLen == fileList.value.length) {
                resetUploadState();
            }
            ElMessage.error(`${file.name}文件上传失败`)
            uploadRef.value?.abort(file)
            visible.value = false
            emit('change', file)
            emit('error', file)
        }
        const handleExceed = () => {
            ElMessage.error(`超出上传上限${props.uploadLimit}，请重新上传`)
        }
        const handleClose = () => {
            fileList.value = []
            visible.value = false
        }

        const resetUploadState = () => {
            uploadLen = 0;
            fileList.value = [];
            emit('allSuccess');
        }

        const acceptMap = {
            image: '.jpg,.png,.gif,.jpeg,.ico',
            video: '.wmv,.avi,.mpg,.mpeg,.3gp,.mov,.mp4,.flv,.rmvb,.mkv'
        }

        const getAccept = computed(() => {
            return acceptMap[props.type] || '*';
        })


        return {
            uploadRef,
            action,
            headers,
            visible,
            fileList,
            getAccept,
            handleProgress,
            handleSuccess,
            handleError,
            handleExceed,
            handleClose
        }
    }
})


</script>

<style lang="scss"></style>
