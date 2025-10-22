<template> 
    <el-dialog v-model="dialogVisible" title="修改密码" width="500px" :destroy-on-close="true">
        <div class="password-tips">
            <el-alert
                title="温馨提示"
                type="info"
                :closable="false"
                show-icon
            >
                <template #default>
                    <div class="tips-content">
                        <p>• 新密码长度不能少于6位</p>
                        <p>• 修改密码成功后将自动退出登录，请重新登录</p>
                    </div>
                </template>
            </el-alert>
        </div>
        
        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="原密码" prop="old_password">
                <el-input 
                    v-model.trim="formData.old_password" 
                    placeholder="请输入原密码" 
                    type="password"
                    :show-password="true" 
                    clearable 
                    class="input-width" 
                />
            </el-form-item>
            <el-form-item label="新密码" prop="password">
                <el-input 
                    v-model.trim="formData.password" 
                    placeholder="请输入新密码（6-32位）" 
                    type="password"
                    :show-password="true" 
                    clearable 
                    class="input-width" 
                />
            </el-form-item>
            <el-form-item label="确认密码" prop="confirm_password">
                <el-input 
                    v-model.trim="formData.confirm_password" 
                    placeholder="请再次输入新密码" 
                    type="password"
                    :show-password="true" 
                    clearable 
                    class="input-width" 
                />
            </el-form-item>
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认修改</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { reactive, ref } from 'vue';
import { editCurrentUserPassword } from '@/api/menuRoutes';
import { ElMessage, ElMessageBox } from 'element-plus';
// @ts-ignore
import useUserInfoStore from '@/stores/modules/userInfo';

const dialogVisible = ref(false)
const loading = ref(false)
const userInfoStore = useUserInfoStore()

/**
 * 表单数据
 */
const initialFormData = {
    old_password: '',
    password: '',
    confirm_password: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

const validatePassword = (rule: any, value: string, callback: any) => {
    if (value !== formData.password) {
        callback(new Error('两次输入密码不一致'))
    } else {
        callback()
    }
}

// 表单验证规则
const formRules = {
    old_password: [
        { required: true, message: '请输入原密码', trigger: 'blur' }
    ],
    password: [
        { required: true, message: '请输入新密码', trigger: 'blur' },
        { min: 6, message: '密码长度不能少于6位', trigger: 'blur' }
    ],
    confirm_password: [
        { required: true, message: '请确认新密码', trigger: 'blur' },
        { validator: validatePassword, trigger: 'blur' }
    ]
}

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    
    loading.value = true
    editCurrentUserPassword(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        if(res.code === 10000){
            ElMessage.success('密码修改成功，即将自动退出登录')
            // 重置表单数据
            Object.assign(formData, initialFormData)
            emit('complete')
            
            // 延迟1.5秒后自动退出登录
            setTimeout(() => {
                ElMessageBox.confirm('密码修改成功，系统将自动退出登录，请使用新密码重新登录', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'success',
                    showCancelButton: false,
                    showClose: false,
                    closeOnClickModal: false,
                    closeOnPressEscape: false
                }).then(() => {
                    userInfoStore.logout()
                }).catch(() => {
                    // 即使用户尝试取消也要退出登录
                    userInfoStore.logout()
                })
            }, 1500)
        }
    }).catch(() => {
        loading.value = false
    })
}



defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },

})
</script>

<style scoped>
.input-width {
    width: 100%;
}

.password-tips {
    margin-bottom: 20px;
}

.tips-content p {
    margin: 5px 0;
    font-size: 14px;
    line-height: 1.5;
}
</style>
