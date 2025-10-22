<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="70%" :destroy-on-close="true">
        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">


            <el-form-item label="标题" prop="title">
                <el-input v-model="formData.title" placeholder="请输入标题"></el-input>
            </el-form-item>

            <el-form-item label="内容" prop="content">
                <RichTextEditor v-model="formData.content" />
            </el-form-item>

            <el-form-item label="排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="1" placeholder="请输入排序值"></el-input-number>
            </el-form-item>

            <el-form-item label="是否显示" prop="is_show">
                <el-switch v-model="formData.is_show" :active-value="1" :inactive-value="0" />
            </el-form-item>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue';
import type { FormInstance } from 'element-plus';
import { createSysAgreement, updateSysAgreement, getSysAgreementInfo } from '@/pages-admin/main/api/system/sysAgreement';

import RichTextEditor from '@/components/editor/index.vue'

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle: string = '';

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    title: '',
    content: '',
    sort: 1,
    is_show: 1,
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 表单验证规则
const formRules = computed(() => {
    return {
        code: [
            { required: true, message: '请输入协议类型', trigger: 'blur' }
        ],
        title: [
            { required: true, message: '请输入标题', trigger: 'blur' }
        ],
        content: [
            { required: true, message: '请输入内容', trigger: 'blur' }
        ],
        sort: [
            { required: true, message: '请输入排序值', trigger: 'blur' }
        ]
    };
});


const emit = defineEmits(['complete'])
// 提交表单
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return;

    await formRef.value.validate();
    const requestFun = formData.id ? updateSysAgreement : createSysAgreement;
    loading.value = true;

    requestFun(formData).then(res => {
        loading.value = false;
        dialogVisible.value = false;
        emit('complete')
    }).catch(() => {
        loading.value = false;
    });
};

// 设置对话框数据
const setDialogData = async (row: any = null) => {
    loading.value = true;
    Object.assign(formData, initialFormData);
    popTitle = '添加协议';

    if (row.id) {
        popTitle = '更新协议'
        const data = await (await getSysAgreementInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    }

    loading.value = false;
};

// 暴露方法给父组件
defineExpose({
    openDialog: () => {
        dialogVisible.value = true;
    },
    setDialogData
});
</script>

<style scoped>
.dialog-footer {
    margin-bottom: 20px;
    display: flex;
    justify-content: center;
}


</style>


