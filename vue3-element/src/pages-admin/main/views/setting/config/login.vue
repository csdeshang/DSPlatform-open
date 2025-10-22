<template>
    <el-card shadow="never">
        <el-form :model="formData" :rules="formRules" label-width="150px" ref="ruleFormRef" v-loading="loading">



            <el-form-item label="开启普通登录" prop="user_login_normal">
                <el-switch v-model="formData.user_login_normal" :active-value="'1'" :inactive-value="'0'" />
                <div class="form-item-tip">用户可以使用账号密码进行登录和注册</div>
            </el-form-item>

            <el-form-item label="开启手机验证码登录" prop="user_login_mobile">
                <el-switch v-model="formData.user_login_mobile" :active-value="'1'" :inactive-value="'0'" />
                <div class="form-item-tip">用户可以使用手机验证码进行登录和注册</div>
            </el-form-item>

            <el-form-item label="开启微信登录" prop="user_login_wechat">
                <el-switch v-model="formData.user_login_wechat" :active-value="'1'" :inactive-value="'0'" />
                <div class="form-item-tip">
                    1.用户可以微信进行登录,新用户会自动注册<br/>
                    2.需要先通过 <a href="https://open.weixin.qq.com/" target="_blank">微信开放平台</a>关联绑定,通过Unionid关联，实现微信各端用户唯一识别
                </div>
            </el-form-item>



            <el-form-item label="用户登录显示LOGO" prop="user_login_logo">
                <PickerImage v-model="formData.user_login_logo" :limit="1" type="image"></PickerImage>
                <div class="form-item-tip">登录页面显示的LOGO图片</div>
            </el-form-item>



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
import { getSysConfigList, editLoginConfig } from '@/pages-admin/main/api/system/sysConfig'
import PickerImage from '@/components/attachment/picker-image.vue'

const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    user_login_normal: '1',
    user_login_mobile: '0',
    user_login_wechat: '0',
    user_login_logo: '',

})

const formRules = computed(() => {
    return {
        // 规则可根据需要添加
    }
})

const setFormData = async () => {
    getSysConfigList({
        type: 'user_login'
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
        const result = await editLoginConfig(formData)
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

<style scoped>
.form-item-tip {
    font-size: 12px;
    color: #909399;
    line-height: 1.5;
    margin-top: 5px;
    width: 100%;
}
</style>
