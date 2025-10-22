<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading"
            class="px-10">
            <el-form-item label="会员名" prop="username">
                <el-text>{{ userInfo.username }}</el-text>
            </el-form-item>

            <el-form-item label="当前余额">
                <el-text>{{ userInfo.balance }}</el-text>
            </el-form-item>
            <el-form-item label="调整类型" prop="change_mode">
                <el-radio-group v-model="formData.change_mode" class="flex gap-4">
                    <el-radio :value="1">增加</el-radio>
                    <el-radio :value="2">减少</el-radio>
                </el-radio-group>
            </el-form-item>

            <el-form-item label="调整金额" prop="change_amount">
                <el-input-number v-model="formData.change_amount" :min="0" :precision="2" />
            </el-form-item>

            <el-form-item label="调整后余额">
                <el-text>{{ adjustedBalance }}</el-text>
            </el-form-item>

            <el-form-item label="备注" prop="remark">
                <el-input v-model="formData.remark" type="textarea" :rows="3" />
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
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { modifyUserBalance } from '@/pages-admin/main/api/user/userBalance'
import { getUserInfo } from '@/pages-admin/main/api/user/user'

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const userInfo = reactive({})

/**
* 表单数据
*/
const initialFormData = {
    user_id: '',
    change_mode: 1,
    change_amount: 0,
}

const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

const adjustedBalance = computed<string>(() => {
    const current = parseFloat(userInfo.balance)
    const amount = parseFloat(formData.change_amount || 0)
    const result = formData.change_mode === 1 ? current + amount : current - amount
    return result.toFixed(2)
})


// 表单验证规则
const formRules = computed(() => {
    return {
        change_mode: [
            { required: true, message: '请选择调整类型', trigger: 'change' }
        ],
        // change_amount: [
        //     { required: true, message: '请输入调整金额', trigger: 'blur' },
        //     {
        //         validator: (rule: any, value: number) => {
        //             if (value <= 0) {
        //                 return Promise.reject('调整金额必须大于0')
        //             }
        //             if (formData.change_mode === 2 && value > parseFloat(userInfo.balance)) {
        //                 return Promise.reject('调整金额不能超过当前余额')
        //             }
        //             return Promise.resolve()
        //         },
        //         trigger: 'blur'
        //     }
        // ]
    }
})


const emit = defineEmits(['complete'])


const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()

    loading.value = true
    modifyUserBalance(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}


const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '调整余额'

    if (row) {
        const data = await (await getUserInfo(row.id)).data
        Object.assign(userInfo, data)
        formData.user_id = data.id
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