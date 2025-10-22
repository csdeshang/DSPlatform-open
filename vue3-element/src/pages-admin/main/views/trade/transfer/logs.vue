<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户ID">
                <el-input v-model="searchParams.user_id" placeholder="用户ID" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="商户转账单号">
                <el-input v-model="searchParams.out_transfer_no" placeholder="商户转账单号" clearable />
            </el-form-item>
            <el-form-item label="转账单号">
                <el-input v-model="searchParams.transfer_no" placeholder="转账单号" clearable />
            </el-form-item>
            <el-form-item label="转账金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.transfer_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.transfer_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="转账状态">
                <el-select v-model="searchParams.transfer_status" placeholder="请选择转账状态" clearable class="w-[100px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in transfer_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">



        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="user_id" label="用户ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user_id)">{{
                        row.user_id
                    }}</el-link>
                </template>
            </el-table-column>
            <el-table-column prop="source_type" label="关联类型" width="100" />
            <el-table-column prop="source_id" label="关联ID" width="100" />
            <el-table-column prop="out_transfer_no" label="商户转账单号" width="180" />
            <el-table-column prop="transfer_no" label="转账单号" width="180" />
            <el-table-column prop="transfer_type" label="转账类型" width="100" />
            <el-table-column prop="transfer_amount" label="转账金额" width="120" />
            <el-table-column prop="transfer_status" label="状态" width="100" />
            <el-table-column prop="account_type" label="账户类型" width="100" />
            <el-table-column prop="account_name" label="账户名称" width="120" />
            <el-table-column prop="account_number" label="账号" width="180" />
            <el-table-column prop="account_holder" label="持有人" width="100" />
            <el-table-column prop="create_at" label="创建时间" width="160" />
            <el-table-column prop="transfer_response" label="返回结果" min-width="200" show-overflow-tooltip />
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>



    <UserDetail ref="detailUserDialog" />






</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { getTradeTransferLogPages } from '@/pages-admin/main/api/trade/tradeTransferLog'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'

// 使用枚举 Hook
const { options: transfer_status_options, } = useEnum('default.trade_transfer_log.transfer_status')


const searchParams = reactive({
    user_id: '',
    username: '',
    out_transfer_no: '',
    transfer_no: '',
    transfer_amount_min: '',
    transfer_amount_max: '',
    transfer_status: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTradeTransferLogPages,
    searchParams: searchParams
})
getTableList()






// 会员详情弹窗[公共组件]
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



</script>
