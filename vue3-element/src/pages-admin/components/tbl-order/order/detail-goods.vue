<template>
    <!-- 详情页中显示订单商品列表   根据订单ID 显示订单商品 显示 TblOrderGoods 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="goods_id" label="商品ID" width="100" />
        <el-table-column prop="goods_name" label="商品名称" width="200" />
        <el-table-column prop="goods_image" label="商品图片">
            <template #default="{ row }">
                <el-image :src="formatFileUrl(row.goods_image)" style="width: 50px; height: 50px;" fit="cover" />
            </template>
        </el-table-column>
        <el-table-column prop="sku_id" label="SKU ID" width="100" />
        <el-table-column prop="sku_name" label="SKU名称" width="150" />
        <el-table-column prop="promotion_price" label="活动价格" width="120" />
        <el-table-column prop="sku_price" label="商品价格" width="120" />
        <el-table-column prop="pay_price" label="实际成交价" width="120" />
        <el-table-column prop="goods_num" label="购买数量" width="100" />
        <el-table-column prop="promotion_platform" label="活动平台" width="150" />
        <el-table-column prop="promotion_type" label="活动类型" width="150" />
        <el-table-column prop="promotion_related_id" label="活动ID" width="100" />
    </el-table>

</template>


<script lang="ts" setup>

import { formatFileUrl } from '@/utils/util'

import { getTblOrderGoodsList } from '@/pages-admin/main/api/tbl-order/tblOrder'
import { reactive, watch } from 'vue';


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
const fetchTblOrderGoodsList = () => {
    tableData.loading = true;
    getTblOrderGoodsList(searchParams).then(res => {
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
        fetchTblOrderGoodsList(); // 重新获取数据
    }
);


</script>