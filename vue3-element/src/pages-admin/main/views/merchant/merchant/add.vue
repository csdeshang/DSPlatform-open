


<template>


    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="选择会员" prop="user_select">
                <el-button @click="handleSelectUser">选择会员</el-button>
            </el-form-item>

            <el-form-item label="会员ID" prop="user_id">
                <el-input v-model="formData.user_id" disabled/>
            </el-form-item>

            <el-form-item label="商户名称" prop="name">
                <el-input v-model="formData.name" />
            </el-form-item>


        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleSubmit">确定</el-button>
            </span>
        </template>
    </el-dialog>


    <UserSelectDialog ref="selectUserDialog" @confirm="selectedUser"/>



</template>


<script setup lang="ts">

import { ref, reactive } from 'vue'
import { ElMessage } from 'element-plus'
import type { FormInstance, FormRules } from 'element-plus'
import { createMerchant } from '@/pages-admin/main/api/merchant/merchant'
import UserSelectDialog from '@/pages-admin/components/user/select-dialog.vue'



const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''


const initialFormData = {
    user_id: '0',
    name: '',
}

const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()


const formRules = reactive<FormRules>({

})

const emit = defineEmits(['complete'])
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    createMerchant(formData).then(res => {
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
    popTitle = '快速添加商户'
    loading.value = false
}


// 暴露给父组件
defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})


// 选择会员
const selectUserDialog = ref()
const handleSelectUser = () => {
    selectUserDialog.value?.openDialog()
}
const selectedUser = (user: any) => {
    if (!user || !user.id) {
        ElMessage.error('请选择有效的会员')
        return
    }
    formData.user_id = user.id
    ElMessage.success('会员选择成功')
}
</script>
