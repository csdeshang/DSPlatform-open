<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules"
            v-loading="loading">

            <el-form-item label="等级名称" prop="level_name">
                <el-input v-model="formData.level_name" placeholder="请输入等级名称"></el-input>
            </el-form-item>
            <el-form-item label="最小成长值" prop="min_growth">
                <el-input v-model="formData.min_growth" placeholder="请输入最小成长值"></el-input>
            </el-form-item>
            <el-form-item label="等级描述" prop="description">
                <el-input v-model="formData.description"  :rows="3" type="textarea"
                    placeholder="请输入等级描述" />
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
import { createUserGrowthLevel, updateUserGrowthLevel, getUserGrowthLevelInfo } from '@/pages-admin/main/api/user/userGrowthLevel'


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

/**
* 表单数据
*/
const initialFormData = {
    id: '',
    level_name: '',
    min_growth: 0,
    description: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        level_name: [
            { required: true, message: '请输入等级名称', trigger: 'blur' }
        ],
        min_growth: [
            { required: true, message: '请输入最小成长值', trigger: 'blur' }
        ],
    }
})


const emit = defineEmits(['complete'])



const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateUserGrowthLevel : createUserGrowthLevel
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
    popTitle = '添加等级'

    if (row) {
        popTitle = '更新等级'
        const data = await (await getUserGrowthLevelInfo(row.id)).data
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