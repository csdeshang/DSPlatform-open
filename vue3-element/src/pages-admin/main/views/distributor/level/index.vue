<template>
    <div>
        <el-alert title="注意：新增或删除分销等级后，所有单独设置的商品分销佣金将会被清空，需要重新设置，因为分销佣金是根据分销等级设置的" type="warning" :closable="false" show-icon
            style="margin-bottom: 15px;" />
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd">新增等级</el-button>
            </div>
        </el-card>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="等级名称" prop="name" min-width="100" />
            <el-table-column label="级别排序" prop="sort" min-width="80" />
            <el-table-column label="等级描述" prop="description" />
            <el-table-column label="自购佣金比例" prop="base_self_ratio" min-width="80" />
            <el-table-column label="1级佣金比例" prop="base_parent1_ratio" min-width="80" />
            <el-table-column label="2级佣金比例" prop="base_parent2_ratio" min-width="80" />
            <el-table-column label="是否默认" prop="is_default" min-width="80" />

            <el-table-column label="更新时间" prop="update_at" min-width="100" />

            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                    <el-button type="primary" link @click="handleDelete(row.id)" v-if="!row.is_default">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <LevelForm ref="editDistributorLevelDialog" @complete="fetchDistributorLevelList()" />

    </div>
</template>


<script lang="ts" setup>
import { ElMessageBox } from 'element-plus'
import { reactive, ref } from 'vue'

import LevelForm from './level-form.vue'
import { getDistributorLevelList, deleteDistributorLevel } from '@/pages-admin/main/api/distributor/distributorLevel';

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchDistributorLevelList = () => {
    tableData.loading = true;
    getDistributorLevelList({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchDistributorLevelList();


// 新增和编辑
const editDistributorLevelDialog: Record<string, any> | null = ref(null)
const handleAdd = () => {
    editDistributorLevelDialog.value.setDialogData()
    editDistributorLevelDialog.value?.openDialog()
}
const handleEdit = (row: any) => {
    editDistributorLevelDialog.value.setDialogData(row);
    editDistributorLevelDialog.value?.openDialog()
}

// 删除
const handleDelete = (id: number) => {
    ElMessageBox.confirm('您确定是否删除', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteDistributorLevel(id).then(() => {
            fetchDistributorLevelList()
        }).catch(() => {
        })
    })
}


</script>