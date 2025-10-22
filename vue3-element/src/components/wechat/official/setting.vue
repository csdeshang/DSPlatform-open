<template>
    <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules">
        <el-form-item label="AppID" prop="app_id">
            <el-input v-model="formData.app_id" placeholder="请输入AppID" />
        </el-form-item>
        <el-form-item label="App Secret" prop="app_secret">
            <el-input v-model="formData.app_secret" placeholder="请输入App Secret" />
        </el-form-item>
        <el-form-item label="Token" prop="token">
            <el-input v-model="formData.token" placeholder="请输入Token" />
        </el-form-item>
        <el-form-item label="Encoding AES Key" prop="encoding_aes_key">
            <el-input v-model="formData.encoding_aes_key" placeholder="请输入Encoding AES Key" />
        </el-form-item>
        <el-form-item label="加密类型" prop="encryption_type">
            <el-radio-group v-model="formData.encryption_type">
                <el-radio value="plain">明文模式</el-radio>
                <el-radio value="compatible">兼容模式</el-radio>
                <el-radio value="safe">安全模式(推荐)</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submitForm" :loading="loading">保存配置</el-button>
        </el-form-item>
    </el-form>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { ElMessage, type FormInstance } from 'element-plus';
import { getWechatOfficialSetting, updateWechatOfficialSetting } from '@/api/wechat/wechatOfficial';

// 定义组件名称
defineOptions({
    name: 'WechatSetting'
});

const formRef = ref<FormInstance>();
const loading = ref(false);



// 初始化表单数据
const initialFormData = {
    app_id: '',
    app_secret: '',
    token: '',
    encoding_aes_key: '',
    encryption_type: 'safe'
};

// 创建响应式数据
const formData = reactive({ ...initialFormData });

// 表单验证规则
const formRules = {
    app_id: [
        { required: true, message: '请输入AppID', trigger: 'blur' }
    ],
    app_secret: [
        { required: true, message: '请输入App Secret', trigger: 'blur' }
    ],
    token: [
        { required: true, message: '请输入Token', trigger: 'blur' }
    ],
    encoding_aes_key: [
        { required: true, message: '请输入Encoding AES Key', trigger: 'blur' }
    ],
    encryption_type: [
        { required: true, message: '请选择加密类型', trigger: 'blur' }
    ]
};

// 提交表单
const submitForm = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()

    loading.value = true
    updateWechatOfficialSetting(formData).then(res => {
        loading.value = false
        ElMessage.success('保存成功')

    }).catch(() => {
        loading.value = false
    })

};

// 获取微信配置
const fetchWechatOfficialSetting = async () => {
    loading.value = true;
    try {
        const res = await getWechatOfficialSetting();
        if (res && res.data) {
            Object.keys(formData).forEach(key => {
                if (res.data[key] !== undefined) {
                    formData[key] = res.data[key];
                }
            });
        }
    } catch (error) {
        console.error('获取配置失败:', error);
    } finally {
        loading.value = false;
    }
};

// 组件挂载时获取数据
onMounted(() => {
    fetchWechatOfficialSetting();
});
</script>

<style scoped lang="scss"></style>