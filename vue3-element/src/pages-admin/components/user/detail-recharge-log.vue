<template>
    <!-- 详情页中显示充值记录列表   根据用户ID 显示充值记录 显示 UserRechargeLog 表中的数据 -->

    <div>

        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="pay_channel" label="支付系统" width="100" />
            <el-table-column prop="pay_scene" label="支付场景" width="100" />
            <el-table-column prop="recharge_amount" label="充值金额" width="100" />
            <el-table-column prop="recharge_status_desc" label="支付状态" width="100" />
            <el-table-column prop="out_trade_no" label="商户订单号" width="200" />
            <el-table-column prop="trade_no" label="支付订单号" width="200" />
            <el-table-column prop="pay_merchant_id" label="收款商户ID" width="100" />
            <el-table-column prop="create_at" label="创建事件" width="100" />
            <el-table-column prop="pay_time" label="支付时间" width="100" />
            <el-table-column prop="update_at" label="更新时间" width="100" />
        </el-table>
        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </div>

</template>


<script lang="ts" setup>
import { getUserRechargeLogPages } from '@/pages-admin/main/api/user/userRecharge'
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
    requestFun: getUserRechargeLogPages,
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