<template>
    <!-- 详情页中显示支付记录列表   根据订单ID 显示支付记录 显示 TblTradePayLog 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="支付ID" width="100" />
        <el-table-column prop="user_id" label="用户ID" width="100" />
        <el-table-column prop="source_type" label="来源类型" width="120" />
        <el-table-column prop="source_id" label="来源ID" width="100" />
        <el-table-column prop="out_trade_no" label="商户订单号" width="150" />
        <el-table-column prop="trade_no" label="支付订单号" width="150" />
        <el-table-column prop="pay_merchant_id" label="收款商户ID" width="120" />
        <el-table-column prop="pay_channel" label="支付渠道" width="120" />
        <el-table-column prop="pay_scene" label="支付场景" width="120" />
        <el-table-column prop="pay_amount" label="支付金额" width="120" />
        <el-table-column prop="pay_status" label="支付状态" width="120" />
        <el-table-column prop="buyer_id" label="付款号" width="150" />
        <el-table-column prop="seller_id" label="收款号" width="150" />
        <el-table-column prop="pay_time" label="支付时间" width="180" />
        <el-table-column prop="close_time" label="关闭时间" width="180" />
        <el-table-column prop="create_at" label="创建时间" width="180" />
    </el-table>

</template>


<script lang="ts" setup>
import { getTblOrderPayLogList } from '@/pages-admin/main/api/tbl-order/tblOrder'
import { reactive, watch } from 'vue';


const props = defineProps({
    // 订单ID
    order_id: {
        type: Number,
        default: 0
    },
    // 订单合并ID
    order_merge_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    order_id: props.order_id,
    order_merge_id: props.order_merge_id
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载数据
const fetchTblOrderPayLogList = () => {
    tableData.loading = true;
    getTblOrderPayLogList(searchParams).then(res => {
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
        fetchTblOrderPayLogList(); // 重新获取数据
    }
);


</script>