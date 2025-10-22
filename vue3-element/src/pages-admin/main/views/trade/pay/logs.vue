<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户ID">
                <el-input v-model="searchParams.user_id" placeholder="用户ID" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="来源类型">
                <el-select v-model="searchParams.source_type" placeholder="请选择来源类型" clearable class="w-[100px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in source_type_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="商户订单号">
                <el-input v-model="searchParams.out_trade_no" placeholder="商户订单号" clearable />
            </el-form-item>
            <el-form-item label="支付订单号">
                <el-input v-model="searchParams.trade_no" placeholder="支付订单号" clearable />
            </el-form-item>
            <el-form-item label="支付状态">
                <el-select v-model="searchParams.pay_status" placeholder="请选择支付状态" clearable class="w-[100px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in pay_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="支付金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.pay_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.pay_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">



        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="支付ID" width="100" />
            <el-table-column prop="user_id" label="用户ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user_id)">{{
                        row.user_id
                        }}</el-link>
                </template>
            </el-table-column>
            <el-table-column prop="source_type_desc" label="来源类型" width="120" />
            <el-table-column prop="source_id" label="来源ID" width="100" />
            <el-table-column prop="out_trade_no" label="商户订单号" width="150" />
            <el-table-column prop="trade_no" label="支付订单号" width="150" />
            <el-table-column prop="pay_merchant_id" label="收款商户ID" width="120" />
            <el-table-column prop="pay_channel" label="支付渠道" width="120" />
            <el-table-column prop="pay_scene" label="支付场景" width="120" />
            <el-table-column prop="pay_amount" label="支付金额" width="120" />
            <el-table-column prop="pay_status_desc" label="支付状态" width="120" />
            <el-table-column prop="buyer_id" label="付款号" width="150" />
            <el-table-column prop="seller_id" label="收款号" width="150" />
            <el-table-column prop="pay_time" label="支付时间" width="180" />
            <el-table-column prop="close_time" label="关闭时间" width="180" />
            <el-table-column prop="create_at" label="创建时间" width="180" />
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

import { getTradePayLogPages } from '@/pages-admin/main/api/trade/tradePayLog'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'

// 使用枚举 Hook
const { options: source_type_options, } = useEnum('default.trade_pay_log.source_type')
const { options: pay_status_options, } = useEnum('default.trade_pay_log.pay_status')


const searchParams = reactive({
    user_id: '',
    username: '',
    source_type: '',
    out_trade_no: '',
    trade_no: '',
    pay_status: '',
    pay_amount_min: '',
    pay_amount_max: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTradePayLogPages,
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
