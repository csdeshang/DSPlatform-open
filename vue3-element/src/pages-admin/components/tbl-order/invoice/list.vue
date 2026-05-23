<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="平台">
          <el-input v-model="searchParams.platform" placeholder="mall/food/house/kms" clearable class="w-[120px]" />
        </el-form-item>
        <el-form-item label="开票状态">
          <el-select v-model="searchParams.invoice_status" placeholder="全部" clearable class="w-[130px]">
            <el-option label="全部" value="" />
            <el-option v-for="opt in invoice_status_options" :key="String(opt.value)" :label="opt.label" :value="String(opt.value)" />
          </el-select>
        </el-form-item>
        <el-form-item label="订单ID">
          <el-input v-model="searchParams.order_id" placeholder="订单ID" clearable class="w-[120px]" />
        </el-form-item>
        <el-form-item label="订单号">
          <el-input v-model="searchParams.order_sn" placeholder="订单号" clearable />
        </el-form-item>
        <el-form-item label="用户ID">
          <el-input v-model="searchParams.user_id" placeholder="用户ID" clearable class="w-[120px]" />
        </el-form-item>
        <el-form-item label="用户名">
          <el-input v-model="searchParams.username" placeholder="用户名" clearable />
        </el-form-item>
        <el-form-item label="店铺ID">
          <el-input v-model="searchParams.store_id" placeholder="店铺ID" clearable class="w-[120px]" />
        </el-form-item>
        <el-form-item label="店铺名">
          <el-input v-model="searchParams.store_name" placeholder="店铺名" clearable />
        </el-form-item>
        <el-form-item label="商户ID">
          <el-input v-model="searchParams.merchant_id" placeholder="商户ID" clearable class="w-[120px]" />
        </el-form-item>
        <el-form-item label="商户名">
          <el-input v-model="searchParams.merchant_name" placeholder="商户名称" clearable />
        </el-form-item>
        <el-form-item label="商品名">
          <el-input v-model="searchParams.goods_name" placeholder="关联订单含该商品" clearable />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="resetPage">查询</el-button>
          <el-button @click="resetSearchParams">重置</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-card shadow="never">
      <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
        <el-table-column label="申请ID" prop="id" min-width="80" />
        <el-table-column label="平台" prop="platform" min-width="80" />
        <el-table-column label="订单ID" prop="order_id" min-width="90">
          <template #default="{ row }">
            <el-button type="primary" link @click="handleOrderDetail(row.order_id)">{{ row.order_id }}</el-button>
          </template>
        </el-table-column>
        <el-table-column label="订单号" prop="order_sn" min-width="130" />
        <el-table-column label="买家" min-width="110">
          <template #default="{ row }">
            <el-button v-if="row.user?.id" type="primary" link @click="handleUserDetail(row.user.id)">
              {{ row.user?.username || row.user?.nickname || '—' }}
            </el-button>
            <span v-else>—</span>
          </template>
        </el-table-column>
        <el-table-column label="店铺" min-width="110">
          <template #default="{ row }">
            <el-button v-if="row.store?.id" type="primary" link @click="handleStoreDetail(row.store.id)">
              {{ row.store?.store_name || '—' }}
            </el-button>
            <span v-else>—</span>
          </template>
        </el-table-column>
        <el-table-column label="金额" min-width="100">
          <template #default="{ row }">¥{{ row.invoice_amount }}</template>
        </el-table-column>
        <el-table-column label="状态" prop="invoice_status_desc" min-width="100" />
        <el-table-column label="申请时间" prop="create_at" min-width="160" />
        <el-table-column label="操作" align="right" fixed="right" width="90">
          <template #default="{ row }">
            <el-button type="primary" link @click="openInvoiceDetail(row.id)">详情</el-button>
          </template>
        </el-table-column>
      </el-table>
      <div class="flex justify-end mt-[20px]">
        <el-pagination
          v-model:current-page="tableData.page_current"
          v-model:page-size="tableData.page_size"
          layout="total, sizes, prev, pager, next, jumper"
          :total="tableData.total"
          @size-change="getTableList()"
          @current-change="getTableList"
        />
      </div>
    </el-card>

    <TblOrderInvoiceDetail ref="invoiceDetailRef" />
    <TblOrderDetail ref="orderDetailRef" />
    <TblStoreDetail ref="storeDetailRef" />
    <UserDetail ref="userDetailRef" />
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'
import { getTblOrderInvoicePages } from '@/pages-admin/main/api/tbl-order/tblOrderInvoice'
import TblOrderInvoiceDetail from './detail.vue'
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
import UserDetail from '@/pages-admin/components/user/detail.vue'

const props = defineProps({
  platform: {
    type: String,
    default: ''
  }
})

const searchParams = reactive({
  platform: (props.platform || '') as string,
  invoice_status: '' as string,
  order_id: '' as string | number,
  order_sn: '',
  username: '',
  user_id: '' as string | number,
  store_id: '' as string | number,
  store_name: '',
  merchant_id: '' as string | number,
  merchant_name: '',
  goods_name: ''
})

watch(
  () => props.platform,
  (v) => {
    searchParams.platform = v || ''
  }
)

const { options: invoice_status_options } = useEnum('default.tbl_order.invoice_status')

const { tableData, getTableList, resetSearchParams, resetPage } = usePagination({
  page_current: 1,
  page_size: 10,
  requestFun: getTblOrderInvoicePages,
  searchParams
})
getTableList()

const invoiceDetailRef = ref<InstanceType<typeof TblOrderInvoiceDetail> | null>(null)
const openInvoiceDetail = async (id: number) => {
  await invoiceDetailRef.value?.setDialogData({ id })
  invoiceDetailRef.value?.openDialog()
}

const orderDetailRef = ref<InstanceType<typeof TblOrderDetail> | null>(null)
const handleOrderDetail = (orderId: number) => {
  orderDetailRef.value?.setDialogData({ id: orderId })
  orderDetailRef.value?.openDialog()
}

const storeDetailRef = ref<InstanceType<typeof TblStoreDetail> | null>(null)
const handleStoreDetail = (storeId: number) => {
  storeDetailRef.value?.setDialogData({ id: storeId })
  storeDetailRef.value?.openDialog()
}

const userDetailRef = ref<InstanceType<typeof UserDetail> | null>(null)
const handleUserDetail = (userId: number) => {
  userDetailRef.value?.setDialogData({ id: userId })
  userDetailRef.value?.openDialog()
}
</script>
