<template>
    <el-card shadow="never">

        <el-form :model="formData" :rules="formRules" label-width="200px" ref="ruleFormRef" v-loading="loading">
            <el-form-item label="商品是否审核" prop="goods_need_audit">
                <el-switch v-model="formData.goods_need_audit" :active-value="1" :inactive-value="0" />
            </el-form-item>
            <el-form-item label="商品分类选择限制" prop="goods_category_select_limit">
                <el-input v-model="formData.goods_category_select_limit" placeholder="请输入商品分类选择限制" />
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
import { getSysConfigList, editGoodsRules } from '@/pages-admin/main/api/system/sysConfig'



const loading = ref(true)
const ruleFormRef = ref<FormInstance>()
const formData: any = reactive({
    goods_need_audit: 0,
    goods_category_select_limit: 10,
})

const formRules = computed(() => {
    return {

    }
})


const setFormData = async () => {
    getSysConfigList({
        type: 'goods'
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
        await editGoodsRules(formData)
        ElMessage.success('保存成功')
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

</script>