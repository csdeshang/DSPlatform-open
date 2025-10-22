<template>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" width="60" />
            <el-table-column label="应用名称" prop="name" width="200" />
            <el-table-column label="唯一标识" prop="platform" width="200" />
            <el-table-column label="应用场景" prop="scene" width="200" />

            <el-table-column label="版本" prop="version" width="100" />
            <el-table-column label="描述" prop="description" width="200" />
            <el-table-column label="排序" prop="sort" width="100" />
            <el-table-column label="是否启用" prop="is_enable" width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.is_enable" type="success">启用</el-tag>
                    <el-tag v-else type="danger">禁用</el-tag>
                </template>
            </el-table-column>


            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="danger" link @click="handleEnable(row)">{{ row.is_enable ? '禁用' : '启用'
                        }}</el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>
</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getSysPlatformList, updateSysPlatform } from '@/pages-admin/main/api/system/SysPlatform'

import { ElMessageBox } from 'element-plus';


// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchSysPlatformList = () => {
    tableData.loading = true;
    getSysPlatformList({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchSysPlatformList();





// 是否启用
const handleEnable = (row: any) => {
    ElMessageBox.confirm(`您确定是否${row.is_enable ? '禁用' : '启用'}此平台`, 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        updateSysPlatform({ id: row.id, is_enable: !row.is_enable }).then(() => {
            fetchSysPlatformList()
        }).catch(() => {
        })
    })
}



</script>