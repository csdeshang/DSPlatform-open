<template>
    <div class="popover-input-container" @mouseenter="inPopover = true" @mouseleave="inPopover = false">
        <el-popover
            placement="top"
            v-model:visible="visible"
            :width="width"
            trigger="contextmenu"
            class="popover-input"
            :teleported="teleported"
            :persistent="false"
            popper-class="!p-0"
        >
            <div class="flex p-3" @click.stop="">
                <div class="popover-input__input mr-[10px] flex-1">
                    <el-select
                        v-if="type === 'select'"
                        v-model="inputValue"
                        class="flex-1"
                        :size="size"
                        :teleported="teleported"
                        :placeholder="placeholder"
                    >
                        <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                        />
                    </el-select>
                    <el-input
                        v-else
                        v-model.trim="inputValue"
                        :maxlength="limit"
                        :show-word-limit="showLimit"
                        :type="type"
                        :size="size"
                        clearable
                        :placeholder="placeholder"
                        @keyup.enter="handleConfirm"
                    />
                </div>
                <div class="popover-input__btns flex-none">
                    <el-button link @click="close">取消</el-button>
                    <el-button type="primary" :size="size" @click="handleConfirm">确定</el-button>
                </div>
            </div>
            <template #reference>
                <div class="inline cursor-pointer" @click.stop="handleOpen">
                    <slot></slot>
                </div>
            </template>
        </el-popover>
    </div>
</template>

<script lang="ts" setup>
import { useEventListener } from '@vueuse/core'
import { ref, watch, type PropType, onMounted } from 'vue'

const props = defineProps({
    value: {
        type: [String, Number],
        default: ''
    },
    type: {
        type: String,
        default: 'text'
    },
    width: {
        type: [Number, String],
        default: '300px'
    },
    placeholder: {
        type: String,
        default: '请输入'
    },
    disabled: {
        type: Boolean,
        default: false
    },
    options: {
        type: Array as PropType<Array<{ label: string, value: string | number }>>,
        default: () => []
    },
    size: {
        type: String as PropType<'default' | 'small' | 'large'>,
        default: 'default'
    },
    limit: {
        type: Number,
        default: 200
    },
    showLimit: {
        type: Boolean,
        default: false
    },
    teleported: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['confirm', 'update:visible'])

const visible = ref(false)
const inPopover = ref(false)
const inputValue = ref<string | number>(props.value || '')

const handleConfirm = () => {
    if (inputValue.value === '' && props.type !== 'select') {
        return
    }
    
    close()
    emit('confirm', inputValue.value)
}

const handleOpen = () => {
    if (props.disabled) return
    
    inputValue.value = props.value || ''
    visible.value = true
    emit('update:visible', true)
}

const close = () => {
    visible.value = false
    emit('update:visible', false)
}

watch(() => props.value, (newValue) => {
    inputValue.value = newValue || ''
}, { immediate: true })

useEventListener(document.documentElement, 'click', () => {
    if (inPopover.value) return
    close()
})

onMounted(() => {
    inputValue.value = props.value || ''
})
</script>

<style scoped lang="scss">
.popover-input-container {
    display: inline-block;
}

.inline {
    display: inline-block;
}
</style>
