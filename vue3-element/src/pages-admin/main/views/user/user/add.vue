<template>

  <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
    <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">

      <el-form-item label="用户名" prop="username">
        <el-input v-model="formData.username" />
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="formData.password" type="password" />
      </el-form-item>
      <el-form-item label="确认密码" prop="confirm_password">
        <el-input v-model="formData.confirm_password" type="password" />
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
import { createUser } from '@/pages-admin/main/api/user/user'


const dialogVisible = ref(false)

const loading = ref(false)

let popTitle: string = ''


const initialFormData = {
  username: '',
  password: '',
  confirm_password: ''
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

const formRules = reactive<FormRules>({
  username: [
    { required: true, message: '请输入用户名', trigger: 'blur' },
    { min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'blur' }
  ],
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' },
    { min: 6, max: 20, message: '长度在 6 到 20 个字符', trigger: 'blur' }
  ],
  confirm_password: [
    { required: true, message: '请确认密码', trigger: 'blur' },
    { validator: validatePassword, trigger: 'blur' }
  ]
})

const emit = defineEmits(['complete'])
const handleSubmit = async () => {
  if (loading.value || !formRef.value) return
  await formRef.value.validate()
  loading.value = true
  createUser(formData).then(res => {
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
  popTitle = '添加会员'
  loading.value = false
}




defineExpose({
  openDialog: () => {
    dialogVisible.value = true
  },
  setDialogData
})
</script>

<style scoped></style>