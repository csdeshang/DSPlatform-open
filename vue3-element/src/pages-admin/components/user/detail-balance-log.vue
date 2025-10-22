<template>
    <!-- 详情页中显示支付记录列表   根据订单ID 显示支付记录 显示 TblTradePayLog 表中的数据 -->


    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="ID" width="100" />
        <el-table-column prop="change_type_desc" label="变动类型" width="100" />
        <el-table-column prop="related_id" label="关联ID" width="100" />
        <el-table-column prop="before_balance" label="变动前余额" width="100" />
        <el-table-column prop="change_mode_desc" label="变动方式" width="100" />
        <el-table-column prop="change_amount" label="变动金额" width="100" />
        <el-table-column prop="after_balance" label="变动后余额" width="100" />
        <el-table-column prop="change_desc" label="描述" />
        <el-table-column prop="create_at" label="变动时间" width="100" />
    </el-table>
    <div class="flex justify-end mt-[20px]">
        <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
            layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
            @current-change="getTableList" />
    </div>


</template>


<script lang="ts" setup>
import { getUserBalanceLogPages } from '@/pages-admin/main/api/user/userBalance'
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
    requestFun: getUserBalanceLogPages,
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