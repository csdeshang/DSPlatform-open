<template>

    <el-form :model="configData" label-width="120px">


        <el-form-item label="app_id" prop="app_id">
            <el-input v-model="configData.app_id" placeholder="请输入app_id" />
            「必填」 微信小程序appid 公众号appid  APP应用appid
        </el-form-item>

        <el-form-item label="商户号" prop="mch_id">
            <el-input v-model="configData.mch_id" placeholder="请输入商户号" />
            「必填」商户号 可在 https://pay.weixin.qq.com/ 账户中心->商户信息 查看
        </el-form-item>

        <el-form-item label="商户秘钥" prop="mch_secret_key">
            <el-input v-model="configData.mch_secret_key" placeholder="请输入商户秘钥" />
            「必填」v3 商户秘钥  即 API v3 密钥(32字节，形如md5值)，可在 账户中心->API安全 中设置
        </el-form-item>



        <el-form-item label="商户私钥" prop="mch_secret_cert">
            <el-input type="textarea" :rows="6" v-model="configData.mch_secret_cert" placeholder="请输入商户私钥" />
            「「必填」商户私钥 字符串或路径  即 API证书 PRIVATE KEY，可在 账户中心->API安全->申请API证书 里获得  （apiclient_key.pem）
            <div v-if="configData.mch_secret_cert_path">证书路径：{{ configData.mch_secret_cert_path }}</div>
        </el-form-item>

        <el-form-item label="商户公钥证书" prop="mch_public_cert">
            <el-input type="textarea" :rows="6" v-model="configData.mch_public_cert" placeholder="请输入商户公钥证书" />
            「必填」商户公钥证书路径  即 API证书 CERTIFICATE，可在 账户中心->API安全->申请API证书 里获得 (apiclient_cert.pem)
            <div v-if="configData.mch_public_cert_path">证书路径：{{ configData.mch_public_cert_path }}</div>
        </el-form-item>



        <el-form-item label="微信支付公钥ID" prop="wechat_public_cert_id">
            <el-input v-model="configData.wechat_public_cert_id" placeholder="请输入微信支付公钥ID" />
            微信支付公钥ID及证书路径
        </el-form-item>
        <el-form-item label="微信支付公钥证书" prop="wechat_public_cert">
            <el-input type="textarea" :rows="6"  v-model="configData.wechat_public_cert" placeholder="请输入微信支付公钥证书" />
            微信支付公钥ID及证书路径
            <div v-if="configData.wechat_public_cert_path">证书路径：{{ configData.wechat_public_cert_path }}</div>
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
