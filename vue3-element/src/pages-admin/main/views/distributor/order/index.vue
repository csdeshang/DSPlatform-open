<template>

    <el-card shadow="never" class="mb-[10px]">
        <!--提示-->
        <el-alert title="分销订单只有在订单已完成且不能申请退款的状态下，才能对分销订单结算佣金。" type="info" show-icon />
    </el-card>



    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="订单编号">
                <el-input v-model="searchParams.order_sn" placeholder="订单编号" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="商品名">
                <el-input v-model="searchParams.goods_name" placeholder="请输入商品名" clearable />
            </el-form-item>
            <el-form-item label="店铺名">
                <el-input v-model="searchParams.store_name" placeholder="请输入店铺名" clearable />
            </el-form-item>
            <el-form-item label="佣金类型">
                <el-select v-model="searchParams.commission_type" placeholder="请选择佣金类型" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in commission_type_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="佣金金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.commission_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.commission_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="支付金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.pay_price_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.pay_price_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>



    <el-card shadow="never">

        <el-tabs v-model="searchParams.commission_status" @tab-change="handleTabChange">
            <el-tab-pane name="" label="全部"></el-tab-pane>
            <el-tab-pane v-for="item in distributor_order_status_options" :key="item.value" :name="item.value"
                :label="item.label"></el-tab-pane>
        </el-tabs>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="佣金类型" prop="commission_type_desc" min-width="120" />
            <el-table-column label="实付金额" prop="pay_price" min-width="120" />
            <el-table-column label="佣金" prop="commission_amount" min-width="120" />

            <el-table-column label="佣金状态" prop="commission_status_desc" min-width="120" />
            <el-table-column label="备注" prop="commission_remark" min-width="300" />


            <el-table-column label="分销商ID" prop="distributor_user_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleUserDetail(row.distributor_user_id)">{{
                        row.distributor_user_id }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="分销商等级" prop="distributor_level_id" min-width="100" />
            <el-table-column label="购买者ID" prop="user_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleUserDetail(row.user_id)">{{ row.user_id }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="店铺ID" prop="store_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleStoreDetail(row.store_id)">{{ row.store_id
                    }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="订单ID" prop="order_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleTblOrderDetail(row)">{{ row.order_id }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="商品ID" prop="goods_id" min-width="100" />
        </el-table>

        <div class="flex justify-end mt-[20px]">

            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />

        </div>


    </el-card>


    <!-- 订单详情弹窗 -->
    <TblOrderDetail ref="handleOrderDetailRef" />
    <!-- 会员详情弹窗 -->
    <UserDetail ref="handleUserDetailRef" />
    <!-- 店铺详情弹窗 -->
    <TblStoreDetail ref="handleStoreDetailRef" />


</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';

import { usePagination } from '@/hooks/usePagination'
import { getDistributorOrderPages } from '@/pages-admin/main/api/distributor/distributorOrder'





const searchParams = reactive({
    commission_status: '',
    order_sn: '',
    username: '',
    goods_name: '',
    store_name: '',
    commission_type: '',
    commission_amount_min: '',
    commission_amount_max: '',
    pay_price_min: '',
    pay_price_max: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getDistributorOrderPages,
    searchParams: searchParams
})
getTableList()


// 切换订单状态
const handleTabChange = (name: any) => {
    searchParams.commission_status = name
    getTableList()
}


import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: distributor_order_status_options } = useEnum('default.distributor_order.commission_status')
const { options: commission_type_options } = useEnum('default.distributor_order.commission_type')


// 订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const handleOrderDetailRef = ref<any>()
const handleTblOrderDetail = (row: any) => {
    handleOrderDetailRef.value.setDialogData({ id: row.order_id })
    handleOrderDetailRef.value?.openDialog()
}


// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const handleUserDetailRef = ref<any>()
const handleUserDetail = (user_id: any) => {
    handleUserDetailRef.value.setDialogData({ id: user_id })
    handleUserDetailRef.value?.openDialog()
}

// 店铺详情弹窗
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
const handleStoreDetailRef = ref<any>()
const handleStoreDetail = (store_id: any) => {
    handleStoreDetailRef.value.setDialogData({ id: store_id })
    handleStoreDetailRef.value?.openDialog()
}


</script>
