<template>



  <el-card shadow="never" class="mb-[10px]">
    <el-form :model="searchParams" inline>
      <el-form-item label="标题">
        <el-input v-model="searchParams.title" placeholder="请输入标题" clearable />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="resetPage">查询</el-button>
        <el-button @click="resetSearchParams">重置</el-button>
      </el-form-item>
    </el-form>
    <el-button type="primary" @click="handleAdd()">添加</el-button>
  </el-card>


  <el-card shadow="never">
    <el-tabs v-model="searchParams.platform" @tab-change="handleTabChange">
      <el-tab-pane v-for="item in platformList" :key="item.id" :label="item.name" :name="item.platform">
      </el-tab-pane>
    </el-tabs>

    <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
      <el-table-column label="ID" prop="id" min-width="60" />
      <el-table-column label="标题" prop="title" />
      <el-table-column label="平台" prop="platform.name" />
      <el-table-column label="页面类型" prop="type_desc" />
      <el-table-column label="创建时间" prop="create_time" />
      <el-table-column label="操作" align="right" fixed="right" width="130">
        <template #default="{ row }">
          <el-button type="primary" link @click="handleEdit(row)">装修</el-button>
          <el-button type="primary" link @click="handleDelete(row.id)">删除</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="flex justify-end mt-[20px]">
      <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
        layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
        @current-change="getTableList" />
    </div>
  </el-card>


  <!-- 添加页面 -->
  <EditablePageAdd ref="editablePageAddRef" @complete="getTableList" />




</template>




<script setup lang="ts">
import { reactive, ref } from 'vue';

import { useRouter } from 'vue-router';

import { ElMessage, ElMessageBox } from 'element-plus';
import { getEditablePages, deleteEditablePage } from '@/pages-admin/main/api/editable/editable'
import EditablePageAdd from './add.vue'

import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'

const router = useRouter();

import { usePagination } from '@/hooks/usePagination'




const searchParams = reactive({
  title: '',
  platform: 'system'
})


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
getTableList()


// 添加页面
const editablePageAddRef: Record<string, any> | null = ref(null)
const handleAdd = () => {
  editablePageAddRef.value.setDialogData()
  editablePageAddRef.value?.openDialog()
}


// 编辑页面
const handleEdit = (row: any) => {

  router.push(`/admin/editable/edit?id=${row.id}`)

  // window.open(`/admin/editable/edit?id=${row.id}`, '_blank'); // 在新标签页中打开
}


/**
 * 删除
 */
const handleDelete = (id: number) => {
  ElMessageBox.confirm('您确定是否删除此分类', 'Warning',
    {
      confirmButtonText: '确认',
      cancelButtonText: '取消',
      type: 'warning'
    }
  ).then(() => {
    deleteEditablePage(id).then(() => {
      getTableList()
    }).catch(() => {
    })
  })
}



// 获取平台列表
const platformList = ref<any[]>([])
const fetchPlatformList = async () => {
    try {
        const response = await getSysPlatformList({});
        platformList.value = response.data
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
};
fetchPlatformList()


// 平台切换
const handleTabChange = (val: string) => {
  searchParams.platform = val
  getTableList()
}




</script>