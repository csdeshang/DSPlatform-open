<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="优惠券名称">
          <el-input v-model="searchParams.name" placeholder="请输入优惠券名称" clearable />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="resetPage">查询</el-button>
          <el-button @click="resetSearchParams">重置</el-button>
        </el-form-item>
      </el-form>
    </el-card>

    <el-card shadow="never">
      <el-table :data="tableData.data" size="large" v-loading="tableData.loading" @row-click="handleRowClick">
        <el-table-column type="selection" width="55">
          <template #default="{ row }">
            <el-radio v-model="selectedRowId" :label="row.id" @change="handleSelect(row)">
            </el-radio>
          </template>
        </el-table-column>
        <el-table-column label="优惠券名称" prop="name" min-width="180" show-overflow-tooltip />
        <el-table-column label="面额" width="100">
          <template #default="{ row }">
            <span class="text-red-500">￥{{ row.amount }}</span>
          </template>
        </el-table-column>
        <el-table-column label="使用条件" prop="useCondition" min-width="150" show-overflow-tooltip />
        <el-table-column label="有效期" width="180" show-overflow-tooltip>
          <template #default="{ row }">
            {{ row.startTime }} 至 {{ row.endTime }}
          </template>
        </el-table-column>
      </el-table>

      <div class="flex justify-end mt-[20px]">
        <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
          layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
          @size-change="getTableList()" @current-change="getTableList" />
      </div>
    </el-card>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { getTblStoreCouponPages } from '@/pages-admin/main/api/tbl-store/tblStoreCoupon'
import { usePagination } from '@/hooks/usePagination'

// 定义组件名称
defineOptions({
  name: 'CouponList'
})

// 定义Props和事件
const props = defineProps({
  platform: {
    type: String,
    default: 'mall'
  }
})

const emit = defineEmits(['select'])

// 状态管理
const selectedRowId = ref(null)

// 搜索参数
const searchParams = reactive({
  name: '',
  platform: props.platform
})

// 使用分页钩子
const {
  tableData,
  getTableList,
  resetSearchParams,
  resetPage
} = usePagination({
  page_current: 1,
  page_size: 20,
  requestFun: getTblStoreCouponPages,
  searchParams: searchParams
})

// 行点击事件处理
const handleRowClick = (row: any) => {
  selectedRowId.value = row.id
  handleSelect(row)
}

// 选择处理
const handleSelect = (row: any) => {
  // 构建发送到父组件的数据结构
  const selectData = {
    id: row.id,
    link: `/pages/coupon/detail?id=${row.id}`
  }

  emit('select', selectData)
}

onMounted(() => {
  getTableList()
})
</script>

<style scoped lang="scss"></style>
