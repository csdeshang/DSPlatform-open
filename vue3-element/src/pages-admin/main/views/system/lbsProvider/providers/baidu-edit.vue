<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">


        <el-alert type="info" :closable="false" show-icon>
            <p>使用提示：</p>
            <p>1. 设为默认之后，相关地图请上传至对应的存储空间</p>
            <p>2. 信息请妥善保管，避免泄露</p>
            <p>3. 浏览器（AK）请使用百度地图开放平台申请。<a href="https://lbsyun.baidu.com/" target="_blank">https://lbsyun.baidu.com/</a></p>
        </el-alert>
        <el-form :model="formData" label-width="180px" ref="formRef" :rules="formRules" v-loading="loading">


            <el-form-item label="设为默认" prop="is_default">
                <el-switch v-model="formData.is_default" :active-value="1" :inactive-value="0" />
            </el-form-item>

            <el-form-item label="服务端（AK）" prop="config.service_ak">
                <el-input v-model="formData.config.service_ak" placeholder="请输入服务端（AK）"  type="password" show-password />
            </el-form-item>

            <el-form-item label="浏览器（AK）" prop="config.browser_ak">
                <el-input v-model="formData.config.browser_ak" placeholder="请输入浏览器（AK）" type="password" show-password />
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

<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import type { FormInstance } from 'element-plus';
import { ElMessage } from 'element-plus';
import { updateLbsProviderConfig, getLbsProviderInfo } from '@/pages-admin/main/api/system/sysLbsProvider'

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle = ref('编辑地图服务商');

/**
 * 表单数据
 */
const initialFormData = {
    name: '',
    provider: '',
    config: {
        service_ak: '',
        browser_ak: '',
    },
    is_default: 0
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 表单验证规则
const formRules = computed(() => {
    return {
        config: {

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

        const res = await updateLbsProviderConfig(updateData);

        ElMessage.success('更新地图服务商配置成功');
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
        ElMessage.warning('请选择要编辑的地图服务商');
        return;
    }

    loading.value = true;
    // 重置表单数据
    Object.assign(formData, initialFormData);

    try {
        popTitle.value = `编辑地图服务商 - ${row.name}`;

        // 获取服务商详细信息
        const { data } = await getLbsProviderInfo(row.provider);

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
</style>
