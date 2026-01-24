<template>
  <div>
    <el-card shadow="never">
      <el-table 
        :data="tableData.data" 
        size="large" 
        v-loading="tableData.loading" 
        row-key="id"
        :tree-props="{ children: 'children', hasChildren: 'hasChildren' }"
        @row-click="handleRowClick">
        <el-table-column type="selection" width="55">
          <template #default="{ row }">
            <el-radio v-model="selectedRowId" :label="row.id" @change="handleSelect(row)">
            </el-radio>
          </template>
        </el-table-column>
        <el-table-column label="分类名称" prop="name" min-width="180" show-overflow-tooltip />
        <el-table-column label="是否显示" prop="is_show" width="100">
          <template #default="{ row }">
            <el-tag v-if="row.is_show == 1" type="success">是</el-tag>
            <el-tag v-else type="danger">否</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="排序" prop="sort" width="80" />
      </el-table>
    </el-card>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { getTblStoreCategoryTree } from '@/pages-admin/main/api/tbl-store/tblStoreCategory'

// 定义组件名称
defineOptions({
  name: 'StoreCategory'
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
const selectedRowId = ref<number | null>(null)

// 搜索参数
const searchParams = reactive({
  platform: props.platform
})

// 数据
const tableData = reactive({
  loading: true,
  data: []
})

// 加载列表
const fetchTblStoreCategoryTree = () => {
  tableData.loading = true
  getTblStoreCategoryTree({
    ...searchParams
  }).then(res => {
    tableData.loading = false
    tableData.data = res.data || []
  }).catch(() => {
    tableData.loading = false
    tableData.data = []
  })
}

// 监听 platform 变化
watch(
  () => props.platform,
  (newPlatform) => {
    searchParams.platform = newPlatform
    fetchTblStoreCategoryTree()
  },
  { immediate: false }
)

// 初始化列表
fetchTblStoreCategoryTree()

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
    link: `/home/platform/${props.platform}/pages/search/storelist/index?category_id=${row.id}`
  }

  emit('select', selectData)
}
</script>

<style scoped lang="scss"></style>

