<template>

    <!-- 后台与商户管理 支付方式列表 -->

    <el-card shadow="never" class="mt-4 !border-none" v-for="(value, scene) in tableData.data" :key="scene">
        <div class="text-lg mb-[24px]" >{{ value.name }}</div>
        <el-table :data="Object.values(value.payment_config_list)" style="width: 100%">
            <el-table-column label="图标" min-width="150">
                <template #default="{ row }">
                    <el-image :src="fetchRemoteIconUrl(row.icon)" alt="图标" style="width: 34px; height: 34px" />
                </template>
            </el-table-column>
            <el-table-column prop="payment_name" label="支付方式" min-width="150" />
            <el-table-column prop="sort" label="排序" min-width="150" />
            <el-table-column label="开启状态" min-width="150">
                <template #default="{ row }">
                    <span>
                        {{ row.is_enabled == 1 ? '开启' : '关闭' }}
                    </span>
                </template>
            </el-table-column>
            <el-table-column label="操作" min-width="150">
                <template #default="{ row }">
                    <el-button type="primary" size="small" @click="handleEdit(row)">设置</el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>

    <payment-config-edit ref="editPaymentConfigDialog"  @complete="fetchPaymentConfigByMerchant()"/>


</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';

import { fetchRemoteIconUrl } from '@/utils/image'
import { getPaymentConfigByMerchant } from './paymentConfig'

import paymentConfigEdit from './edit.vue'


// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载系统支付方式列表
const fetchPaymentConfigByMerchant = () => {
    tableData.loading = true;
    // 获取支付配置数据 0 为总平台
    getPaymentConfigByMerchant().then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化系统支付方式列表
fetchPaymentConfigByMerchant();



const editPaymentConfigDialog: Record<string, any> | null = ref(null)
const handleEdit = (row: any) => {
    editPaymentConfigDialog.value.setDialogData(row);
    editPaymentConfigDialog.value?.openDialog()
}




</script>
