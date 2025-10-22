<template>

    <el-card shadow="never">

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
    </el-card>

    
</template>


<script lang="ts" setup>
import { getRiderBalanceLogPages } from '@/pages-admin/main/api/rider/riderBalance'
import { reactive, watch } from 'vue';
import { usePagination } from '@/hooks/usePagination'


const props = defineProps({
    // 骑手ID
    rider_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    rider_id: props.rider_id,
})


const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getRiderBalanceLogPages,
    searchParams: searchParams
})



// 监听 rider_id 的变化并更新 searchParams
watch(
    () => props.rider_id,
    (newRiderId) => {
        searchParams.rider_id = newRiderId;
        getTableList(); // 重新获取数据
    }
);


</script>