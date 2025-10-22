<template>
    <el-card shadow="never">

        <el-form :model="formData" :rules="formRules" label-width="200px" ref="ruleFormRef" v-loading="loading">
            
            <el-form-item label="提现最低金额" prop="withdrawal_min_amount">
                <el-input v-model="formData.withdrawal_min_amount" placeholder="请输入提现最小金额">
                    <template #append>元</template>
                </el-input>
            </el-form-item>
            <el-form-item label="提现手续费率" prop="withdrawal_fee_rate">
                <el-input v-model="formData.withdrawal_fee_rate" placeholder="请输入提现手续费率">
                    <template #append>%</template>
                </el-input>
            </el-form-item>
        </el-form>

        <div class="flex justify-center mt-10 mb-10">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
        </div>

    </el-card>
</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editUserWithdrawalRules } from '@/pages-admin/main/api/system/sysConfig'



const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    withdrawal_min_amount: 0,
    withdrawal_fee_rate: 0,
})

const formRules = computed(() => {
    return {

    }
})


const setFormData = async () => {
    getSysConfigList({
        type: 'user_withdrawal'
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
        const result = await editUserWithdrawalRules(formData)
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