<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="700px" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="商品名称">
                <div>{{ formData.goods_name }}</div>
            </el-form-item>

            <el-form-item label="商品状态" prop="sys_status">
                <el-radio-group v-model="formData.sys_status">
                    <el-radio v-for="option in sys_status_options" :key="option.value" :value="option.value">{{
                        option.label
                        }}</el-radio>
                </el-radio-group>
            </el-form-item>

            <el-form-item label="状态说明" prop="sys_status_reason" v-if="formData.sys_status === 2 || formData.sys_status === 3">
                <el-input v-model="formData.sys_status_reason" type="textarea" :rows="4" placeholder="请输入状态变更原因"
                    maxlength="255" show-word-limit />
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
import { computed, reactive, ref } from 'vue';
import type { FormInstance } from 'element-plus';
import { ElMessage } from 'element-plus';
import { updateTblGoodsSysStatus } from '@/pages-admin/main/api/tbl-goods/tblGoods';

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle = '修改商品状态';

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    goods_name: '',
    sys_status: 1, // 默认正常状态: 0待审核 1正常 2审核失败 3违规下架
    sys_status_reason: '', // 状态理由
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 表单验证规则
const formRules = computed(() => {
    return {
        sys_status: [
            { required: true, message: '请选择商品状态', trigger: 'change' }
        ],
    };
});

const emit = defineEmits(['complete']);

/**
 * 提交表单
 */
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return;

    try {
        // 表单验证
        await formRef.value.validate();

        // 提交数据
        loading.value = true;
        const submitData = {
            id: formData.id,
            sys_status: formData.sys_status,
            sys_status_reason: formData.sys_status_reason
        };

        await updateTblGoodsSysStatus(submitData);


        ElMessage.success(`商品状态已更新为${sys_status_options.value.find(option => option.value === formData.sys_status)?.label}`);

        // 关闭对话框并通知父组件
        dialogVisible.value = false;
        emit('complete');
    } catch (error) {
        console.error('更新商品状态失败:', error);
        ElMessage.error('更新商品状态失败');
    } finally {
        loading.value = false;
    }
};

import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: sys_status_options, } = useEnum('default.tbl_goods.sys_status')


/**
 * 设置对话框数据
 */
const setDialogData = (row: any = null) => {
    if (!row) return;

    loading.value = true;

    try {
        // 重置表单数据
        Object.assign(formData, initialFormData);

        // 设置商品信息
        formData.id = row.id || '';
        formData.goods_name = row.goods_name || '';

        // 设置当前系统状态
        if (row.sys_status !== undefined) {
            formData.sys_status = row.sys_status;
        }

        // 设置状态理由 (如果有)
        formData.sys_status_reason = row.sys_status_reason || '';
    } catch (error) {
        console.error('设置数据失败:', error);
        ElMessage.error('加载商品数据失败');
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

.w-full {
    width: 100%;
}
</style>