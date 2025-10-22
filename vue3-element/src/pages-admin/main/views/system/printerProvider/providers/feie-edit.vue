<template>
    <el-dialog v-model="dialogVisible" :title="`配置飞鹅打印机`" width="600px" :destroy-on-close="true">
        <el-form :model="formData" ref="formRef" :rules="formRules" label-width="120px">
            <el-alert type="info" :closable="false" class="mb-4">
                <p>飞鹅打印机配置</p>
                <p class="mt-1">官网: <a href="https://www.feieyun.com/" target="_blank">https://www.feieyun.com/</a></p>
            </el-alert>

            <el-form-item label="用户名" prop="config.user">
                <el-input v-model="formData.config.user" placeholder="请输入飞鹅云后台注册用户名" clearable />
            </el-form-item>

            <el-form-item label="UKEY" prop="config.ukey">
                <el-input v-model="formData.config.ukey" placeholder="请输入飞鹅云后台登录生成的UKEY" clearable />
            </el-form-item>

            <el-form-item label="设为默认">
                <el-switch v-model="formData.is_default" />
            </el-form-item>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false" :disabled="loading">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">
                    保存配置
                </el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';
import { updatePrinterProviderConfig } from '@/pages-admin/main/api/system/sysPrinterProvider';

const dialogVisible = ref(false);
const loading = ref(false);
const formRef = ref<FormInstance>();

// 表单数据
const formData = reactive({
    provider: 'feie',
    config: {
        user: '',
        ukey: ''
    },
    is_default: false
});

// 表单验证规则
const formRules = {
    'config.user': [
        { required: true, message: '请输入用户名', trigger: 'blur' }
    ],
    'config.ukey': [
        { required: true, message: '请输入UKEY', trigger: 'blur' }
    ]
};

// 设置弹窗数据
const setDialogData = (data: any) => {
    formData.provider = data.provider;
    formData.is_default = data.is_default || false;
    
    if (data.config) {
        formData.config.user = data.config.user || '';
        formData.config.ukey = data.config.ukey || '';
    }
};

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true;
};

// 提交表单
const handleSubmit = async () => {
    if (!formRef.value) return;

    try {
        await formRef.value.validate();
        loading.value = true;

        const res = await updatePrinterProviderConfig(formData);
        
        if (res.code === 10000) {
            ElMessage.success('配置保存成功');
            dialogVisible.value = false;
            emit('complete');
        }
    } catch (error) {
        console.error('配置保存失败:', error);
        ElMessage.error('配置保存失败');
    } finally {
        loading.value = false;
    }
};

// 定义事件
const emit = defineEmits(['complete']);

// 暴露方法
defineExpose({
    setDialogData,
    openDialog
});
</script>

<style scoped>
.dialog-footer {
    display: flex;
    justify-content: flex-end;
}

.el-alert {
    margin-bottom: 16px;
}
</style> 