<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="页面名称">
          <el-input v-model="searchParams.name" placeholder="请输入页面名称" clearable />
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
        <el-table-column label="ID" prop="id" width="80" />
        <el-table-column label="页面名称" prop="name" min-width="180" show-overflow-tooltip />
        <el-table-column label="描述" prop="desc" min-width="200" show-overflow-tooltip />
        <el-table-column label="更新时间" prop="updateTime" width="180" />
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
import { getEditablePages } from '@/pages-admin/main/api/editable/editable'
import { usePagination } from '@/hooks/usePagination'

// 定义组件名称
defineOptions({
  name: 'DiyList'
})

const emit = defineEmits(['select'])

// 状态管理
const selectedRowId = ref(null)

// 搜索参数
const searchParams = reactive({
  name: ''
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
  requestFun: getEditablePages,
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
    link: `/pages/diy/index?id=${row.id}`
  }

  emit('select', selectData)
}

onMounted(() => {
  getTableList()
})
</script>

<style scoped lang="scss"></style>
