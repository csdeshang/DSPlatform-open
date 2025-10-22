<template>
    <!-- 公共商品列表 显示 TblOrder 表中的数据 -->


    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="商品名">
                <el-input v-model="searchParams.goods_name" placeholder="输入商品名" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="商户名">
                <el-input v-model="searchParams.merchant_name" placeholder="请输入商户名" clearable />
            </el-form-item>
            <el-form-item label="店铺名">
                <el-input v-model="searchParams.store_name" placeholder="请输入店铺名" clearable />
            </el-form-item>
            <el-form-item label="订单号">
                <el-input v-model="searchParams.order_sn" placeholder="请输入订单号" clearable />
            </el-form-item>
            <el-form-item label="支付单号">
                <el-input v-model="searchParams.out_trade_no" placeholder="请输入支付单号" clearable />
            </el-form-item>
            <el-form-item label="交易号">
                <el-input v-model="searchParams.trade_no" placeholder="请输入交易号" clearable />
            </el-form-item>
            <el-form-item label="交付方式">
                <el-select v-model="searchParams.delivery_method" placeholder="请选择交付方式" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in delivery_method_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="订单状态">
                <el-select v-model="searchParams.order_status" placeholder="请选择订单状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in order_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="是否评价">
                <el-select v-model="searchParams.is_evaluate" placeholder="请选择是否评价" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option label="未评价" value="0" />
                    <el-option label="已评价" value="1" />
                </el-select>
            </el-form-item>
            <el-form-item label="退款状态">
                <el-select v-model="searchParams.refund_status" placeholder="请选择退款状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in refund_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="是否退款中">
                <el-select v-model="searchParams.is_refunding" placeholder="请选择是否退款中" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option label="否" value="0" />
                    <el-option label="是" value="1" />
                </el-select>
            </el-form-item>
            <el-form-item label="支付金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.pay_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.pay_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" width="60" />
            <el-table-column label="订单号" prop="order_sn" width="200" />
            <el-table-column label="购买用户" prop="user_id" width="100">
                <template #default="{ row }">
                    <div class="text-blue-500 cursor-pointer" @click="handleUserDtail(row.user.id)">{{
                        row.user.username }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="店铺" prop="store_id" width="200">
                <template #default="{ row }">
                    <div class="text-blue-500 cursor-pointer" @click="handleStoreDtail(row.store.id)">{{
                        row.store.store_name }}</div>
                </template>
            </el-table-column>

            <el-table-column label="商品" prop="goods_name" width="450">
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
            <el-table-column label="下单时间" prop="add_time" width="200"></el-table-column>


            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleTblOrderDtail(row.id)">详情</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>

    <tbl-order-detail ref="detailTblOrderDialog" />
    <tbl-store-detail ref="detailTblStoreDialog" />
    <user-detail ref="detailUserDialog" />




</template>

<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { formatFileUrl } from '@/utils/util'
import { useRouter } from 'vue-router'
const router = useRouter()

import { usePagination } from '@/hooks/usePagination'
import { getTblOrderPages } from '@/pages-admin/main/api/tbl-order/tblOrder'



const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})




const searchParams = reactive({
    platform: props.platform,
    username: '',
    merchant_name: '',
    store_name: '',
    goods_name: '',
    order_sn: '',
    out_trade_no: '',
    trade_no: '',
    delivery_method: '',
    order_status: '',
    is_evaluate: '',
    refund_status: '',
    is_refunding: '',
    pay_amount_min: '',
    pay_amount_max: ''
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
getTableList()

// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: delivery_method_options } = useEnum('default.tbl_order.delivery_method')
const { options: order_status_options } = useEnum('default.tbl_order.order_status')
const { options: refund_status_options } = useEnum('default.tbl_order.refund_status')

// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}

// 店铺详情弹窗
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
const detailTblStoreDialog = ref()
const handleStoreDtail = (store_id: any) => {
    detailTblStoreDialog.value.setDialogData({ id: store_id })
    detailTblStoreDialog.value?.openDialog()
}

//订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const detailTblOrderDialog = ref()
const handleTblOrderDtail = (order_id: any) => {
    detailTblOrderDialog.value.setDialogData({ id: order_id })
    detailTblOrderDialog.value?.openDialog()
}





</script>
