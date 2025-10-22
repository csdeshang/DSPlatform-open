<template>
    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="formData" :rules="formRules" label-width="150px" ref="ruleFormRef" v-loading="loading">
            <el-form-item label="开启登录获取积分" prop="points_login_enabled">
                <el-switch v-model="formData.points_login_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="登录获取" prop="points_login_amount">
                <el-input v-model="formData.points_login_amount" placeholder="请输入登录获取" class="w-[200px]" />
            </el-form-item>
            <el-form-item label="开启注册获取积分" prop="points_register_enabled">
                <el-switch v-model="formData.points_register_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="注册获取积分" prop="points_register_amount">
                <el-input v-model="formData.points_register_amount" placeholder="请输入注册获取积分" class="w-[200px]" />
            </el-form-item>
            <el-form-item label="开启支付获取积分" prop="points_pay_enabled">
                <el-switch v-model="formData.points_pay_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="支付获取积分" prop="points_pay_amount">
                <el-input v-model="formData.points_pay_amount" placeholder="请输入支付获取积分" class="w-[200px]" />
            </el-form-item>
            <el-form-item label="是否按照比例获取" prop="points_payrate_enabled">
                <el-switch v-model="formData.points_payrate_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="金额比例获取" prop="points_payrate_amount">
                <el-input v-model="formData.points_payrate_amount" placeholder="请输入金额比例获取" class="w-[200px]" />
            </el-form-item>
            <el-form-item label="开启评价获取积分" prop="points_review_enabled">
                <el-switch v-model="formData.points_review_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="评价获取" prop="points_review_amount">
                <el-input v-model="formData.points_review_amount" placeholder="请输入评价获取" class="w-[200px]" />
            </el-form-item>
            <el-form-item label="开启邀请注册" prop="points_invite_enabled">
                <el-switch v-model="formData.points_invite_enabled" active-value="1" inactive-value="0" />
            </el-form-item>
            <el-form-item label="邀请注册" prop="points_invite_amount">
                <el-input v-model="formData.points_invite_amount" placeholder="请输入邀请注册" class="w-[200px]" />
            </el-form-item>

        </el-form>

        <div class="flex justify-center">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editPointsRules } from '@/pages-admin/main/api/system/sysConfig'

const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    points_login_amount: '',
    points_login_enabled: false,
    points_register_amount: '',
    points_register_enabled: false,
    points_pay_amount: '',
    points_pay_enabled: false,
    points_payrate_amount: '',
    points_payrate_enabled: false,
    points_review_amount: '',
    points_review_enabled: false,
    points_invite_amount: '',
    points_invite_enabled: false,
})

const formRules = computed(() => {
    return {
        points_login_amount: [{ required: true, message: '请输入登录获取', trigger: 'blur' }],
        points_login_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        points_register_amount: [{ required: true, message: '请输入注册获取积分', trigger: 'blur' }],
        points_register_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        points_pay_amount: [{ required: true, message: '请输入支付获取积分', trigger: 'blur' }],
        points_pay_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        points_payrate_amount: [{ required: true, message: '请输入金额比例获取', trigger: 'blur' }],
        points_payrate_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        points_review_amount: [{ required: true, message: '请输入评价获取', trigger: 'blur' }],
        points_review_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
        points_invite_amount: [{ required: true, message: '请输入邀请注册', trigger: 'blur' }],
        points_invite_enabled: [{ required: true, message: '请选择是否开启', trigger: 'change' }],
    }
})

const setFormData = async () => {
    getSysConfigList({
        type: 'points'
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
        const result = await editPointsRules(formData)
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