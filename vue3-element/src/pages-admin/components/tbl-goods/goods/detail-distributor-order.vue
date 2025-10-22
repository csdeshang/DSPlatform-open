<template>


    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column label="商品ID" prop="goods_id" min-width="100" />
        <el-table-column label="佣金类型" prop="commission_type_desc" min-width="120" />
        <el-table-column label="实付金额" prop="pay_price" min-width="120" />
        <el-table-column label="佣金" prop="commission_amount" min-width="120" />
        <el-table-column label="佣金状态" prop="commission_status_desc" min-width="120" />
        <el-table-column label="备注" prop="commission_remark" min-width="300" />


        <el-table-column label="分销商ID" prop="distributor_user_id" min-width="100">

        </el-table-column>
        <el-table-column label="分销商等级" prop="distributor_level_id" min-width="100" />
        <el-table-column label="购买者ID" prop="user_id" min-width="100">

        </el-table-column>
        <el-table-column label="店铺ID" prop="store_id" min-width="100">

        </el-table-column>
        <el-table-column label="订单ID" prop="order_id" min-width="100">

        </el-table-column>


    </el-table>

    <div class="flex justify-end mt-[20px]">
        <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
            layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
            @current-change="getTableList" />
    </div>

</template>


<script lang="ts" setup>

import { getDistributorOrderPages } from '@/pages-admin/main/api/distributor/distributorOrder'
import { reactive, watch } from 'vue';

import { usePagination } from '@/hooks/usePagination'


const props = defineProps({
    // 商品ID
    goods_id: {
        type: Number,
        default: 0
    },
})



const searchParams = reactive({
    goods_id: props.goods_id,
})


const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getDistributorOrderPages,
    searchParams: searchParams
})





// 监听 goods_id 的变化并更新 searchParams
watch(
    () => props.goods_id,
    (newGoodsId) => {
        searchParams.goods_id = newGoodsId;
        getTableList(); // 重新获取数据
    }
);


</script>