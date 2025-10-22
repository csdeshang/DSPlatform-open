<template>
    <!-- 退货退款列表 -->

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="商品名">
                <el-input v-model="searchParams.goods_name" placeholder="输入商品名" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="店铺名">
                <el-input v-model="searchParams.store_name" placeholder="请输入店铺名" clearable />
            </el-form-item>
            <el-form-item label="退款单号">
                <el-input v-model="searchParams.out_refund_no" placeholder="请输入退款单号" clearable />
            </el-form-item>
            <el-form-item label="退款类型">
                <el-select v-model="searchParams.refund_type" placeholder="请选择退款类型" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in refund_type_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="退款状态">
                <el-select v-model="searchParams.refund_status" placeholder="请选择退款状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in refund_status_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="退款方式">
                <el-select v-model="searchParams.refund_method" placeholder="请选择退款方式" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in refund_method_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="退款金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.refund_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.refund_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
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
            <el-table-column label="订单ID" prop="order_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleTblOrderDtail(row.order_id)">{{ row.order_id }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="用户" prop="user_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleUserDtail(row.user.id)">{{ row.user.username }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="店铺" prop="store_id" min-width="100">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleStoreDtail(row.store.id)">{{ row.store.store_name }}</el-button>
                </template>
            </el-table-column>
            <el-table-column label="退款单号" prop="out_refund_no" min-width="120" />
            <el-table-column label="商品信息" min-width="200">
                <template #default="{ row }">
                    <div v-if="row.order_goods_id === 0">
                        <el-tag type="danger">全额退款</el-tag>
                    </div>
                    <div v-else>
                        <el-tag type="info">部分退款</el-tag>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="申请金额" prop="apply_amount" min-width="100">
                <template #default="{ row }">
                    ¥{{ row.apply_amount }}
                </template>
            </el-table-column>
            <el-table-column label="实际退款" prop="refund_amount" min-width="100">
                <template #default="{ row }">
                    <span v-if="row.refund_amount > 0">¥{{ row.refund_amount }}</span>
                    <span v-else>-</span>
                </template>
            </el-table-column>
            <el-table-column label="退款类型" prop="refund_type_desc" min-width="100" />
            <el-table-column label="退款方式" prop="refund_method_desc" min-width="100" />
            <el-table-column label="退款状态" prop="refund_status_desc" min-width="100" />
            <el-table-column label="申请时间" prop="create_at" min-width="160" />


            <el-table-column label="操作" align="right" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleRetryRefund(row.id)" v-if="row.refund_status == 60 || row.refund_status == 80">重新发起退款</el-button>
                    <el-button type="primary" link @click="handleRefundDetail(row.id)">详情</el-button>
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

    <tbl-order-refund-detail ref="detailRefundDialog" />



</template>

<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { ElMessage } from 'element-plus';


import { usePagination } from '@/hooks/usePagination'
import { getTblOrderRefundPages, retryTblOrderRefund } from '@/pages-admin/main/api/tbl-order/tblOrderRefund'



const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})

// 当 退款状态  为 60 或者 80 时，可以重新发起退款， 表示订单相关的处理已完成，但是金额未退款
const handleRetryRefund = async (id: number) => {
    const res = await retryTblOrderRefund(id)
    if (res.code == 10000) {
        ElMessage.success('重新发起退款成功')
        getTableList()
    } else {
        ElMessage.error(res.message)
    }
}




const searchParams = reactive({
    platform: props.platform,
    username: '',
    store_name: '',
    goods_name: '',
    out_refund_no: '',
    refund_type: '',
    refund_status: '',
    refund_method: '',
    refund_amount_min: '',
    refund_amount_max: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblOrderRefundPages,
    searchParams: searchParams
})
getTableList()

// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: refund_type_options } = useEnum('default.tbl_order_refund.refund_type')
const { options: refund_status_options } = useEnum('default.tbl_order_refund.refund_status')
const { options: refund_method_options } = useEnum('default.tbl_order_refund.refund_method')

// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (userId: number) => {
    detailUserDialog.value.setDialogData({ id: userId })
    detailUserDialog.value?.openDialog()
}

// 店铺详情弹窗
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
const detailTblStoreDialog = ref()
const handleStoreDtail = (storeId: number) => {
    detailTblStoreDialog.value.setDialogData({ id: storeId })
    detailTblStoreDialog.value?.openDialog()
}

//订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const detailTblOrderDialog = ref()
const handleTblOrderDtail = (orderId: number) => {
    detailTblOrderDialog.value.setDialogData({ id: orderId })
    detailTblOrderDialog.value?.openDialog()
}


// 退款详情
import TblOrderRefundDetail from './detail.vue'
const detailRefundDialog = ref<any>()
const handleRefundDetail = (refundId: number) => {
    detailRefundDialog.value.setDialogData({ id: refundId })
    detailRefundDialog.value?.openDialog()
}




</script>
