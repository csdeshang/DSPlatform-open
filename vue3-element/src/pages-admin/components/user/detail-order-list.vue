<template>
    <!-- 详情页中显示订单列表   根据用户ID 显示订单 显示 TblOrder 表中的数据 -->





    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column label="ID" prop="id" width="60" />
        <el-table-column label="店铺" prop="store.store_name" width="200" />
        <el-table-column label="商品" prop="goods_name">
            <template #default="{ row }">
                <div class="flex flex-col">
                    <div v-for="item in row.orderGoodsList" :key="item.id" class="flex flex-row">
                        <div>
                            <el-image :src="formatFileUrl(item.goods_image)" style="width: 50px; height: 50px;" fit="cover" />
                        </div>
                        <div>{{ item.goods_name }} ({{ item.sku_name }})</div>
                        <div>{{ item.pay_price }} X {{ item.goods_num }}</div>
                    </div>
                </div>
            </template>
        </el-table-column>
        <el-table-column label="支付金额" prop="pay_amount" width="100"></el-table-column>
        <el-table-column label="支付方式" prop="pay_channel" width="100"></el-table-column>
        <el-table-column label="订单状态" prop="order_status_desc" width="100"></el-table-column>
        <el-table-column label="下单时间" prop="add_time" width="100"></el-table-column>
    </el-table>

    <div class="flex justify-end mt-[20px]">
        <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
            layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
            @current-change="getTableList" />
    </div>


</template>


<script lang="ts" setup>

import { reactive, watch } from 'vue';

import { formatFileUrl } from '@/utils/util'


import { usePagination } from '@/hooks/usePagination'
import { getTblOrderPages } from '@/pages-admin/main/api/tbl-order/tblOrder'


const props = defineProps({
    // 用户ID
    user_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    user_id: props.user_id,
    page_size: 10,
})


const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblOrderPages,
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