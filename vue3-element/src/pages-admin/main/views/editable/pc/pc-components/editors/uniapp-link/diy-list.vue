<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="标题">
          <el-input v-model="searchParams.title" placeholder="请输入标题" clearable />
        </el-form-item>
        <el-form-item label="设备类型" style="width:200px;">
          <el-select v-model="searchParams.device_type" placeholder="请选择设备类型" clearable>
            <el-option label="手机端" value="mobile" />
            <el-option label="PC端" value="pc" />
          </el-select>
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
        <el-table-column label="标题" prop="title" min-width="140" show-overflow-tooltip />
        <el-table-column label="页面类型" prop="type_desc" width="100" />
        <el-table-column label="平台" prop="platform.name" width="100" />
        <el-table-column label="设备类型" width="90">
          <template #default="{ row }">
            <el-tag v-if="row.device_type === 'pc'" type="success" size="small">PC端</el-tag>
            <el-tag v-else-if="row.device_type === 'mobile'" type="primary" size="small">手机端</el-tag>
            <span v-else>-</span>
          </template>
        </el-table-column>
        <el-table-column label="更新时间" prop="update_at" width="180" />
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

const searchParams = reactive({
  title: '',
  device_type: '' as string
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
    title: row.title,
    link: `/diy?id=${row.id}`
  }

  emit('select', selectData)
}

onMounted(() => {
  getTableList()
})
</script>

<style scoped lang="scss"></style>
