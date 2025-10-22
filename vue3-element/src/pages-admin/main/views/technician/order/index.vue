<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="师傅ID">
                    <el-input v-model="searchParams.technician_id" placeholder="请输入师傅ID" clearable />
                </el-form-item>
                <el-form-item label="订单ID">
                    <el-input v-model="searchParams.order_id" placeholder="请输入订单ID" clearable />
                </el-form-item>
                <el-form-item label="交付状态">
                    <el-select v-model="searchParams.delivery_status" placeholder="请选择交付状态" clearable>
                        <el-option label="待分配" value="20" />
                        <el-option label="到达中" value="30" />
                        <el-option label="到达完成" value="40" />
                        <el-option label="服务完成" value="50" />
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
                <el-table-column label="师傅ID" width="100">
                    <template #default="{ row }">
                        <el-link v-if="row.technician_id" type="primary" :underline="false" @click="handleTechnicianDetail(row.technician_id)">
                            {{ row.technician_id }}
                        </el-link>
                        <span v-else>--</span>
                    </template>
                </el-table-column>
                <el-table-column label="订单ID" width="100">
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleTblOrderDetail(row.order_id)">
                            {{ row.order_id }}
                        </el-link>
                    </template>
                </el-table-column>
                <el-table-column label="服务地址" width="300">
                    <template #default="{ row }">
                        <div class="flex flex-col gap-[5px]">
                            <div>{{ row.orderAddress?.reciver_name || '--' }}</div>
                            <div>{{ row.orderAddress?.reciver_mobile || '--' }}</div>
                            <div>{{ row.orderAddress?.reciver_address || '--' }}</div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="师傅服务信息" width="400">
                    <template #default="{ row }">
                        <div>
                            <div>预约时间：{{ row.technician_appt_time || '--' }}</div>
                            <div>服务时长：{{ row.technician_duration ? row.technician_duration + '分钟' : '--' }}</div>
                            <div>抽佣比例：{{ row.technician_fee_rate || '--' }}</div>
                            <div>师傅费用：{{ row.technician_fee || '--' }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="delivery_status_desc" label="交付状态" width="100" />
                <el-table-column prop="create_at" label="创建时间" width="150" />
                <el-table-column prop="update_at" label="更新时间" width="150" />
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination 
                    v-model:current-page="tableData.page_current" 
                    v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="tableData.total" 
                    @size-change="getTableList()"
                    @current-change="getTableList" 
                />
            </div>
        </el-card>

        <technician-detail ref="detailTechnicianDialog" />
        <tbl-order-detail ref="detailTblOrderDialog" />
    </div>
</template>

<script lang="ts" setup name="TechnicianOrderIndex">
import { reactive, ref } from 'vue';
// 师傅订单配送，使用订单配送接口，主要显示师傅配送相关数据
import { getTblOrderDeliveryPages } from '@/pages-admin/main/api/tbl-order/tblOrderDelivery'
import { usePagination } from '@/hooks/usePagination'

const searchParams = reactive({
    delivery_method: 'technician',
    technician_id: '',
    order_id: '',
    delivery_status: '',
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

// 师傅详情弹窗
import TechnicianDetail from '@/pages-admin/main/views/technician/technician/detail.vue'
const detailTechnicianDialog = ref()
const handleTechnicianDetail = (technician_id: any) => {
    detailTechnicianDialog.value.setDialogData({ id: technician_id })
    detailTechnicianDialog.value?.openDialog()
}

// 订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const detailTblOrderDialog = ref()
const handleTblOrderDetail = (order_id: any) => {
    detailTblOrderDialog.value.setDialogData({ id: order_id })
    detailTblOrderDialog.value?.openDialog()
}
</script>