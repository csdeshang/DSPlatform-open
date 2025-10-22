<template>

    <el-form :model="configData" label-width="120px">


        <el-form-item label="app_id" prop="app_id">
            <el-input v-model="configData.app_id" placeholder="请输入app_id" />
            「必填」 支付宝分配的 app_id
        </el-form-item>
        <el-form-item label="应用私钥" prop="app_secret_cert">
            <el-input type="textarea" :rows="6" v-model="configData.app_secret_cert" placeholder="请输入应用私钥" />
            「必填」应用私钥 
        </el-form-item>
        <el-form-item label="应用公钥证书" prop="app_public_cert">
            <el-input type="textarea" :rows="6" v-model="configData.app_public_cert" placeholder="请输入应用公钥证书" />
            「必填」应用公钥证书 设置应用私钥后，即可下载得到 appCertPublicKey
            <div v-if="configData.app_public_cert_path">证书路径：{{ configData.app_public_cert_path }}</div>
        </el-form-item>
        <el-form-item label="支付宝公钥证书" prop="alipay_public_cert">
            <el-input type="textarea" :rows="6" v-model="configData.alipay_public_cert" placeholder="请输入支付宝公钥证书" />
            「必填」支付宝公钥证书  设置应用私钥后，即可下载得到 alipayCertPublicKey_RSA2
            <div v-if="configData.alipay_public_cert_path">证书路径：{{ configData.alipay_public_cert_path }}</div>
        </el-form-item>
        <el-form-item label="支付宝根证书" prop="alipay_root_cert">
            <el-input type="textarea" :rows="6" v-model="configData.alipay_root_cert" placeholder="请输入支付宝根证书" />
            「必填」支付宝根证书  设置应用私钥后，即可下载得到 alipayRootCert
            <div v-if="configData.alipay_root_cert_path">证书路径：{{ configData.alipay_root_cert_path }}</div>
        </el-form-item>





    </el-form>



</template>

<script setup lang="ts">
import { reactive, toRefs, watch } from 'vue';



const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({

        }),
    },
})



const emit = defineEmits(['update:modelValue']);


const configData = reactive({ ...props.modelValue });

watch(configData, (newValue) => {
    emit('update:modelValue', newValue);
}, { deep: true });

// 监听 props 的变化
watch(() => props.modelValue, (newValue) => {
    Object.assign(configData, newValue);
});

</script>
