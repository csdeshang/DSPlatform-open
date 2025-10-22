<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules"
            v-loading="loading">

            <el-form-item label="角色名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入管理组名称"></el-input>
            </el-form-item>
            <el-form-item label="角色描述" prop="desc">
                <el-input v-model="formData.desc"  :rows="3" type="textarea"
                    placeholder="请输入管理组描述" />
            </el-form-item>
            <el-form-item label="排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="0" :max="999"
                    placeholder="请输入排序"></el-input-number>
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
import { computed, reactive, ref, toRaw } from 'vue';
import { createAdminRole, updateAdminRole, getAdminRoleInfo } from '@/pages-admin/main/api/admin/adminRole'


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

/**
* 表单数据
*/
const initialFormData = {
    id: '',
    name: '',
    desc: '',
    sort: 255,
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请选择您的管理组', trigger: 'blur' }
        ],
    }
})


const emit = defineEmits(['complete'])



const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateAdminRole : createAdminRole
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
    Object.assign(formData, initialFormData)
    popTitle = '添加管理员'

    if (row) {
        popTitle = '更新管理员'
        const data = await (await getAdminRoleInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
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