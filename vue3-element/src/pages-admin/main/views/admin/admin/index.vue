<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="管理员账号">
                    <el-input v-model="searchParams.username" placeholder="请输入管理员账号" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
            <el-button type="primary" @click="handleAdd()">添加</el-button>
        </el-card>

        <el-card shadow="never">

            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" min-width="60" />
                <el-table-column label="账号" prop="username" />
                <el-table-column label="登录次数" prop="login_num" />
                <el-table-column label="上次登录时间" prop="login_time" />
                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                        <el-button type="primary" link @click="handleDelete(row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>

        
        <admin-form ref="editAdminDialog" @complete="getTableList()" />

    </div>
</template>
<script lang="ts" setup>
import { ElMessageBox } from 'element-plus';
import { reactive, ref } from 'vue';
import { getAdminPages, deleteAdmin } from '@/pages-admin/main/api/admin/admin'
import adminForm from './edit.vue'

import { usePagination } from '@/hooks/usePagination'


const searchParams = reactive({
    username: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getAdminPages,
    searchParams: searchParams
})
getTableList()


// 新增 编辑
const editAdminDialog: Record<string, any> | null = ref(null)
const handleAdd = () => {
    editAdminDialog.value.setDialogData()
    editAdminDialog.value?.openDialog()
}
const handleEdit = (data: any) => {
    editAdminDialog.value.setDialogData(data)
    editAdminDialog.value?.openDialog()
}

/**
 * 删除
 */
const handleDelete = (id: number) => {
    ElMessageBox.confirm('您确定是否删除此管理员', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteAdmin(id).then(() => {
            getTableList()
        }).catch(() => {
        })
    })
}

</script>