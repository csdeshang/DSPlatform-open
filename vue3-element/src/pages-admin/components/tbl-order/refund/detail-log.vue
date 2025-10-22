<template>
    <!-- 详情页中显示订单日志列表   根据订单ID 显示订单日志 显示 TblOrderLog 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="日志ID" width="100" />
        <el-table-column prop="refund_status" label="退款状态" width="120" />
        <el-table-column prop="message" label="日志描述" width="200" />
        <el-table-column prop="create_role" label="创建人角色" width="120" />
        <el-table-column prop="create_uid" label="创建人" width="120" />
        <el-table-column prop="create_at" label="创建时间" width="180"/>
        <el-table-column prop="extra" label="修改参数" width="200" />
    </el-table>


</template>


<script lang="ts" setup>
import { getTblOrderRefundLogList } from '@/pages-admin/main/api/tbl-order/tblOrderRefund'
import { reactive, watch } from 'vue';



const props = defineProps({
    // 订单ID
    refund_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    refund_id: props.refund_id
})


// 数据
const tableData = reactive({
    loading: true,
    data: [],
})


// 加载数据
const fetchTblOrderRefundLogList = () => {
    tableData.loading = true;
    getTblOrderRefundLogList(searchParams.refund_id).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}


// 监听 order_id 的变化并更新 searchParams
watch(
    () => props.refund_id,
    (newRefundId) => {
        searchParams.refund_id = newRefundId;
        fetchTblOrderRefundLogList(); // 重新获取数据
    }
);


</script>