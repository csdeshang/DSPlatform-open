<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules"
            v-loading="loading">
            <el-form-item label="用户名" prop="username">
                <el-input v-if="formData.id == 0" v-model.trim="formData.username" placeholder="请输入管理员"
                    clearable class="input-width" maxlength="10" show-word-limit />
                <el-input v-else v-model.trim="formData.username" placeholder="请输入管理员" clearable :disabled="true"
                    class="input-width" maxlength="10" show-word-limit />
            </el-form-item>
            <el-form-item label="密码" prop="password">
                <el-input v-model.trim="formData.password" placeholder="请输入您的密码" type="password"
                    :show-password="true" clearable class="input-width" />
            </el-form-item>
            <el-form-item label="确认密码" prop="confirm_password">
                <el-input v-model.trim="formData.confirm_password" placeholder="请再次输入您的密码" type="password"
                    :show-password="true" clearable class="input-width" />
            </el-form-item>
            <el-form-item label="权限组" prop="role_id">
                <el-select v-model="formData.role_id" placeholder="请选择权限组" clearable class="input-width">
                    <el-option v-for="role in admin_role_list" :key="role.id" :label="role.name"
                        :value="role.id" />
                </el-select>
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
import { createAdmin, updateAdmin, getAdminInfo } from '@/pages-admin/main/api/admin/admin'
import { getAdminRoleList } from '@/pages-admin/main/api/admin/adminRole';

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

/**

* 表单数据
*/
const initialFormData = {
    id: '',
    username: '',
    password: '',
    confirm_password: '',
    role_id: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

const validatePassword = (rule: any, value: string, callback: any) => {
  if (value !== formData.password) {
    callback(new Error('两次输入密码不一致'))
  } else {
    callback()
  }
}
// 表单验证规则
const formRules = computed(() => {
    return {
        username: [
            { required: formData.id === '', message: '请输入您的用户名', trigger: 'blur' }
        ],
        role_id: [
            { required: true, message: '请选择您的管理组', trigger: 'blur' }
        ],
        password: [
            { required: formData.id === '', message: '请输入您的密码', trigger: 'blur' }
        ],
        confirm_password: [
            { required: formData.id === '', message: '确认您的密码', trigger: 'blur' },
            {
                validator:validatePassword,
                trigger: 'blur'
            }
        ]
    }
})


const emit = defineEmits(['complete'])

// 角色
const admin_role_list = ref<Record<string, any>>([])
getAdminRoleList({}).then(res => {
    admin_role_list.value = res.data

})


const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateAdmin : createAdmin
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
    // 重置表单数据
    Object.assign(formData, initialFormData)
    popTitle = '添加管理员'

    if (row) {
        popTitle = '更新管理员'
        const data = await (await getAdminInfo(row.id)).data
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