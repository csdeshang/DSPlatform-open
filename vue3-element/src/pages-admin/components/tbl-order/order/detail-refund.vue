<template>
    <!-- 详情页中显示订单退款记录   根据订单ID 显示订单退款记录 显示 TblOrderRefund 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="ID" width="100" />
        <el-table-column prop="platform" label="应用类型" width="120" />
        <el-table-column prop="order_id" label="订单ID" width="120" />
        <el-table-column prop="order_goods_id" label="订单商品ID" width="150" />
        <el-table-column prop="out_refund_no" label="商户退款单号" width="150" />
        <el-table-column prop="user_id" label="用户ID" width="100" />
        <el-table-column prop="store_id" label="店铺ID" width="100" />
        <el-table-column prop="apply_amount" label="申请退款金额" width="150" />
        <el-table-column prop="refund_type" label="退款类型" width="120" />
        <el-table-column prop="refund_status" label="退款状态" width="120" />
        <el-table-column prop="refund_method" label="退款方式" width="120" />
        <el-table-column prop="refund_amount" label="退款金额" width="150" />
        <el-table-column prop="refund_explain" label="退款申请说明" width="200" />
        <el-table-column prop="refund_images" label="退款申请图片" width="150" />
        <el-table-column prop="refund_address" label="退款地址" width="200" />
        <el-table-column prop="express_number" label="物流订单" width="150" />
        <el-table-column prop="express_company" label="物流名称" width="150" />
        <el-table-column prop="reject_reason" label="拒绝原因" width="150" />
        <el-table-column prop="agree_time" label="同意时间" width="150" />
        <el-table-column prop="reject_time" label="拒绝时间" width="150" />
        <el-table-column prop="success_time" label="成功时间" width="150" />
        <el-table-column prop="create_at" label="创建时间" width="150" />
        <el-table-column prop="update_at" label="更新时间" width="150" />

        <el-table-column label="操作" align="right" fixed="right" width="130">
            <template #default="{ row }">
                <el-button type="primary" link @click="handleTblOrderRefundLogList(row)">退款操作记录</el-button>
            </template>
        </el-table-column>

    </el-table>

    <DetailRefundLog ref="detailRefundLogDialog" />

</template>


<script lang="ts" setup>
import { getTblOrderRefundList } from '@/pages-admin/main/api/tbl-order/tblOrderRefund'
import { reactive, ref, watch } from 'vue';
import DetailRefundLog from './detail-refund-log.vue'



const props = defineProps({
    // 订单ID
    order_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    order_id: props.order_id
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载数据
const fetchTblOrderRefundList = () => {
    tableData.loading = true;
    getTblOrderRefundList(searchParams).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}


// 监听 order_id 的变化并更新 searchParams
watch(
    () => props.order_id,
    (newOrderId) => {
        searchParams.order_id = newOrderId;
        fetchTblOrderRefundList(); // 重新获取数据
    }
);


// 订单退款操作记录
const detailRefundLogDialog: Record<string, any> | null = ref(null)
const handleTblOrderRefundLogList = (row: any) => {
    detailRefundLogDialog.value.setDialogData(row)
    detailRefundLogDialog.value?.openDialog()
}

</script>