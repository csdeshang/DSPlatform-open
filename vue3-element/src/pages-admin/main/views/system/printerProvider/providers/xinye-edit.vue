<template>
    <el-dialog v-model="dialogVisible" :title="`配置芯烨打印机`" width="600px" :destroy-on-close="true">
        <el-form :model="formData" ref="formRef" :rules="formRules" label-width="120px">
            <el-alert type="info" :closable="false" class="mb-4">
                <p>芯烨打印机配置</p>
                <p class="mt-1">官网: <a href="https://www.xprinter.com.cn/" target="_blank">https://www.xprinter.com.cn/</a></p>
            </el-alert>

            <el-form-item label="API密钥" prop="config.api_key">
                <el-input v-model="formData.config.api_key" placeholder="请输入芯烨API密钥" clearable />
            </el-form-item>

            <el-form-item label="API密钥" prop="config.api_secret">
                <el-input v-model="formData.config.api_secret" placeholder="请输入芯烨API密钥" clearable />
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
    provider: 'xinye',
    config: {
        api_key: '',
        api_secret: ''
    },
    is_default: false
});

// 表单验证规则
const formRules = {
    'config.api_key': [
        { required: true, message: '请输入API密钥', trigger: 'blur' }
    ],
    'config.api_secret': [
        { required: true, message: '请输入API密钥', trigger: 'blur' }
    ]
};

// 设置弹窗数据
const setDialogData = (data: any) => {
    formData.provider = data.provider;
    formData.is_default = data.is_default || false;
    
    if (data.config) {
        formData.config.api_key = data.config.api_key || '';
        formData.config.api_secret = data.config.api_secret || '';
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