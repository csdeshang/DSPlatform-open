<template>
    <el-dialog v-model="dialogVisible" title="调整师傅余额" width="500px" :before-close="closeDialog">
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="100px">
            <el-form-item label="师傅信息">
                <div class="text-gray-600">
                    <div>姓名：{{ technicianInfo.name }}</div>
                    <div>手机：{{ technicianInfo.mobile }}</div>
                    <div>当前余额：<span class="text-green-600 font-bold">¥{{ technicianInfo.balance }}</span></div>
                </div>
            </el-form-item>
            
            <el-form-item label="调整类型" prop="change_type">
                <el-radio-group v-model="formData.change_type" class="flex gap-4">
                    <el-radio :value="1">增加</el-radio>
                    <el-radio :value="2">减少</el-radio>
                </el-radio-group>
            </el-form-item>
            
            <el-form-item label="调整金额" prop="change_amount">
                <el-input-number v-model="formData.change_amount" :min="0" :precision="2" />
            </el-form-item>
            
            <el-form-item label="调整说明" prop="change_desc">
                <el-input 
                    v-model="formData.change_desc" 
                    type="textarea" 
                    :rows="3"
                    placeholder="请输入调整说明"
                />
            </el-form-item>
            
            <el-form-item label="调整后余额">
                <el-text>{{ adjustedBalance }}</el-text>
            </el-form-item>
        </el-form>
        
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeDialog">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">确定</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, computed } from 'vue'
import { ElMessage, type FormInstance, type FormRules } from 'element-plus'
import { modifyTechnicianBalance } from '@/pages-admin/main/api/technician/technicianBalance'

const dialogVisible = ref(false)
const loading = ref(false)
const formRef = ref<FormInstance>()

const technicianInfo = reactive({
    id: 0,
    name: '',
    mobile: '',
    balance: 0
})

const formData = reactive({
    change_type: 1, // 1增加 2减少
    change_amount: 0,
    change_desc: ''
})

const rules: FormRules = {
    change_type: [
        { required: true, message: '请选择调整类型', trigger: 'change' }
    ],
    change_amount: [
        { required: true, message: '请输入调整金额', trigger: 'blur' },
        {
            validator: (rule: any, value: number) => {
                if (value <= 0) {
                    return Promise.reject('调整金额必须大于0')
                }
                if (formData.change_type === 2 && value > parseFloat(technicianInfo.balance)) {
                    return Promise.reject('调整金额不能超过当前余额')
                }
                return Promise.resolve()
            },
            trigger: 'blur'
        }
    ],
    change_desc: [
        { required: true, message: '请输入调整说明', trigger: 'blur' },
        { min: 2, max: 200, message: '调整说明长度在2到200个字符', trigger: 'blur' }
    ]
}

const emit = defineEmits<{
    complete: []
}>()

const adjustedBalance = computed<string>(() => {
    if (!technicianInfo.balance) return '0.00'
    const current = parseFloat(technicianInfo.balance)
    const amount = parseFloat(formData.change_amount || 0)
    const result = formData.change_type === 1 ? current + amount : current - amount
    return result.toFixed(2)
})

const setDialogData = (data: any) => {
    Object.assign(technicianInfo, {
        id: data.id,
        name: data.name,
        mobile: data.mobile,
        balance: data.balance
    })
    
    // 重置表单
    Object.assign(formData, {
        change_type: 1,
        change_amount: 0,
        change_desc: ''
    })
}

const openDialog = () => {
    dialogVisible.value = true
}

const closeDialog = () => {
    dialogVisible.value = false
    emit('complete')
}

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()

    loading.value = true
    modifyTechnicianBalance({
        technician_id: technicianInfo.id,
        change_mode: formData.change_type,
        change_amount: formData.change_amount,
        change_desc: formData.change_desc
    }).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

defineExpose({
    setDialogData,
    openDialog
})
</script> 