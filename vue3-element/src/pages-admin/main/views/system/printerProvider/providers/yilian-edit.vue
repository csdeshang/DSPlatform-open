<template>
    <el-dialog v-model="dialogVisible" :title="`配置易联云打印机`" width="600px" :destroy-on-close="true">
        <el-form :model="formData" ref="formRef" :rules="formRules" label-width="120px">
            <el-alert type="info" :closable="false" class="mb-4">
                <p>易联云打印机配置</p>
                <p class="mt-1">官网: <a href="https://www.yilianyun.net/" target="_blank">https://www.yilianyun.net/</a></p>
            </el-alert>

            <el-form-item label="应用ID" prop="config.client_id">
                <el-input v-model="formData.config.client_id" placeholder="请输入易联云应用ID" clearable />
            </el-form-item>

            <el-form-item label="应用密钥" prop="config.client_secret">
                <el-input v-model="formData.config.client_secret" placeholder="请输入易联云应用密钥" clearable />
            </el-form-item>

            <el-form-item label="访问令牌" prop="config.access_token">
                <el-input v-model="formData.config.access_token" placeholder="易联云访问令牌(自动获取)" readonly />
                <div class="mt-1 text-gray-500">
                    <p>访问令牌会自动获取</p>
                </div>
            </el-form-item>

            <el-form-item label="刷新令牌" prop="config.refresh_token">
                <el-input v-model="formData.config.refresh_token" placeholder="易联云刷新令牌(自动获取)" readonly />
                <div class="mt-1 text-gray-500">
                    <p>刷新令牌会自动获取</p>
                </div>
            </el-form-item>

            <el-form-item label="令牌过期时间" prop="config.token_expire_time">
                <el-input v-model="formData.config.token_expire_time" placeholder="易联云令牌过期时间(自动获取)" readonly />
                <div class="mt-1 text-gray-500">
                    <p>令牌过期时间会自动获取</p>
                </div>
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
    provider: 'yilian',
    config: {
        client_id: '',
        client_secret: '',
        access_token: '',
        refresh_token: '',
        token_expire_time: '',
    },
    is_default: false
});

// 表单验证规则
const formRules = {
    'config.client_id': [
        { required: true, message: '请输入应用ID', trigger: 'blur' }
    ],
    'config.client_secret': [
        { required: true, message: '请输入应用密钥', trigger: 'blur' }
    ]
};

// 设置弹窗数据
const setDialogData = (data: any) => {
    formData.provider = data.provider;
    formData.is_default = data.is_default || false;
    
    if (data.config) {
        formData.config.client_id = data.config.client_id || '';
        formData.config.client_secret = data.config.client_secret || '';
        formData.config.access_token = data.config.access_token || '';
        formData.config.refresh_token = data.config.refresh_token || '';
        formData.config.token_expire_time = data.config.token_expire_time || '';
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