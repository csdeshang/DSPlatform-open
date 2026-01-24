<template>



  <el-card shadow="never" class="mb-[10px]">
    <el-form :model="searchParams" :rules="formRules" ref="searchFormRef" inline>
      <el-form-item label="标题">
        <el-input v-model="searchParams.title" placeholder="请输入标题" clearable />
      </el-form-item>
      <el-form-item label="设备类型" prop="device_type" style="width:200px;">
        <el-select v-model="searchParams.device_type" placeholder="请选择设备类型">
          <el-option label="手机端" value="mobile" />
          <el-option label="PC端" value="pc" />
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleSearch">查询</el-button>
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
      <el-table-column label="设备类型" prop="device_type" min-width="100">
        <template #default="{ row }">
          <el-tag v-if="row.device_type === 'pc'" type="success">PC端</el-tag>
          <el-tag v-else-if="row.device_type === 'mobile'" type="primary">手机端</el-tag>
          <span v-else>-</span>
        </template>
      </el-table-column>
      <el-table-column label="创建时间" prop="create_at" />
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
import type { FormInstance, FormRules } from 'element-plus';
import { getEditablePages, deleteEditablePage } from '@/pages-admin/main/api/editable/editable'
import EditablePageAdd from './add.vue'

import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'

const router = useRouter();

import { usePagination } from '@/hooks/usePagination'

// 表单引用
const searchFormRef = ref<FormInstance>()

// 表单验证规则
const formRules: FormRules = {
  device_type: [
    { required: true, message: '请选择设备类型', trigger: 'change' }
  ]
}

const searchParams = reactive({
  title: '',
  platform: 'system',
  device_type: 'mobile' // 默认值为手机端
})


const {
  tableData,
  getTableList,
  resetSearchParams: originalResetSearchParams,
  resetPage
} = usePagination({
  page_current: 1,
  page_size: 20,
  requestFun: getEditablePages,
  searchParams: searchParams
})

// 重写重置搜索参数方法，确保 device_type 重置为 'mobile'
const resetSearchParams = () => {
  searchParams.title = ''
  searchParams.platform = 'system'
  searchParams.device_type = 'mobile'
  originalResetSearchParams()
  // 清除表单验证
  searchFormRef.value?.clearValidate()
}

// 处理搜索，添加表单验证
const handleSearch = async () => {
  if (!searchFormRef.value) return
  
  try {
    await searchFormRef.value.validate()
    resetPage()
  } catch (error) {
    ElMessage.warning('请选择设备类型')
  }
}

// 初始化时加载数据
getTableList()


// 添加页面
const editablePageAddRef: Record<string, any> | null = ref(null)
const handleAdd = () => {
  editablePageAddRef.value.setDialogData()
  editablePageAddRef.value?.openDialog()
}


// 编辑页面
const handleEdit = (row: any) => {
  // 根据 device_type 决定跳转到 PC 还是 H5 编辑页面
  const deviceType = row.device_type || 'mobile' // 默认为手机端
  const routePath = deviceType === 'pc' 
    ? `/admin/editable/pc/edit?id=${row.id}` 
    : `/admin/editable/h5/edit?id=${row.id}`
  
  router.push(routePath)

  // window.open(routePath, '_blank'); // 在新标签页中打开
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