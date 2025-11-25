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

            <el-form-item label="服务商描述">
                <el-input v-model="formData.description" disabled type="textarea" :rows="2" />
            </el-form-item>

            <!-- 可编辑配置 -->
            <el-divider content-position="left">服务商配置</el-divider>

            <el-form-item label="设为默认服务商" prop="is_default">
                <el-switch v-model="formData.is_default" :active-value="1" :inactive-value="0" />
            </el-form-item>

            <el-form-item label="客户编号" prop="config.customer">
                <el-input v-model="formData.config.customer" placeholder="请输入快递100客户编号" />
            </el-form-item>

            <el-form-item label="授权码" prop="config.key">
                <el-input v-model="formData.config.key" placeholder="请输入快递100授权码" type="password" show-password />
            </el-form-item>

            <el-form-item label="接口类型" prop="config.api_type">
                <el-select v-model="formData.config.api_type" placeholder="请选择接口类型">
                    <el-option label="免费接口" value="free" />
                    <el-option label="收费接口" value="paid" />
                </el-select>
                <div class="mt-1 text-gray-500">
                    <p>免费接口：无需认证，每日限额；收费接口：需要认证，无限制</p>
                </div>
            </el-form-item>



            <template>
                <el-alert type="info" :closable="false" show-icon>
                    <p>使用提示：</p>
                    <p>1. 请先到快递100官网（https://www.kuaidi100.com/）注册账号并申请API权限</p>
                    <p>2. 获取客户编号和授权码后填入上方配置</p>
                    <p>3. 快递100支持800+快递公司，服务稳定可靠</p>
                    <p>4. 配置完成后请进行测试，确保接口可正常使用</p>
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
import { updateExpressProviderConfig, getExpressProviderInfo } from '@/pages-admin/main/api/system/sysExpressProvider'

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle = ref('编辑快递查询服务商');

/**
 * 表单数据
 */
const initialFormData = {
    name: '',
    provider: '',
    description: '',
    config: {
        customer: '',
        key: '',
        api_type: 'free',
    },
    is_default: 0
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 表单验证规则
const formRules = computed(() => {
    return {
        config: {
            customer: [
                { required: true, message: '请输入快递100客户编号', trigger: 'blur' }
            ],
            key: [
                { required: true, message: '请输入快递100授权码', trigger: 'blur' }
            ],
            api_type: [
                { required: true, message: '请选择接口类型', trigger: 'change' }
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

        const res = await updateExpressProviderConfig(updateData);

        ElMessage.success('更新快递查询服务商配置成功');
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
        ElMessage.warning('请选择要编辑的快递查询服务商');
        return;
    }

    loading.value = true;
    // 重置表单数据
    Object.assign(formData, initialFormData);

    try {
        popTitle.value = `编辑快递查询服务商 - ${row.name}`;

        // 获取服务商详细信息
        const { data } = await getExpressProviderInfo(row.provider);

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

.text-gray-500 {
    color: #9ca3af;
}

.mt-1 {
    margin-top: 0.25rem;
}
</style>