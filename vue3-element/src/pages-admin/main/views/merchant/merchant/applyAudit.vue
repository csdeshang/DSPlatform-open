<template>
  <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
    <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">
      <el-form-item label="审核状态" prop="apply_status">
        <el-radio-group v-model="formData.apply_status" placeholder="请选择审核状态" clearable>
          <el-radio label="审核通过" :value="1" />
          <el-radio label="拒绝" :value="2" />
        </el-radio-group>
      </el-form-item>
      <el-form-item label="审核备注" prop="audit_remark" v-if="formData.apply_status === 2">
        <el-input v-model="formData.audit_remark" type="textarea" :rows="3" placeholder="请输入审核备注" />
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
import { auditMerchant } from '@/pages-admin/main/api/merchant/merchant';

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = '审核商户申请'

/**
 * 表单数据
 */
const initialFormData = {
  id: '',
  apply_status: 1,
  audit_remark: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
  return {
    apply_status: [
      { required: true, message: '请选择审核状态', trigger: 'change' }
    ],
    audit_remark: [
      { 
        required: formData.apply_status === 2, 
        message: '请输入审核备注', 
        trigger: 'blur' 
      }
    ]
  }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
  if (loading.value || !formRef.value) return
  await formRef.value.validate()
  loading.value = true
  
  try {
    const res = await auditMerchant(formData.id, {
      apply_status: formData.apply_status,
      audit_remark: formData.audit_remark
    })
    loading.value = false
    dialogVisible.value = false
    emit('complete')
  } catch (error) {
    loading.value = false
  }
}

const setDialogData = async (row: any = null) => {
  loading.value = true
  // 重置表单数据
  Object.assign(formData, initialFormData)

  if (row) {
    const data = row
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
