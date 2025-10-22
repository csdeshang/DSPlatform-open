<template>

    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">

        <el-form :model="formData" label-width="150px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="等级名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入等级名称"></el-input>
            </el-form-item>
            <el-form-item label="级别排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="0" placeholder="请输入级别排序"
                    :disabled="formData.is_default == 1"></el-input-number>
            </el-form-item>
            <el-form-item label="等级描述" prop="description">
                <el-input v-model="formData.description" :rows="3" type="textarea" placeholder="请输入等级描述" />
            </el-form-item>

            <el-divider>佣金设置</el-divider>
            <el-form-item label="自购佣金比例" prop="base_self_ratio">
                <el-input-number v-model="formData.base_self_ratio" :min="0" :max="100" :precision="2" :step="0.1">
                    <template #suffix>%</template>
                </el-input-number>
            </el-form-item>
            <el-form-item label="1级佣金比例" prop="base_parent1_ratio">
                <el-input-number v-model="formData.base_parent1_ratio" :min="0" :max="100" :precision="2" :step="0.1">
                    <template #suffix>%</template>
                </el-input-number>
            </el-form-item>
            <el-form-item label="2级佣金比例" prop="base_parent2_ratio">
                <el-input-number v-model="formData.base_parent2_ratio" :min="0" :max="100" :precision="2" :step="0.1">
                    <template #suffix>%</template>
                </el-input-number>
            </el-form-item>

            <div v-if="!formData.is_default">
                <el-divider>升级条件设置</el-divider>
                <el-form-item label="是否开启">
                    <el-switch v-model="formData.self_single_amount_is" :active-value="1"
                        :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="自购单笔消费">
                    <el-input-number v-model="formData.self_single_amount" :min="0" :precision="2"
                        placeholder="请输入金额"></el-input-number>
                </el-form-item>
                <el-form-item label="是否开启">
                    <el-switch v-model="formData.self_total_amount_is" :active-value="1"
                        :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="自购总消费金额">
                    <el-input-number v-model="formData.self_total_amount" :min="0" :precision="2"
                        placeholder="请输入金额"></el-input-number>
                </el-form-item>
                <el-form-item label="是否开启">
                    <el-switch v-model="formData.self_total_count_is" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="自购消费次数">
                    <el-input-number v-model="formData.self_total_count" :min="0" placeholder="请输入次数"></el-input-number>
                </el-form-item>


                <el-form-item label="是否开启">
                    <el-switch v-model="formData.parent1_total_amount_is" :active-value="1"
                        :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="一级分销订单金额">
                    <el-input-number v-model="formData.parent1_total_amount" :min="0" :precision="2"
                        placeholder="请输入金额"></el-input-number>
                </el-form-item>

                <el-form-item label="是否开启">
                    <el-switch v-model="formData.parent1_total_count_is" :active-value="1"
                        :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="一级分销订单数">
                    <el-input-number v-model="formData.parent1_total_count" :min="0"
                        placeholder="请输入数量"></el-input-number>
                </el-form-item>

                <el-form-item label="是否开启">
                    <el-switch v-model="formData.invite_count_is" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="邀请注册人数">
                    <el-input-number v-model="formData.invite_count" :min="0" placeholder="请输入人数"></el-input-number>
                </el-form-item>
            </div>




        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
            </span>
        </template>
    </el-drawer>
</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { createDistributorLevel, updateDistributorLevel, getDistributorLevelInfo } from '@/pages-admin/main/api/distributor/distributorLevel'


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

/**
* 表单数据
*/
const initialFormData = {
    id: '',
    // 名称
    name: '',
    // 级别
    sort: '255',
    // 描述
    description: '',
    // 自购佣金比例
    base_self_ratio: '',
    // 1级佣金比例
    base_parent1_ratio: '',
    // 2级佣金比例
    base_parent2_ratio: '',
    // 是否是默认等级
    is_default: '',
    // 自购单笔消费金额
    self_single_amount: '',
    // 自购单笔消费金额是否开启
    self_single_amount_is: '',
    // 自购总消费金额
    self_total_amount: '',
    // 自购总消费金额是否开启
    self_total_amount_is: '',
    // 自购消费次数
    self_total_count: '',
    // 自购消费次数是否开启
    self_total_count_is: '',
    // 一级分销订单总金额
    parent1_total_amount: '',
    // 一级分销订单总金额是否开启
    parent1_total_amount_is: '',
    // 一级分销订单总数
    parent1_total_count: '',
    // 一级分销订单总数是否开启
    parent1_total_count_is: '',
    // 邀请注册人数
    invite_count: '',
    // 邀请注册人数是否开启
    invite_count_is: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入等级名称', trigger: 'blur' }
        ],
        sort: [
            { required: true, message: '请输入级别排序', trigger: 'blur' }
        ],
        base_self_ratio: [
            { required: true, message: '请输入自购佣金比例', trigger: 'blur' }
        ],
        base_parent1_ratio: [
            { required: true, message: '请输入1级佣金比例', trigger: 'blur' }
        ],
        base_parent2_ratio: [
            { required: true, message: '请输入2级佣金比例', trigger: 'blur' }
        ]
    }
})


const emit = defineEmits(['complete'])



const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateDistributorLevel : createDistributorLevel
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
    popTitle = '添加等级'

    if (row) {
        popTitle = '更新等级'
        const data = await (await getDistributorLevelInfo(row.id)).data
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