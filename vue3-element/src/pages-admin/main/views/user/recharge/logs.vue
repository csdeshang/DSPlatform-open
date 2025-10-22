<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="用户名" clearable />
            </el-form-item>
            <el-form-item label="商户订单号">
                <el-input v-model="searchParams.out_trade_no" placeholder="商户订单号" clearable />
            </el-form-item>
            <el-form-item label="支付订单号">
                <el-input v-model="searchParams.trade_no" placeholder="支付订单号" clearable />
            </el-form-item>
            <el-form-item label="充值金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.recharge_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.recharge_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="充值状态">
                <el-select v-model="searchParams.recharge_status" placeholder="请选择充值状态" clearable class="w-[100px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in recharge_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="user_id" label="用户ID" width="80">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user.id)">{{
                        row.user.username
                    }}</el-link>
                </template>
            </el-table-column>
            <el-table-column prop="pay_channel" label="支付系统" width="100" />
            <el-table-column prop="pay_scene" label="支付场景" width="100" />
            <el-table-column prop="recharge_amount" label="充值金额" width="100" />
            <el-table-column prop="recharge_status_desc" label="支付状态" width="100" />
            <el-table-column prop="out_trade_no" label="商户订单号" width="260" />
            <el-table-column prop="trade_no" label="支付订单号" width="260" />
            <el-table-column prop="pay_merchant_id" label="收款商户ID" width="100" />
            <el-table-column prop="create_at" label="创建时间" width="200" />
            <el-table-column prop="pay_time" label="支付时间" width="200" />
  

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

import { getUserRechargeLogPages } from '@/pages-admin/main/api/user/userRecharge'
import { usePagination } from '@/hooks/usePagination'
// 充值状态选项
const recharge_status_options = [
    { label: '未完成', value: '0' },
    { label: '已完成', value: '1' }
]

const searchParams = reactive({
    username: '',
    out_trade_no: '',
    trade_no: '',
    recharge_amount_min: '',
    recharge_amount_max: '',
    recharge_status: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserRechargeLogPages,
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
