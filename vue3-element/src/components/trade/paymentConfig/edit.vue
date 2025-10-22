<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">

        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="开启状态" prop="is_enabled">
                <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
            </el-form-item>

            <payment-config-alipay v-if="formData.payment_channel === 'ali_pay'" v-model="formData.config_data" />
            <payment-config-wechat v-if="formData.payment_channel === 'wechat_pay'" v-model="formData.config_data" />

            <el-form-item label="排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="0" :max="100" placeholder="请输入排序"></el-input-number>
            </el-form-item>



        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
            </span>
        </template>
    </el-drawer>
</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref} from 'vue';
import { createPaymentConfig, updatePaymentConfig, getPaymentConfigInfo } from './paymentConfig'

import paymentConfigAlipay from './configAlipay.vue'
import paymentConfigWechat from './configWechat.vue'



const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''


const formData: Record<string, any> = reactive({
    id: '',
    merchant_id: '',
    payment_channel: '',
    payment_scene: '',
    config_data: {},
    is_enabled: 0,
    sort: 255,
})



const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {

    }
})




const emit = defineEmits(['complete'])



const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updatePaymentConfig : createPaymentConfig
    loading.value = true
    requestFun(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, row)
    popTitle = row.payment_name + ' - ' + row.payment_scene

    if (row.id) {
        const data = await (await getPaymentConfigInfo(row.id)).data
        Object.assign(formData, data)
    }

    loading.value = false
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})




</script>