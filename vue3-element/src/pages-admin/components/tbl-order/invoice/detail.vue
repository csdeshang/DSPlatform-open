<template>
  <el-drawer v-model="visible" title="开票申请详情（只读）" size="60%" @closed="onClosed">
    <div v-loading="loading" class="ds-detail">
      <template v-if="info.id">
        <el-descriptions :column="2" border class="mb-4">
          <el-descriptions-item label="申请ID">{{ info.id }}</el-descriptions-item>
          <el-descriptions-item label="平台">{{ info.platform }}</el-descriptions-item>
          <el-descriptions-item label="订单ID">{{ info.order_id }}</el-descriptions-item>
          <el-descriptions-item label="订单号">{{ info.order_sn }}</el-descriptions-item>
          <el-descriptions-item label="商户ID">{{ info.merchant_id }}</el-descriptions-item>
          <el-descriptions-item label="店铺ID">{{ info.store_id }}</el-descriptions-item>
          <el-descriptions-item label="状态">{{ info.invoice_status_desc }}</el-descriptions-item>
          <el-descriptions-item label="开票金额">¥{{ info.invoice_amount }}</el-descriptions-item>
          <el-descriptions-item label="抬头类型">{{ info.invoice_type === 2 ? '企业' : '个人' }}</el-descriptions-item>
          <el-descriptions-item label="发票种类">{{ info.invoice_kind === 2 ? '增值税专票' : '普通发票' }}</el-descriptions-item>
          <el-descriptions-item label="发票抬头" :span="2">{{ info.invoice_title }}</el-descriptions-item>
          <el-descriptions-item label="税号" v-if="info.tax_number">{{ info.tax_number }}</el-descriptions-item>
          <el-descriptions-item label="收票邮箱" v-if="info.receiver_email">{{ info.receiver_email }}</el-descriptions-item>
          <el-descriptions-item label="收票手机" v-if="info.receiver_mobile">{{ info.receiver_mobile }}</el-descriptions-item>
          <el-descriptions-item label="申请说明" :span="2" v-if="info.apply_remark">{{ info.apply_remark }}</el-descriptions-item>
          <el-descriptions-item label="发票号码" v-if="info.invoice_no">{{ info.invoice_no }}</el-descriptions-item>
          <el-descriptions-item label="外部流水号" v-if="info.out_invoice_no">{{ info.out_invoice_no }}</el-descriptions-item>
          <el-descriptions-item label="下载地址" :span="2" v-if="info.invoice_file_url">{{ info.invoice_file_url }}</el-descriptions-item>
          <el-descriptions-item label="完成说明" :span="2" v-if="info.issue_remark">{{ info.issue_remark }}</el-descriptions-item>
          <el-descriptions-item label="开票完成时间" v-if="info.issue_time">{{ info.issue_time }}</el-descriptions-item>
          <el-descriptions-item label="驳回原因" :span="2" v-if="info.reject_reason">{{ info.reject_reason }}</el-descriptions-item>
          <el-descriptions-item label="驳回时间" v-if="info.reject_time">{{ info.reject_time }}</el-descriptions-item>
          <el-descriptions-item label="作废原因" :span="2" v-if="info.void_reason">{{ info.void_reason }}</el-descriptions-item>
          <el-descriptions-item label="作废时间" v-if="info.void_time">{{ info.void_time }}</el-descriptions-item>
          <el-descriptions-item label="申请时间">{{ info.create_at }}</el-descriptions-item>
          <el-descriptions-item label="更新时间">{{ info.update_at || '—' }}</el-descriptions-item>
        </el-descriptions>
        <div class="section-bd-block-title mb-2">处理记录</div>
        <el-timeline v-if="(info.orderInvoiceLogList || []).length">
          <el-timeline-item
            v-for="(log, idx) in info.orderInvoiceLogList"
            :key="idx"
            :timestamp="log.create_at || ''"
          >
            <div>{{ log.invoice_status_desc }}</div>
            <div class="text-gray-500 text-sm">{{ log.message }}</div>
          </el-timeline-item>
        </el-timeline>
        <el-empty v-else description="暂无记录" />
      </template>
    </div>
  </el-drawer>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { ElMessage } from 'element-plus'
import { getTblOrderInvoiceInfo } from '@/pages-admin/main/api/tbl-order/tblOrderInvoice'

const visible = ref(false)
const loading = ref(false)
const info = reactive<Record<string, any>>({})

const resetInfo = () => {
  Object.keys(info).forEach((k) => delete (info as any)[k])
}

const load = async (id: number) => {
  if (!id) return
  loading.value = true
  try {
    const res = await getTblOrderInvoiceInfo(id)
    if (res.code === 10000 && res.data) {
      resetInfo()
      Object.assign(info, res.data)
    } else {
      ElMessage.error(res.message || '加载失败')
    }
  } finally {
    loading.value = false
  }
}

const setDialogData = async (row: { id: number }) => {
  await load(row.id)
}

const openDialog = () => {
  visible.value = true
}

const onClosed = () => {
  resetInfo()
}

defineExpose({ setDialogData, openDialog })
</script>

<style scoped>
.mb-2 {
  margin-bottom: 8px;
}
.mb-4 {
  margin-bottom: 16px;
}
.text-sm {
  font-size: 12px;
}
.text-gray-500 {
  color: var(--el-text-color-secondary);
}
</style>
