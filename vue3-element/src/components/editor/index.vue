<template>
    <div class="rounded-md flex flex-col" :style="styles" >
        <Toolbar class="border-b" :defaultConfig="toolbarConfig" :editor="editorRef" :mode="mode" :style="{ borderColor: 'var(--el-border-color)' }" />
        <Editor v-model="valueHtml" :mode="mode" :defaultConfig="editorConfig" @onCreated="handleCreated"
            @onChange="handleChange" @onFocus="handleFocus" @onBlur="handleBlur" />
        <AttachmentManageIndex ref="attachmentManageRef" :type="fileType" :limit="20"
            @confirm="handleAttachmentSelect" />
    </div>



</template>

<script setup lang="ts">
import '@wangeditor/editor/dist/css/style.css'

import { ref, shallowRef, computed, onBeforeUnmount, watch } from 'vue'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'
import type { CSSProperties } from 'vue'
import AttachmentManageIndex from '@/components/attachment/manage/index.vue'

import { formatFileUrl } from '@/utils/util'

import { IEditorConfig, IToolbarConfig } from '@wangeditor/editor'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    mode: {
        type: String,
        default: 'default'
    },
    height: {
        type: [String, Number],
        default: '600px'
    },
    width: {
        type: [String, Number],
        default: 'auto'
    },
    placeholder: {
        type: String,
        default: '请输入内容...'
    },
    disabled: {
        type: Boolean,
        default: false
    },
})

const emit = defineEmits(['update:modelValue'])

// 编辑器实例，必须用 shallowRef
const editorRef = shallowRef()
const attachmentManageRef = shallowRef()
const fileType = ref('image')

type InsertFnType = (url: string, alt: string, href: string) => void

// 用于存储插入函数的引用
let insertFn: InsertFnType | null = null

// 计算样式
const styles = computed<CSSProperties>(() => ({
    height: typeof props.height === 'number' ? `${props.height}px` : props.height,
    width: typeof props.width === 'number' ? `${props.width}px` : props.width
}))

// 编辑器内容
const valueHtml = ref(props.modelValue)




// 自定义编辑器配置
const editorConfig: Partial<IEditorConfig> = {
    placeholder: props.placeholder,
    readOnly: props.disabled,

    MENU_CONF: {
        // 图片菜单配置
        uploadImage: {
            customBrowseAndUpload(insert: InsertFnType) {
                fileType.value = 'image'
                insertFn = insert
                attachmentManageRef.value?.openDialog()
            }
        },
        // 视频菜单配置
        uploadVideo: {
            customBrowseAndUpload(insert: InsertFnType) {
                fileType.value = 'video'
                insertFn = insert
                attachmentManageRef.value?.openDialog()
            }
        }
    }
}


const toolbarConfig: Partial<IToolbarConfig> = {

}


// 处理附件选择
const handleAttachmentSelect = (data: Record<string, any>) => {
    if (!insertFn) return

    data.forEach((item: Record<string, any>) => {
        insertFn(formatFileUrl(item.path), item.name, formatFileUrl(item.path))
    })
}

// 编辑器创建完成时的回调
const handleCreated = (editor) => {
    editorRef.value = editor
}

// 编辑器内容变化时的回调
const handleChange = (editor) => {
    // 触发事件，将内容传递给父组件
    emit('update:modelValue', valueHtml.value);
}

// 编辑器聚焦时的回调
const handleFocus = (editor) => {
    // console.log('编辑器聚焦', editor)
}

// 编辑器失焦时的回调
const handleBlur = (editor) => {
    // console.log('编辑器失焦', editor)
}


// 监听 props.modelValue 的变化
watch(() => props.modelValue, (newValue) => {
    valueHtml.value = newValue; // 更新 valueHtml
});


// 组件销毁时，销毁编辑器
onBeforeUnmount(() => {
    const editor = editorRef.value
    if (editor == null) return
    editor.destroy()
})

// 暴露方法
defineExpose({
    getEditor: () => editorRef.value,
    getHtml: () => editorRef.value?.getHtml() || '',
    getText: () => editorRef.value?.getText() || '',
    setHtml: (html) => {
        if (editorRef.value) {
            editorRef.value.setHtml(html)
        }
    },
    clear: () => {
        if (editorRef.value) {
            editorRef.value.setHtml('')
        }
    },
    focus: () => {
        if (editorRef.value) {
            editorRef.value.focus()
        }
    },
    blur: () => {
        if (editorRef.value) {
            editorRef.value.blur()
        }
    }
})
</script>

<style>
::v-deep .w-e-text-container {
    min-height: 300px !important;
    /* 设置最小高度 */
}
</style>

<style lang="scss">
// 暗黑模式适配
html.dark .w-e-toolbar {
    border-color: #4c4d4f !important;
    background-color: #1d1e1f !important;
}

html.dark .w-e-text-container {
    background-color: #1d1e1f !important;
}

html.dark .w-e-text-placeholder {
    color: #6c6e72 !important;
}

html.dark .w-e-panel-container {
    background-color: #1d1e1f !important;
    border-color: #4c4d4f !important;
}

html.dark .w-e-panel-container .w-e-panel-tab-title {
    color: #e5eaf3 !important;
}

html.dark .w-e-panel-container .w-e-panel-tab-content input {
    background-color: #141414 !important;
    border-color: #4c4d4f !important;
    color: #e5eaf3 !important;
}

html.dark .w-e-panel-container .w-e-panel-tab-content button {
    background-color: #409eff !important;
    color: #ffffff !important;
}
</style>
