<template>
  <el-dialog v-model="dialogVisible" title="消息通知测试" width="600px" :destroy-on-close="true">
    <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" v-loading="loading">
      
      <el-form-item label="选择用户" prop="user_id">
        <div class="flex items-center gap-3">
          <el-button @click="handleSelectUser">选择用户</el-button>
          <span v-if="selectedUserInfo.id" class="text-sm text-gray-600">
            已选择用户：{{ selectedUserInfo.username }} (ID: {{ selectedUserInfo.id }})
          </span>
        </div>
      </el-form-item>

      <el-form-item label="消息模板" prop="template_key">
        <el-input v-model="formData.template_key" disabled placeholder="自动填充" />
      </el-form-item>

      <el-form-item label="模板参数" prop="template_params">
        <el-input 
          v-model="formData.template_params" 
          type="textarea" 
          :rows="4" 
          placeholder='请输入JSON格式的模板参数，例如：{"code":"123456","order_no":"DS202412345","amount":"99.00"}'
        />
        <div class="text-xs text-gray-500 mt-1">
          参数格式为JSON，例如：{"code":"123456"}
        </div>
      </el-form-item>



    </el-form>
    
    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleSubmit" :loading="loading">发送测试</el-button>
      </span>
    </template>
  </el-dialog>

  <!-- 用户选择弹窗 -->
  <UserSelectDialog ref="selectUserDialog" @confirm="selectedUser"/>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { ElMessage } from 'element-plus'
import type { FormInstance, FormRules } from 'element-plus'
import UserSelectDialog from '@/pages-admin/components/user/select-dialog.vue'
import { testNoticeTemplate } from '@/pages-admin/main/api/system/sysNotice'

// 弹窗控制
const dialogVisible = ref(false)
const loading = ref(false)
const formRef = ref<FormInstance>()

// 模板信息和选中的用户信息
const templateInfo = ref<any>({})
const selectedUserInfo = ref<any>({})

// 表单数据
const initialFormData = {
  user_id: '',
  template_key: '',
  template_params: ''
}

const formData: Record<string, any> = reactive({ ...initialFormData })

// 表单验证规则
const formRules = reactive<FormRules>({
  user_id: [
    { required: true, message: '请选择测试用户', trigger: 'change' }
  ],
  template_key: [
    { required: true, message: '模板标识不能为空', trigger: 'blur' }
  ],
  template_params: [
    { validator: validateTemplateParams, trigger: 'blur' }
  ]
})

// 验证模板参数格式
function validateTemplateParams(rule: any, value: string, callback: any) {
  if (value && value.trim()) {
    try {
      JSON.parse(value)
      callback()
    } catch (e) {
      callback(new Error('模板参数必须是有效的JSON格式'))
    }
  } else {
    callback()
  }
}

// 设置弹窗数据
const setDialogData = (row: any) => {
  templateInfo.value = row
  formData.template_key = row.key
  // 重置其他表单数据
  formData.user_id = ''
  formData.template_params = ''
  selectedUserInfo.value = {}
}

// 打开弹窗
const openDialog = () => {
  dialogVisible.value = true
}

// 选择用户相关
const selectUserDialog = ref()
const handleSelectUser = () => {
  selectUserDialog.value?.openDialog()
}

const selectedUser = (user: any) => {
  if (!user || !user.id) {
    ElMessage.error('请选择有效的用户')
    return
  }
  formData.user_id = user.id
  selectedUserInfo.value = user
  ElMessage.success('用户选择成功')
}

// 提交测试
const emit = defineEmits(['complete'])
const handleSubmit = async () => {
  if (loading.value || !formRef.value) return
  
  try {
    await formRef.value.validate()
    
    loading.value = true
    
    const params = {
      user_id: formData.user_id,
      template_key: formData.template_key,
      template_params: formData.template_params ? JSON.parse(formData.template_params) : {}
    }
    
    const res = await testNoticeTemplate(params)
    
    if (res.code === 10000) {
      ElMessage.success('测试消息发送成功，请注意查收')
      dialogVisible.value = false
      emit('complete')
    } else {
      ElMessage.error(res.message || '测试消息发送失败')
    }
  } catch (error: any) {
    ElMessage.error(error.message || '发送失败')
  } finally {
    loading.value = false
  }
}

// 暴露给父组件的方法
defineExpose({
  openDialog,
  setDialogData
})
</script>

<style scoped>
.dialog-footer {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}
</style>
