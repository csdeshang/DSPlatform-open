<template>
    <el-card shadow="never">

        <el-form :model="formData" :rules="formRules" label-width="150px" ref="ruleFormRef" v-loading="loading">




            <el-tabs v-model="activeName">
                <el-tab-pane label="基础配置" name="base">

                    <el-form-item label="PC站点名称" prop="pc_site_name">
                        <el-input v-model="formData.pc_site_name" placeholder="请输入PC站点名称" class="w-[400px]" />
                    </el-form-item>
                    <el-form-item label="PC站点Logo" prop="pc_site_logo">
                        <PickerImage v-model="formData.pc_site_logo" :limit=1 type="image"></PickerImage>
                    </el-form-item>


                    <el-form-item label="H5站点名称" prop="h5_site_name">
                        <el-input v-model="formData.h5_site_name" placeholder="请输入H5站点名称" class="w-[400px]" />
                    </el-form-item>
                    <el-form-item label="H5站点Logo" prop="h5_site_logo">
                        <PickerImage v-model="formData.h5_site_logo" :limit=1 type="image"></PickerImage>
                    </el-form-item>
                    <el-form-item label="后台名称" prop="admin_site_name">
                        <el-input v-model="formData.admin_site_name" placeholder="请输入后台名称" class="w-[400px]" />
                    </el-form-item>
                    <el-form-item label="后台Logo" prop="admin_site_logo">
                        <PickerImage v-model="formData.admin_site_logo" :limit=1 type="image"></PickerImage>
                    </el-form-item>


                    <el-form-item label="联系电话" prop="website_phone">
                        <el-input v-model="formData.website_phone" placeholder="请输入联系电话" class="w-[400px]" />
                    </el-form-item>
                    <el-form-item label="客服二维码" prop="website_qrcode">
                        <PickerImage v-model="formData.website_qrcode" :limit=1 type="image"></PickerImage>
                    </el-form-item>
                    <el-form-item label="工作时间" prop="website_work_hours">
                        <el-input v-model="formData.website_work_hours" placeholder="请输入工作时间" class="w-[400px]" />
                    </el-form-item>


                    <el-form-item label="地址" prop="website_address">
                        <el-input v-model="formData.website_address" placeholder="请输入地址" class="w-[600px]" />
                    </el-form-item>

                    <el-form-item label="邮箱" prop="website_email">
                        <el-input v-model="formData.website_email" placeholder="请输入邮箱" class="w-[400px]" />
                    </el-form-item>

                    <el-form-item label="ICP备案号" prop="icp_code">
                        <el-input v-model="formData.icp_code" placeholder="请输入ICP备案号" class="w-[400px]" />
                    </el-form-item>
                    <el-form-item label="ICP备案链接" prop="icp_url">
                        <el-input v-model="formData.icp_url" placeholder="请输入ICP备案链接" class="w-[400px]" />
                        <div class="text-gray-500 w-full">填写ICP备案官网链接，如：https://beian.miit.gov.cn</div>
                    </el-form-item>

                </el-tab-pane>
                <el-tab-pane label="URL配置" name="url">

                    <el-form-item label="API接口地址" prop="api_url">
                        <el-input v-model="formData.api_url" placeholder="请输入API接口地址" class="w-[500px]" />
                    </el-form-item>

                    <el-form-item label="PC端地址" prop="pc_url">
                        <el-input v-model="formData.pc_url" placeholder="请输入PC端地址" class="w-[500px]" />
                    </el-form-item>

                    <el-form-item label="PC店铺管理地址" prop="pc_store_url">
                        <el-input v-model="formData.pc_store_url" placeholder="请输入PC店铺管理地址" class="w-[500px]" />
                    </el-form-item>

                    <el-form-item label="PC商户管理地址" prop="pc_merchant_url">
                        <el-input v-model="formData.pc_merchant_url" placeholder="请输入PC商户管理地址" class="w-[500px]" />
                    </el-form-item>

                    <el-form-item label="H5端地址" prop="h5_url">
                        <el-input v-model="formData.h5_url" placeholder="请输入H5端地址" class="w-[500px]" />
                    </el-form-item>
                    <!--
                    <el-form-item label="H5店铺管理地址" prop="h5_store_url">
                        <el-input v-model="formData.h5_store_url" placeholder="请输入H5店铺管理地址" />
                    </el-form-item>

                    <el-form-item label="H5商户管理地址" prop="h5_merchant_url">
                        <el-input v-model="formData.h5_merchant_url" placeholder="请输入H5商户管理地址" />
                    </el-form-item>
                    -->


                </el-tab-pane>
            </el-tabs>










        </el-form>

        <!-- 居中 -->
        <div class="flex justify-center">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
        </div>

    </el-card>
</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editWebsite } from '@/pages-admin/main/api/system/sysConfig'

import PickerImage from '@/components/attachment/picker-image.vue'

const activeName = ref('base')


const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    api_url: '',
    pc_url: '',
    pc_store_url: '',
    pc_merchant_url: '',
    h5_url: '',
    h5_store_url: '',
    h5_merchant_url: '',

    website_phone: '',
    website_address: '',
    website_email: '',
    website_work_hours: '',
    website_qrcode: '',

    admin_site_name: '',
    admin_site_logo: '',
    h5_site_logo: '',
    h5_site_name: '',
    pc_site_logo: '',
    pc_site_name: '',
    icp_code: '',
    icp_url: '',
})

const formRules = computed(() => {
    return {

    }
})


const setFormData = async () => {
    getSysConfigList({
        type: 'website'
    }).then(res => {
        loading.value = false
        Object.keys(formData).forEach((key: string) => {
            if (res.data[key] != undefined) formData[key] = res.data[key]
        })
    }).catch(() => {
        loading.value = false
    })
}
setFormData()

const handleSubmit = async () => {
    try {
        loading.value = true
        await ruleFormRef.value?.validate()
        const result = await editWebsite(formData)
        if (result.code === 10000) {
            ElMessage.success('保存成功')
        }
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

</script>