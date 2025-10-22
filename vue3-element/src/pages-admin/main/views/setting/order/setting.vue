<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="formData" :rules="formRules" label-width="150px" ref="ruleFormRef" v-loading="loading">
            <el-form-item label="开启自动取消订单" prop="auto_cancel_order_enabled">
                <el-switch v-model="formData.auto_cancel_order_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="自动取消订单时间" prop="auto_cancel_order_hours">
                <el-input v-model="formData.auto_cancel_order_hours" placeholder="请输入自动取消订单时间" class="w-[200px]" />
                <div class="w-full text-[#999]">
                    自动取消订单时间，单位：小时
                </div>
            </el-form-item>
            <el-form-item label="开启自动确认订单" prop="auto_confirm_order_enabled">
                <el-switch v-model="formData.auto_confirm_order_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="自动确认订单时间" prop="auto_confirm_order_hours">
                <el-input v-model="formData.auto_confirm_order_hours" placeholder="请输入自动确认订单时间" class="w-[200px]" />
                <div class="w-full text-[#999]">
                    自动确认订单时间，单位：小时
                </div>
            </el-form-item>

            <el-form-item label="开启确认收货退款" prop="refund_order_enabled">
                <el-switch v-model="formData.refund_order_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="确认收货退款时间" prop="refund_order_days">
                <el-input v-model="formData.refund_order_days" placeholder="请输入确认收货退款时间" class="w-[200px]" />
                <div class="w-full text-[#999]">
                    确认收货退款时间，单位：天, 在确认收货后，在指定天数内可以退款
                </div>
            </el-form-item>


        </el-form>
        <!-- 居中-->
        <div class="flex justify-center">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
        </div>
    </el-card>


</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editOrderAutoConfig } from '@/pages-admin/main/api/system/sysConfig'



const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    auto_cancel_order_enabled: '0',
    auto_cancel_order_hours: '',
    auto_confirm_order_enabled: '0',
    auto_confirm_order_hours: '',
    refund_order_enabled: '0',
    refund_order_days: '',
})

const formRules = computed(() => {
    return {
        auto_cancel_order_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        auto_cancel_order_hours: [{ required: true, message: '请输入自动取消订单时间', trigger: 'blur' }],
        auto_confirm_order_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        auto_confirm_order_hours: [{ required: true, message: '请输入自动确认订单时间', trigger: 'blur' }],
        refund_order_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        refund_order_days: [{ required: true, message: '请输入自动确认订单时间', trigger: 'blur' }],
    }
})

const setFormData = async () => {
    getSysConfigList({
        type: 'order_auto'
    }).then(res => {
        loading.value = false
        Object.keys(formData).forEach((key: string) => {
            if (res.data[key] != undefined) formData[key] = res.data[key]
        })
    }).catch(() => {
        loading.value = false
    })
}
setFormData()

const handleSubmit = async () => {
    try {
        loading.value = true
        await ruleFormRef.value?.validate()
        const result = await editOrderAutoConfig(formData)
        if (result.code === 10000) {
            ElMessage.success('保存成功')
        }
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}
</script>