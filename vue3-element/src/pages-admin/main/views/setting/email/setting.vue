<template>

    <el-card shadow="never" class="mb-5">
        <el-form-item label="是否启用邮件">
            <el-switch v-model="email_is_enabled" size="large" @change="updateEmailIsEnabled" active-value="1"
                inactive-value="0" />
        </el-form-item>
    </el-card>
    <el-card shadow="never">

        <el-form :model="formData" :rules="formRules" label-width="200px" ref="ruleFormRef" v-loading="loading">

            <el-form-item label="SMTP服务器地址" prop="smtp_host">
                <el-input v-model="formData.smtp_host" placeholder="例如: smtp.gmail.com" class="w-[400px]" />
            </el-form-item>

            <el-form-item label="SMTP服务器端口" prop="smtp_port">
                <el-input v-model="formData.smtp_port" placeholder="例如: 465(SSL)或25(非SSL)" class="w-[400px]" />
            </el-form-item>

            <el-form-item label="SMTP用户名" prop="smtp_user">
                <el-input v-model="formData.smtp_user" placeholder="通常是邮箱地址" class="w-[400px]" />
            </el-form-item>

            <el-form-item label="SMTP密码" prop="smtp_pass">
                <el-input v-model="formData.smtp_pass" type="password" placeholder="邮箱密码或授权码" show-password
                    class="w-[400px]" />
            </el-form-item>

            <el-form-item label="发件人邮箱" prop="smtp_from_email">
                <el-input v-model="formData.smtp_from_email" placeholder="发件人邮箱地址" class="w-[400px]" />
            </el-form-item>

            <el-divider content-position="left">测试配置</el-divider>

            <el-form-item label="测试接收邮箱" prop="test_to_email">
                <el-input v-model="formData.test_to_email" placeholder="用于接收测试邮件的邮箱地址" class="w-[400px]">
                    <template #append>
                        <el-button @click="handleTestEmail" :loading="testLoading">发送测试邮件</el-button>
                    </template>
                </el-input>
            </el-form-item>
        </el-form>

        <div class="flex justify-center mt-10 mb-10">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存配置</el-button>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editEmailConfig, testEmailSend } from '@/pages-admin/main/api/system/sysConfig'

const loading = ref(true)
const testLoading = ref(false)
const ruleFormRef = ref<FormInstance>()
const formData = reactive({
    smtp_host: '',
    smtp_port: '',
    smtp_user: '',
    smtp_pass: '',
    smtp_from_email: '',
    // 默认SSL
    smtp_ssl: 'ssl',
    test_to_email: '',
})

const formRules = computed(() => {
    return {
        smtp_host: [
            { required: true, message: '请输入SMTP服务器地址', trigger: 'blur' }
        ],
        smtp_port: [
            { required: true, message: '请输入SMTP服务器端口', trigger: 'blur' },
            { pattern: /^[0-9]+$/, message: '端口必须为数字', trigger: 'blur' }
        ],
        smtp_user: [
            { required: true, message: '请输入SMTP用户名', trigger: 'blur' }
        ],
        smtp_pass: [
            { required: true, message: '请输入SMTP密码', trigger: 'blur' }
        ],
        smtp_from_email: [
            { required: true, message: '请输入发件人邮箱', trigger: 'blur' },
            { type: 'email', message: '请输入正确的邮箱格式', trigger: 'blur' }
        ],
        test_to_email: [
            { type: 'email', message: '请输入正确的邮箱格式', trigger: 'blur' }
        ]
    }
})

// 获取配置数据
const setFormData = async () => {
    getSysConfigList({
        type: 'email'
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

// 保存配置
const handleSubmit = async () => {
    try {
        loading.value = true
        await ruleFormRef.value?.validate()
        const result = await editEmailConfig(formData)
        if (result.code === 10000) {
            ElMessage.success('保存成功')
        }
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

// 发送测试邮件
const handleTestEmail = async () => {
    if (!formData.test_to_email) {
        ElMessage.warning('请先输入测试接收邮箱')
        return
    }

    // 简单邮箱格式验证
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if (!emailRegex.test(formData.test_to_email)) {
        ElMessage.warning('请输入正确的邮箱格式')
        return
    }

    try {
        testLoading.value = true
        const res = await testEmailSend({
            smtp_host: formData.smtp_host,
            smtp_port: formData.smtp_port,
            smtp_user: formData.smtp_user,
            smtp_pass: formData.smtp_pass,
            smtp_from_email: formData.smtp_from_email,
            smtp_ssl: formData.smtp_ssl,
            test_to_email: formData.test_to_email,
        })

        if (res.code === 10000) {
            ElMessage.success('测试邮件发送成功，请查收')
        } else {
            ElMessage.error(res.message || '测试邮件发送失败')
        }
    } catch (error) {
        console.error('测试邮件发送失败:', error)
        ElMessage.error('测试邮件发送失败')
    } finally {
        testLoading.value = false
    }
}




// 是否启用邮件
const email_is_enabled = ref('0');
// 获取邮件是否启用
const fetchEmailIsEnabled = async () => {
    try {
        let key = 'email_is_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            email_is_enabled.value = response.data.config_value;
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchEmailIsEnabled();
// 更新邮件是否启用
const updateEmailIsEnabled = async () => {
    try {
        let key = 'email_is_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'email',
            config_key: key,
            config_value: email_is_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新邮件配置成功');
        } else {
            ElMessage.error('更新邮件配置失败');
        }
    } catch (error) {
        console.error('更新邮件配置时出错:', error);
    }
}

</script>