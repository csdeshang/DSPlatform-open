<template>
    <el-card shadow="never">
        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="交付ID" width="100" />
            <el-table-column prop="order_id" label="订单ID" width="120" />
            <el-table-column prop="order.order_status_desc" label="订单状态" width="120" />
            <el-table-column prop="delivery_method_desc" label="交付方式" width="200" />
            <el-table-column prop="technician_appt_time" label="预约时间" width="200" />
            <el-table-column prop="technician_fee_rate" label="店铺佣金比例" width="200" />
            <el-table-column prop="technician_fee" label="师傅服务费" width="200" />
            <el-table-column prop="delivery_status_desc" label="交付状态" width="200" />
        </el-table>

        <div class="flex justify-end mt-[20px]">

            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { getTblOrderDeliveryPages } from '@/pages-admin/main/api/tbl-order/tblOrderDelivery'
import { reactive, watch } from 'vue'
import { usePagination } from '@/hooks/usePagination'

// 组件名称
defineOptions({
  name: 'TechnicianDetailOrderDelivery'
})

const props = defineProps({
    // 师傅ID
    technician_id: {
        type: Number,
        default: 0
    }
})

const searchParams = reactive({
    technician_id: props.technician_id
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblOrderDeliveryPages,
    searchParams: searchParams
})

// 监听 technician_id 的变化并更新 searchParams
watch(
    () => props.technician_id,
    (newTechnicianId) => {
        searchParams.technician_id = newTechnicianId
        if (newTechnicianId > 0) {
            getTableList() // 重新获取数据
        }
    }
)
</script>
