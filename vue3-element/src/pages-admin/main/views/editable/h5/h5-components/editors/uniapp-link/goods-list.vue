<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="商品名称">
          <el-input v-model="searchParams.name" placeholder="请输入商品名称" clearable />
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
        <el-table-column label="商品名称" prop="goods_name" min-width="180" show-overflow-tooltip />

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
import { ref, reactive, onMounted, watch } from 'vue'
import { getTblGoodsPages } from '@/pages-admin/main/api/tbl-goods/tblGoods'
import { usePagination } from '@/hooks/usePagination'
import { formatFileUrl } from '@/utils/util'

// 定义组件名称
defineOptions({
  name: 'GoodsList'
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
  requestFun: getTblGoodsPages,
  searchParams: searchParams
})

// 监听平台变化
watch(() => props.platform, (newVal) => {
  searchParams.platform = newVal
  getTableList()
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
    name: row.name,
    desc: row.description || '',
    price: row.price || '',
    image: row.image || '',
    link: `/pages/${props.platform}/goods/detail?id=${row.id}`
  }

  emit('select', selectData)
}

onMounted(() => {
  getTableList()
})
</script>

<style scoped lang="scss"></style>
