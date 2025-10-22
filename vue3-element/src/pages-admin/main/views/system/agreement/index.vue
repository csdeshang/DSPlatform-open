<template>

    <el-card shadow="never">
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="协议名称" prop="title" width="300" />
            <el-table-column label="协议标识" prop="code" width="300" />
            <el-table-column label="是否显示" prop="is_show" width="150">
                <template #default="{ row }">
                    <el-tag v-if="row.is_show == 1">是</el-tag>
                    <el-tag v-else type="danger">否</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" width="150"></el-table-column>
            <el-table-column label="更新时间" prop="update_at" width="200"></el-table-column>
            <el-table-column label="操作" width="200" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">
                        编辑
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>
    <AgreementEdit ref="editAgreementDialog" @complete="fetchSysAgreementList()" />

</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';

import AgreementEdit from './edit.vue'
import { getSysAgreementList } from '@/pages-admin/main/api/system/sysAgreement';



// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载数据
const fetchSysAgreementList = () => {
    tableData.loading = true;
    getSysAgreementList({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化
fetchSysAgreementList();




// 编辑弹窗
const editAgreementDialog: Record<string, any> | null = ref(null)
const handleEdit = (row: any) => {
    editAgreementDialog.value.setDialogData(row);
    editAgreementDialog.value?.openDialog()
}




</script>