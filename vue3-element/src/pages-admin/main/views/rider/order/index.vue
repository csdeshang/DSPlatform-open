<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="骑手名称">
                <el-input v-model="searchParams.rider_id" placeholder="请输入骑手ID" clearable />
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
            <el-table-column label="骑手ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleRiderDtail(row.rider_id)" v-if="row.rider_id">
                        {{ row.rider_id }}
                    </el-link>
                    <div v-else>未分配</div>
                </template>
            </el-table-column>
            <el-table-column label="订单ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleTblOrderDtail(row.order_id)">
                        {{ row.order_id }}
                    </el-link>
                </template>
            </el-table-column>
            <el-table-column label="取件地址" width="300">
                <template #default="{ row }">
                    <div class="flex flex-col gap-[5px]">
                        <div>{{ row.orderAddress.shipper_name || '--' }}</div>
                        <div>{{ row.orderAddress.shipper_mobile || '--' }}</div>
                        <div>{{ row.orderAddress.shipper_address || '--' }}</div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="收件地址" width="300">
                <template #default="{ row }">
                    <div class="flex flex-col gap-[5px]">
                        <div>{{ row.orderAddress.reciver_name || '--' }}</div>
                        <div>{{ row.orderAddress.reciver_mobile || '--' }}</div>
                        <div>{{ row.orderAddress.reciver_address || '--' }}</div>
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="配送费用" width="400">
                <template #default="{ row }">
                    <div>
                        <div>配送费：{{ row.rider_total_fee || '--' }}</div>
                        <div>抽佣比例：{{ row.rider_fee_rate || '--' }}</div>
                        <div>骑手费用：{{ row.rider_fee || '--' }}</div>
                        <div>配送距离：{{ row.rider_distance || '--' }}</div>
                        <div>说明：{{ row.rider_fee_desc || '--' }}</div>
                        <div>配送完成时间：{{ row.rider_complete_time || '--' }}</div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="delivery_status_desc" label="交付状态" width="100" />
            <el-table-column prop="create_at" label="创建时间" width="150" />
            <el-table-column prop="update_at" label="更新时间" width="150" />

        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>


    <rider-detail ref="detailRiderDialog" />
    <tbl-order-detail ref="detailTblOrderDialog" />

</template>
<script lang="ts" setup >

import { reactive, ref } from 'vue';
// 骑手订单配送，使用订单配送接口，主要显示骑手配送相关数据
import { getTblOrderDeliveryPages } from '@/pages-admin/main/api/tbl-order/tblOrderDelivery'


import { usePagination } from '@/hooks/usePagination'


const searchParams = reactive({
    delivery_method: 'rider',
    rider_id: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getTblOrderDeliveryPages,
    searchParams: searchParams
})
getTableList()




// 骑手详情弹窗
import RiderDetail from '@/pages-admin/main/views/rider/rider/detail.vue'
const detailRiderDialog = ref()
const handleRiderDtail = (rider_id: any) => {
    detailRiderDialog.value.setDialogData({ id: rider_id })
    detailRiderDialog.value?.openDialog()
}

//订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const detailTblOrderDialog = ref()
const handleTblOrderDtail = (order_id: any) => {
    detailTblOrderDialog.value.setDialogData({ id: order_id })
    detailTblOrderDialog.value?.openDialog()
}


</script>