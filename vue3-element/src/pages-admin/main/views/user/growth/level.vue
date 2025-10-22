<template>
    <div>
        <el-alert title="注意：新增或删除成长等级后，所有店铺配置的商品等级折扣会清空，需要重新设置，因为商品等级折扣是根据成长等级设置的" type="warning" :closable="false" show-icon
            style="margin-bottom: 15px;" />
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd">新增等级</el-button>
            </div>
        </el-card>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="等级名称" prop="level_name" min-width="100" />
            <el-table-column label="最小成长值" prop="min_growth" min-width="80" />
            <el-table-column label="描述" prop="description" />
            <el-table-column label="创建时间" prop="create_at" min-width="100" />
            <el-table-column label="更新时间" prop="update_at" min-width="100" />

            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                    <el-button type="primary" link @click="handleDelete(row.id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <LevelForm ref="editUserGrowthLevelDialog" @complete="fetchUserGrowthLevelList()" />

    </div>
</template>


<script lang="ts" setup>
import { ElMessageBox } from 'element-plus'
import { reactive, ref } from 'vue'

import LevelForm from './level-form.vue'
import { getUserGrowthLevelList, deleteUserGrowthLevel } from '@/pages-admin/main/api/user/userGrowthLevel';

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchUserGrowthLevelList = () => {
    tableData.loading = true;
    getUserGrowthLevelList({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchUserGrowthLevelList();


// 新增和编辑
const editUserGrowthLevelDialog: Record<string, any> | null = ref(null)
const handleAdd = () => {
    editUserGrowthLevelDialog.value.setDialogData()
    editUserGrowthLevelDialog.value?.openDialog()
}
const handleEdit = (row: any) => {
    editUserGrowthLevelDialog.value.setDialogData(row);
    editUserGrowthLevelDialog.value?.openDialog()
}

// 删除
const handleDelete = (adminRoleId: number) => {
    ElMessageBox.confirm('您确定是否删除', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteUserGrowthLevel(adminRoleId).then(() => {
            fetchUserGrowthLevelList()
        }).catch(() => {
        })
    })
}


</script>