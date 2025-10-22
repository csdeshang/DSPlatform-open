<template>
    <el-dialog v-model="dialogVisible" title="更换绑定店铺" width="600px" :before-close="closeDialog">
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="100px">
            <el-form-item label="师傅信息">
                <div class="text-gray-600">
                    <div>姓名：{{ technicianInfo.name }}</div>
                    <div>手机：{{ technicianInfo.mobile }}</div>
                    <div>当前绑定店铺：<span class="text-blue-600 font-bold">{{ technicianInfo.store?.store_name || '未绑定'
                            }}</span></div>
                </div>
            </el-form-item>

            <el-form-item label="选择新店铺" prop="store_id">
                <div class="flex items-center gap-2">
                    <el-input v-model="selectedStoreName" placeholder="点击选择店铺" readonly class="flex-1" />
                    <el-button type="primary" @click="openStoreSelectDialog">选择店铺</el-button>
                </div>
            </el-form-item>
        </el-form>

        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeDialog">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">确定更换</el-button>
            </div>
        </template>

        <!-- 店铺选择弹窗 -->
        <TblStoreSelectDialog ref="storeSelectDialog" @confirm="handleStoreSelected" />
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue'
import { ElMessage, type FormInstance, type FormRules } from 'element-plus'
import { updateTechnicianBindStore } from '@/pages-admin/main/api/technician/technician'
import TblStoreSelectDialog from '@/pages-admin/components/tbl-store/store/select-dialog.vue'

const dialogVisible = ref(false)
const loading = ref(false)
const formRef = ref<FormInstance>()
const storeSelectDialog = ref()

const technicianInfo = reactive({
    id: 0,
    name: '',
    mobile: '',
    store: {
        id: 0,
        store_name: ''
    }
})

const formData = reactive({
    store_id: 0
})

const selectedStoreName = ref('')

const rules: FormRules = {
    store_id: [
        { required: true, message: '请选择要绑定的店铺', trigger: 'change' }
    ]
}

const emit = defineEmits<{
    complete: []
}>()

const setDialogData = (data: any) => {
    Object.assign(technicianInfo, {
        id: data.id,
        name: data.name,
        mobile: data.mobile,
        store: data.store || { id: 0, store_name: '' }
    })

    // 重置表单
    Object.assign(formData, {
        store_id: 0
    })

    selectedStoreName.value = ''
}

const openDialog = () => {
    loading.value = false
    dialogVisible.value = true
}

const closeDialog = () => {
    dialogVisible.value = false
    emit('complete')
}

const openStoreSelectDialog = () => {
    storeSelectDialog.value?.openDialog()
    // 设置店铺选择弹窗的店铺平台为家政
    storeSelectDialog.value?.setDialogData({ platform: 'house' })
}

const handleStoreSelected = (store: any) => {
    formData.store_id = store.id
    selectedStoreName.value = store.store_name
    // 触发表单验证
    formRef.value?.validateField('store_id')
}

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()

    loading.value = true


    const res = await updateTechnicianBindStore({
        id: technicianInfo.id,
        store_id: formData.store_id
    })
    if (res.code === 10000) {
        ElMessage.success('店铺绑定更换成功')
        dialogVisible.value = false
        emit('complete')
    } else {
        ElMessage.error(res.message || '店铺绑定更换失败')
    }

    loading.value = false

    

}

defineExpose({
    setDialogData,
    openDialog
})
</script>