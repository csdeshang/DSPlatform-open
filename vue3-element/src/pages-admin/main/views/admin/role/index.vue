<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd">新增</el-button>
            </div>
        </el-card>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="名称" prop="name" />
            <el-table-column label="描述" prop="desc" />
            <el-table-column label="排序" prop="sort" />
            <el-table-column label="添加时间" prop="create_at" />
            <el-table-column label="更新时间" prop="update_at" />
            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleAuthEdit(row)">分配权限</el-button>
                    <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                    <el-button type="primary" link @click="handleDelete(row.id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <role-form ref="editAdminRoleDialog" @complete="fetchAdminRoleList()" />
        <role-auth-form ref="editAdminRoleAuthDialog" @complete="fetchAdminRoleList()" />
    </div>
</template>


<script lang="ts" setup>
import { ElMessageBox } from 'element-plus'
import { reactive, ref } from 'vue'

import roleForm from './form.vue'
import roleAuthForm from './auth-form.vue'
import { getAdminRoleList, deleteAdminRole } from '@/pages-admin/main/api/admin/adminRole';

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchAdminRoleList = () => {
    tableData.loading = true;
    getAdminRoleList({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchAdminRoleList();


const editAdminRoleDialog: Record<string, any> | null = ref(null)

/**
 * 添加
 */
const handleAdd = (parentId?: number) => {
    editAdminRoleDialog.value.setDialogData()
    editAdminRoleDialog.value?.openDialog()
}

/**
 * 编辑
 */
const handleEdit = (row: any) => {
    editAdminRoleDialog.value.setDialogData(row);
    editAdminRoleDialog.value?.openDialog()
}

/**
 * 删除
 */
const handleDelete = (adminRoleId: number) => {
    ElMessageBox.confirm('您确定是否删除', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteAdminRole(adminRoleId).then(() => {
            fetchAdminRoleList()
        }).catch(() => {
        })
    })
}

// 权限
const editAdminRoleAuthDialog: Record<string, any> | null = ref(null)
/**
 * 编辑
 */
 const handleAuthEdit = (row: any) => {
    editAdminRoleAuthDialog.value.setDialogData(row);
    editAdminRoleAuthDialog.value?.openDialog()
}

</script>