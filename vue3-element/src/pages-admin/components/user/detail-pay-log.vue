<template>
    <!-- 详情页中显示支付记录列表   根据订单ID 显示支付记录 显示 TblTradePayLog 表中的数据 -->


    <div>



        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="支付ID" width="100" />
            <el-table-column prop="user_id" label="用户ID" width="100" />
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

    </div>
</template>


<script lang="ts" setup>
import { getTradePayLogPages } from '@/pages-admin/main/api/trade/tradePayLog'
import { reactive, watch } from 'vue';
import { usePagination } from '@/hooks/usePagination'


const props = defineProps({
    // 用户ID
    user_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    user_id: props.user_id,
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



// 监听 user_id 的变化并更新 searchParams
watch(
    () => props.user_id,
    (newUserId) => {
        searchParams.user_id = newUserId;
        getTableList(); // 重新获取数据
    }
);


</script>