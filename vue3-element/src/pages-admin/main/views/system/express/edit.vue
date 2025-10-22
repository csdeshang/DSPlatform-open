<template>
    <div>
        <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

            <el-form :model="formData" label-width="130" ref="formRef" :rules="formRules" v-loading="loading">

                <el-form-item label="物流公司名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入物流公司名称"></el-input>
                </el-form-item>

                <el-form-item label="物流公司代码" prop="code">
                    <el-input v-model="formData.code" placeholder="请输入物流公司代码"></el-input>
                </el-form-item>

                <el-form-item label="快递100公司编码" prop="kd100_code">
                    <el-input v-model="formData.kd100_code" placeholder="请输入快递100公司编码"></el-input>
                    <span>快递100编号：<a href="https://api.kuaidi100.com/document/5ff2c3e7ba1bf00302f5612e"
                            target="_blank">帮助文档</a></span>
                </el-form-item>

                <el-form-item label="快递鸟公司编码" prop="kdniao_code">
                    <el-input v-model="formData.kdniao_code" placeholder="请输入快递鸟公司编码"></el-input>
                    <span>快递鸟编号：<a href="https://www.yuque.com/kdnjishuzhichi/dfcrg1/mza2ln"
                            target="_blank">帮助文档</a></span>
                </el-form-item>


                <el-form-item label="物流公司logo" prop="logo">
                    <PickerImage v-model="formData.logo" :limit=1 type="image"></PickerImage>
                </el-form-item>

                <el-form-item label="物流公司网址" prop="url">
                    <el-input v-model="formData.url" placeholder="请输入物流公司网址"></el-input>
                </el-form-item>

                <el-form-item label="是否显示" prop="is_show">
                    <el-switch v-model="formData.is_show" active-text="显示" inactive-text="隐藏" :active-value="1"
                        :inactive-value="0" />
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <el-input-number v-model="formData.sort" :min="0" placeholder="请输入排序值"></el-input-number>
                </el-form-item>


            </el-form>

            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogVisible = false">取消</el-button>
                    <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
                </span>
            </template>
        </el-dialog>


    </div>
</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';

import { createSysExpress, updateSysExpress, getSysExpressInfo } from '@/pages-admin/main/api/system/sysExpress'

import PickerImage from '@/components/attachment/picker-image.vue'


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''


/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    name: '',
    code: '',
    kd100_code: '',
    kdniao_code: '',
    logo: '',
    url: '',
    sort: 0,
    is_show: 1,
}

const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入物流公司名称', trigger: 'blur' }
        ],

        sort: [
            { required: true, message: '请输入排序值', trigger: 'blur' }
        ]

    }
})



const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateSysExpress : createSysExpress
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
    popTitle = '添加物流公司'

    if (row) {
        popTitle = '修改物流公司'
        const data = await (await getSysExpressInfo(row.id)).data
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