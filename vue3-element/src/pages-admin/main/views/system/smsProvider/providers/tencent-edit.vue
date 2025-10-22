<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">
        <el-form :model="formData" label-width="180px" ref="formRef" :rules="formRules" v-loading="loading">
            <!-- 基本信息 (只读) -->
            <el-divider content-position="left">基本信息</el-divider>

            <el-form-item label="服务商名称">
                <el-input v-model="formData.name" disabled />
            </el-form-item>

            <el-form-item label="服务商标识">
                <el-input v-model="formData.provider" disabled />
            </el-form-item>

            <!-- 可编辑配置 -->
            <el-divider content-position="left">服务商配置</el-divider>


            <el-form-item label="设为默认服务商" prop="is_default">
                <el-switch v-model="formData.is_default" :active-value="1" :inactive-value="0" />
            </el-form-item>

            <el-form-item label="短信签名" prop="config.sign_name">
                <el-input v-model="formData.config.sign_name" placeholder="请输入短信签名" />
            </el-form-item>

            <el-form-item label="AccessKey ID" prop="config.access_key_id">
                <el-input v-model="formData.config.access_key_id" placeholder="请输入 AccessKey ID" />
            </el-form-item>

            <el-form-item label="AccessKey Secret" prop="config.access_key_secret">
                <el-input v-model="formData.config.access_key_secret" placeholder="请输入 AccessKey Secret" type="password"
                    show-password />
            </el-form-item>

            <template>
                <el-alert type="info" :closable="false" show-icon>
                    <p>使用提示：</p>
                    <p>1. 确保您已在服务商平台完成实名认证和签名审核</p>
                    <p>2. AccessKey 信息请妥善保管，避免泄露</p>
                    <p>3. 启用此服务商后请进行测试，确保配置正确</p>
                </el-alert>
            </template>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import type { FormInstance } from 'element-plus';
import { ElMessage } from 'element-plus';
import { updateSmsProviderConfig, getSmsProviderInfo } from '@/pages-admin/main/api/system/sysSmsProvider'

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle = ref('编辑短信服务商');

/**
 * 表单数据
 */
const initialFormData = {
    name: '',
    provider: '',
    config: {
        sign_name: '',
        access_key_id: '',
        access_key_secret: '',
    },
    is_default: 0
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 表单验证规则
const formRules = computed(() => {
    return {
        config: {
            sign_name: [
                { required: true, message: '请输入短信签名', trigger: 'blur' },
                { max: 20, message: '签名长度不能超过20个字符', trigger: 'blur' }
            ],
            access_key_id: [
                { required: true, message: '请输入 AccessKey ID', trigger: 'blur' }
            ],
            access_key_secret: [
                { required: true, message: '请输入 AccessKey Secret', trigger: 'blur' }
            ]
        }
    };
});

const emit = defineEmits(['complete']);

/**
 * 提交表单 - 只提交可编辑字段
 */
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return;

    try {
        // 表单验证
        await formRef.value.validate();

        // 提交数据
        loading.value = true;

        // 只提交需要更新的字段
        const updateData = {
            provider: formData.provider,
            config: formData.config,
            is_default: formData.is_default
        };

        const res = await updateSmsProviderConfig(updateData);

        ElMessage.success('更新短信服务商配置成功');
        dialogVisible.value = false;
        emit('complete');
    } catch (error) {
        console.error('提交失败:', error);
        ElMessage.error('操作失败，请检查表单数据');
    } finally {
        loading.value = false;
    }
};

/**
 * 设置对话框数据
 */
const setDialogData = async (row: any = null) => {
    if (!row || !row.provider) {
        ElMessage.warning('请选择要编辑的短信服务商');
        return;
    }

    loading.value = true;
    // 重置表单数据
    Object.assign(formData, initialFormData);

    try {
        popTitle.value = `编辑短信服务商 - ${row.name}`;

        // 获取服务商详细信息
        const { data } = await getSmsProviderInfo({ provider: row.provider });

        // 更新表单数据
        if (data) {
            // 使用类型断言解决索引签名问题
            const dataObj = data as Record<string, any>;
            Object.keys(formData).forEach((key) => {
                if (dataObj[key] !== undefined) {
                    (formData as Record<string, any>)[key] = dataObj[key];
                }
            });
        }
    } catch (error) {
        console.error('加载数据失败:', error);
        ElMessage.error('加载数据失败');
    } finally {
        loading.value = false;
    }
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
    display: flex;
    justify-content: flex-end;
}

.el-divider {
    margin: 16px 0;
}

.el-alert {
    margin-top: 16px;
    margin-bottom: 16px;
}
</style>
