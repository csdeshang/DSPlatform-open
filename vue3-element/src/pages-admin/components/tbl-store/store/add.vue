<template>


    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="选择商户">
                <el-button @click="handleSelectMerchant">选择商户</el-button>
            </el-form-item>
            <el-form-item label="已选商户" prop="merchant_id">
                <el-input v-model="merchantInfo.name" disabled />
            </el-form-item>
            <el-form-item label="店铺类型" prop="platform">
                <el-select v-model="formData.platform" placeholder="请选择店铺类型">
                    <el-option v-for="item in platformList" :key="item.id" :label="item.name" :value="item.platform" />
                </el-select>
            </el-form-item>
            <el-form-item label="店铺名称" prop="store_name">
                <el-input v-model="formData.store_name" />
            </el-form-item>
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleSubmit">确定</el-button>
            </span>
        </template>
    </el-dialog>


    <merchant-select ref="selectMerchantDialog" @confirm="selectedMerchant" />


</template>


<script setup lang="ts">

import { ref, reactive } from 'vue'
import { ElMessage } from 'element-plus'
import type { FormInstance, FormRules } from 'element-plus'

import MerchantSelect from '@/pages-admin/components/merchant/select.vue'
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'

import { createMallStore } from '@/pages-admin/platform/mall/api/store/store'
import { createFoodStore } from '@/pages-admin/platform/food/api/store/store'
import { createKmsStore } from '@/pages-admin/platform/kms/api/store/store'
import { createHouseStore } from '@/pages-admin/platform/house/api/store/store'



const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
    merchant_id: {
        type: String,
        default: ''
    }
})

// 选择的商户信息
const merchantInfo = ref({
    name: '',
    id: ''
})

const platformList = ref([])

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''



const formData: Record<string, any> = reactive({
    merchant_id: '0',
    store_name: '',
    platform: props.platform
})
const formRef = ref<FormInstance>()
const formRules = reactive<FormRules>({

})

// 获取平台列表 store 类型
const fetchSysPlatformList = async () => {
    const res = await getSysPlatformList({ scene: 'store' })
    platformList.value = res.data
}


const emit = defineEmits(['complete'])
// 根据选择平台类型，选择不同的创建方式
const handleSubmit = async () => {
    if (formData.platform === 'mall') {
        await handleCreateMallStore()
    } else if (formData.platform === 'food') {
        await handleCreateFoodStore()
    } else if (formData.platform === 'kms') {
        await handleCreateKmsStore()
    } else if (formData.platform === 'house') {
        await handleCreateHouseStore()
    } else {
        ElMessage.error('您选择的平台未开发创建店铺功能')
    }
}


// 创建商城店铺
const handleCreateMallStore = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    createMallStore(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

// 创建外卖店铺
const handleCreateFoodStore = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    createFoodStore(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

// 创建视频店铺
const handleCreateKmsStore = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    createKmsStore(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}


// 创建家政店铺
const handleCreateHouseStore = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    createHouseStore(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}











// 选择商户
const selectMerchantDialog = ref()
const handleSelectMerchant = () => {
    selectMerchantDialog.value?.openDialog()
}
const selectedMerchant = (merchant: any) => {
    if (!merchant || !merchant.id) {
        ElMessage.error('请选择有效的商户')
        return
    }
    formData.merchant_id = merchant.id
    merchantInfo.value = merchant

    ElMessage.success('商户选择成功')
}
// 设置对话框数据
const setDialogData = async (row: any = null) => {
    loading.value = true
    if (row) {
        Object.assign(merchantInfo.value, row)
        formData.merchant_id = merchantInfo.value.id
    }
    await fetchSysPlatformList()
    popTitle = '快速添加店铺'
    loading.value = false
}

// 暴露方法
defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})



</script>
