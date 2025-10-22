<template>

  <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
    <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">

      <el-form-item label="标题" prop="title">
        <el-input v-model="formData.title" />
      </el-form-item>

      <el-form-item label="平台" prop="platform">
        <el-select v-model="formData.platform" placeholder="请选择平台">
          <el-option v-for="item in platformList" :key="item.id" :label="item.name" :value="item.platform" />
        </el-select>
      </el-form-item>

      <el-form-item label="页面类型" prop="type">
        <el-radio-group v-model="formData.type">
          <el-radio v-for="option in type_options" :key="option.value" :value="option.value">{{
            option.label }}</el-radio>
        </el-radio-group>
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
import { createEditablePage } from '@/pages-admin/main/api/editable/editable'
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'


import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: type_options, } = useEnum('default.editable_page.type')



// 平台列表
const platformList = ref([])
// 获取平台列表 store 类型
const fetchSysPlatformList = async () => {
  const res = await getSysPlatformList({})
  platformList.value = res.data
}


const dialogVisible = ref(false)

const loading = ref(false)

let popTitle: string = ''


const initialFormData = {

  title: '',
  platform: '',
  type: 'home',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()




const formRules = reactive<FormRules>({
  title: [
    { required: true, message: '请输入标题', trigger: 'blur' },
    { min: 3, max: 20, message: '长度在 2 到 20 个字符', trigger: 'blur' }
  ],


})

const emit = defineEmits(['complete'])
const handleSubmit = async () => {
  if (loading.value || !formRef.value) return
  await formRef.value.validate()
  loading.value = true
  createEditablePage(formData).then(res => {
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
  popTitle = '添加页面'
  await fetchSysPlatformList()
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