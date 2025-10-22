<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" v-loading="loading">
            <!-- 申请基本信息（只读） -->

            <el-form-item label="申请金额">
                <el-input v-model="formData.apply_amount" disabled>
                    <template #append>元</template>
                </el-input>
            </el-form-item>

            <!-- 处理信息（可编辑） -->
            <el-divider content-position="center">处理信息</el-divider>

            <el-form-item label="处理状态" prop="status">
                <el-radio-group v-model="formData.status">
                    <el-radio :label="1">通过</el-radio>
                    <el-radio :label="2">拒绝</el-radio>
                </el-radio-group>
            </el-form-item>

            <template v-if="formData.status === 1">
                <el-form-item label="转账类型" prop="transfer_type">
                    <el-select v-model="formData.transfer_type" placeholder="请选择转账类型">
                        <el-option v-for="item in transfer_type_options" :key="item.value" :label="item.label" :value="item.value"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="转账备注" prop="transfer_remark">
                    <el-input v-model="formData.transfer_remark" placeholder="请输入转账备注信息" />
                </el-form-item>
            </template>

            <el-form-item label="处理备注" prop="operation_remark" :rules="[
                { required: formData.status === 2, message: '拒绝时请填写处理备注', trigger: 'blur' }
            ]">
                <el-input v-model="formData.operation_remark" type="textarea" :rows="3" placeholder="请输入处理备注" />
            </el-form-item>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">确定</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import type { FormInstance, FormRules } from 'element-plus'
import { ElMessage } from 'element-plus'
import { operationUserWithdrawalLog, getUserWithdrawalLogInfo } from '@/pages-admin/main/api/user/userWithdrawal' // 假设有这个API方法

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = '处理提现申请'

import { useEnum } from '@/hooks/useEnum'
const { options: transfer_type_options } = useEnum('default.user_withdrawal_log.transfer_type')


// 初始化表单数据，只包含需要的字段
const initialFormData = {
    id: '',
    apply_amount: 0,   // 只用于显示
    transfer_type: 'manual',
    transfer_remark: '',
    operation_remark: '',
    status: 1, // 默认通过
}

const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = reactive<FormRules>({

})

// 定义完成处理的事件
const emit = defineEmits(['complete'])

// 提交处理结果
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return

    try {
        // 表单验证
        await formRef.value.validate()

        // 构建提交数据
        const submitData = {
            id: formData.id,
            status: formData.status,
            transfer_type: formData.transfer_type,
            transfer_remark: formData.transfer_remark,
            operation_remark: formData.operation_remark,
        }

        // 上传逻辑
        loading.value = true

        // 调用API处理提现申请
        const res = await operationUserWithdrawalLog(submitData)
        if (res.code == 10000) {
            ElMessage.success('提现申请处理成功')
            dialogVisible.value = false
            emit('complete')
        } else {
            ElMessage.error(res.message)
        }


    } catch (error) {
        console.error('处理失败:', error)
        ElMessage.error('提现申请处理失败')
    } finally {
        loading.value = false
    }
}

// 设置对话框数据
const setDialogData = async (row: any = null) => {
    if (!row) return

    loading.value = true
    try {
        // 重置表单
        Object.assign(formData, initialFormData)

        const data = await (await getUserWithdrawalLogInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
        // 默认通过
        formData.status = 1
        // 默认手工转账
        formData.transfer_type = 'manual'


    } catch (error) {
        console.error('加载数据失败:', error)
        ElMessage.error('加载数据失败')
    } finally {
        loading.value = false
    }
}

// 暴露方法给父组件
defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>

<style scoped>
.el-divider {
    margin: 16px 0;
}
</style>