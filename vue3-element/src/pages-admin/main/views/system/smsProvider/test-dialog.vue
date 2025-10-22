<template>
    <el-dialog v-model="dialogVisible" :title="`测试短信发送 - ${provider?.name || ''}`" width="500px"
        :destroy-on-close="true">
        <el-form :model="formData" ref="formRef" :rules="formRules" label-width="120px">
            <el-alert type="info" :closable="false" class="mb-4">
                <p>测试将使用 <strong>{{ provider?.name }}</strong> 服务商发送短信</p>
                <p v-if="provider?.sign_name" class="mt-1">短信签名: {{ provider.sign_name }}</p>
            </el-alert>

            <el-form-item label="接收手机号" prop="mobile">
                <el-input v-model="formData.mobile" placeholder="请输入接收测试短信的手机号" maxlength="11" clearable />
            </el-form-item>
            <el-form-item label="模板CODE" prop="sms_template_id">
                <el-input v-model="formData.sms_template_id" placeholder="请输入模板CODE" clearable />
                <div class="mt-1 text-gray-500">
                    <p>模板CODE示例：SMS_132545056</p>
                </div>
            </el-form-item>
            <el-form-item label="短信模板参数" prop="sms_template_params">
                <el-input v-model="formData.sms_template_params" placeholder="请输入短信模板参数" clearable />
                <div class="mt-1 text-gray-500">
                    <p>短信模板参数示例：{"code":"123456"}</p>
                </div>
            </el-form-item>

        </el-form>



        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false" :disabled="loading">取消</el-button>
                <el-button type="primary" @click="sendTestSms" :loading="loading">
                    发送测试短信
                </el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';
import { testSmsSend } from '@/pages-admin/main/api/system/sysSmsProvider';

const dialogVisible = ref(false);
const loading = ref(false);
const formRef = ref<FormInstance>();
const provider = ref<any>(null);

// 表单数据
const formData = reactive({
    mobile: '',
    provider: '',
    sms_template_id: '',
    sms_template_params: ''
});

// 表单验证规则
const formRules = {
    mobile: [
        { required: true, message: '请输入手机号码', trigger: 'blur' },
        { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码格式', trigger: 'blur' }
    ]
};



// 设置服务商信息
const setDialogData = (providerData: any) => {
    provider.value = providerData;
    formData.provider = providerData.provider;
};

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true;
    // 重置表单
    formData.mobile = '';
    if (formRef.value) {
        formRef.value.resetFields();
    }
};

// 发送测试短信
const sendTestSms = async () => {
    if (!formRef.value) return;

    try {
        // 表单验证
        await formRef.value.validate();

        // 发送测试短信
        loading.value = true;

        const res = await testSmsSend({
            mobile: formData.mobile,
            provider: formData.provider,
            sms_template_id: formData.sms_template_id,
            sms_template_params: formData.sms_template_params
        });

        if (res.code === 10000) {
            ElMessage.success('测试短信发送成功，请注意查收');
            dialogVisible.value = false;
        } else {
            ElMessage.error(res.message || '测试短信发送失败');
        }
    } catch (error: any) {
        console.error('测试短信发送失败:', error);
        if (error.message) {
            ElMessage.error(error.message);
        } else {
            ElMessage.error('表单验证失败或发送过程中出现错误');
        }
    } finally {
        loading.value = false;
    }
};

// 暴露方法给父组件
defineExpose({
    openDialog,
    setDialogData
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