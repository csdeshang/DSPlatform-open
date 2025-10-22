<template>
    <el-card shadow="never">
        <h1 class="text-xl font-bold mb-6">分销商设置</h1>

        <el-form :model="formData" :rules="formRules" label-width="200px" ref="ruleFormRef" v-loading="loading">
            <el-form-item label="是否开启分销" prop="distributor_is_enabled">
                <el-switch v-model="formData.distributor_is_enabled" active-value="1" inactive-value="0" />
            </el-form-item>

            <el-form-item label="分销员层级" prop="distributor_tier">
                <el-radio-group v-model="formData.distributor_tier">
                    <el-radio value="1">一级分销</el-radio>
                    <el-radio value="2">二级分销</el-radio>
                </el-radio-group>
 
                <div class="text-gray-400 mt-1 w-full">
                    允许发佣的层级在相关设置中确定，分销佣金依据分销层级计算，各层级默认佣金比例需在分销等级设置里完成配置。
</div>
            </el-form-item>

            <el-form-item label="是否开启分销员自购分佣" prop="distributor_self_enabled">
                <el-switch v-model="formData.distributor_self_enabled" active-value="1" inactive-value="0" />
                <div class="text-gray-400 mt-1 w-full">开启后，分销员自购分销商品可以获得自购返佣</div>
            </el-form-item>

            <el-form-item label="分销商申请方式" prop="distributor_apply_type">
                <el-radio-group v-model="formData.distributor_apply_type">
                    <el-radio value="audit">审核分销商</el-radio>
                    <el-radio value="auto">自动分销商</el-radio>
                    <el-radio value="manual">手动分销商</el-radio>
                </el-radio-group>

                <div class="text-gray-400 mt-1 w-full">
    审核制：会员达标后需提交申请，经后台审核通过方可成为分销商<br>
    自动制：会员达到设定条件后，系统自动赋予其分销商资格，无需申请<br>
    手动制：仅支持后台手动添加分销商，前台会员无申请权限
</div>


            </el-form-item>

            <el-form-item label="分销商申请条件" prop="distributor_conditions">
                <el-radio-group v-model="formData.distributor_conditions">
                    <el-radio value="none">无条件</el-radio>
                    <el-radio value="amount">消费金额</el-radio>
                    <el-radio value="count">消费次数</el-radio>
                </el-radio-group>

            </el-form-item>

            <el-form-item label="消费金额" prop="distributor_apply_amount" v-if="formData.distributor_conditions === 'amount'">
                <el-input v-model="formData.distributor_apply_amount" placeholder="请输入消费金额" class="w-[200px]">
                    <template #append>元</template>
                </el-input>    
                <div class="text-gray-400 mt-1 w-full">满足此消费金额可申请成为分销商</div>
            </el-form-item>

            <el-form-item label="消费次数" prop="distributor_apply_count" v-if="formData.distributor_conditions === 'count'">
                <el-input v-model="formData.distributor_apply_count" placeholder="请输入消费次数"  class="w-[200px]">
                    <template #append>次</template>
                </el-input>    
                <div class="text-gray-400 mt-1 w-full">满足此消费次数可申请成为分销商</div>
            </el-form-item>
        </el-form>

        <div class="flex justify-center mt-10 mb-10">
            <el-button type="primary" :loading="loading" @click="handleSubmit">保存</el-button>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { computed, reactive, ref } from 'vue'
import type { FormInstance } from 'element-plus'
import { ElMessage } from 'element-plus'
import { getSysConfigList, editDistributorConfig } from '@/pages-admin/main/api/system/sysConfig'

defineOptions({
    name: 'DistributorSetting'
})

const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    distributor_is_enabled: '0',
    distributor_tier: '1',
    distributor_self_enabled: '0',
    distributor_apply_type: 'audit',
    distributor_conditions: 'none',
    distributor_apply_amount: 0,
    distributor_apply_count: 0,
})

const formRules = computed(() => {
    return {
        distributor_tier: [
            { required: true, message: '请选择分销员层级', trigger: 'change' }
        ],
        distributor_apply_type: [
            { required: true, message: '请选择分销商申请方式', trigger: 'change' }
        ],
        distributor_conditions: [
            { required: true, message: '请选择分销商申请条件', trigger: 'change' }
        ],
        distributor_apply_amount: [
            { 
                validator: (rule: any, value: any, callback: any) => {
                    if (formData.distributor_conditions === 'amount' && (!value || value <= 0)) {
                        callback(new Error('请输入大于0的消费金额'));
                    } else {
                        callback();
                    }
                },
                trigger: 'change'
            }
        ],
        distributor_apply_count: [
            {
                validator: (rule: any, value: any, callback: any) => {
                    if (formData.distributor_conditions === 'count' && (!value || value <= 0)) {
                        callback(new Error('请输入大于0的消费次数'));
                    } else {
                        callback();
                    }
                },
                trigger: 'change'
            }
        ],
    }
})

const setFormData = async () => {
    getSysConfigList({
        type: 'distributor'
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
        const result = await editDistributorConfig(formData)
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


