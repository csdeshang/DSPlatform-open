<template>
  <div>
    <el-card shadow="never" class="mb-[10px]">
      <el-form :model="searchParams" inline>
        <el-form-item label="分类名称">
          <el-input v-model="searchParams.name" placeholder="请输入分类名称" clearable />
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
        <el-table-column label="分类名称" prop="name" min-width="180" show-overflow-tooltip />
        <el-table-column label="图标" width="100">
          <template #default="{ row }">
            <el-image v-if="row.image" style="width: 60px; height: 60px" :src="formatImageUrl(row.image, ThumbnailPresets.small)"
              fit="cover" />
            <span v-else>无图片</span>
          </template>
        </el-table-column>
        <el-table-column label="排序" prop="sort" width="80" />
        <el-table-column label="状态" width="80">
          <template #default="{ row }">
            <el-tag :type="row.status === 1 ? 'success' : 'danger'">
              {{ row.status === 1 ? '启用' : '禁用' }}
            </el-tag>
          </template>
        </el-table-column>
      </el-table>

    </el-card>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { getTblStoreCategoryTree } from '@/pages-admin/main/api/tbl-store/tblStoreCategory'
import { usePagination } from '@/hooks/usePagination'
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'

// 定义组件名称
defineOptions({
  name: 'GoodsCategory'
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
  requestFun: getTblStoreCategoryTree,
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
    link: `/pages/${props.platform}/category/list?id=${row.id}`
  }

  emit('select', selectData)
}

onMounted(() => {
  getTableList()
})
</script>

<style scoped lang="scss"></style>
