<template>
    <!-- 详情页中显示订单商品列表   根据订单ID 显示订单商品 显示 TblOrderGoods 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="order_id" label="订单ID" width="100" />
        <el-table-column prop="sku_id" label="SKU ID" width="100" />
        <el-table-column prop="sku_name" label="SKU名称" width="150" />
        <el-table-column prop="sku_price" label="商品价格" width="120" />
        <el-table-column prop="promotion_price" label="活动价格" width="120" />
        <el-table-column prop="pay_price" label="实际成交价" width="120" />
        <el-table-column prop="goods_num" label="购买数量" width="100" />
        <el-table-column prop="promotion_platform" label="活动平台" width="150" />
        <el-table-column prop="promotion_type" label="活动类型" width="150" />
        <el-table-column prop="promotion_related_id" label="活动ID" width="100" />


    </el-table>

    <div class="flex justify-end mt-[20px]">
        <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
            layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
            @current-change="getTableList" />
    </div>

</template>


<script lang="ts" setup>

import { getTblOrderGoodsPages } from '@/pages-admin/main/api/tbl-order/tblOrder'
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
    requestFun: getTblOrderGoodsPages,
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