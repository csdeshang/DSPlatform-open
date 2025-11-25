<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="物流公司名称">
                    <el-input v-model="searchParams.name" placeholder="请输入物流公司名称" clearable />
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
                <el-table-column label="ID" prop="id" width="60" />
                <el-table-column label="物流公司名称" prop="name" width="200" />
                <el-table-column label="物流公司代码" prop="code" width="200" />
                <el-table-column label="快递100公司编码" prop="kd100_code" width="200" />
                <el-table-column label="快递鸟公司编码" prop="kdniao_code" width="200" />
                <el-table-column label="物流公司logo" prop="logo" width="200">
                    <template #default="{ row }">
                        <el-image :src="formatImageUrl(row.logo, ThumbnailPresets.small)" width="50px" height="50px" fit="cover" />
                    </template>
                </el-table-column>

                <el-table-column label="物流公司网址" prop="url" />
                <el-table-column label="是否显示" prop="is_show" width="100" >
                    <template #default="{ row }">
                        <el-tag :type="row.is_show == 1 ? 'success' : 'danger'">{{ row.is_show == 1 ? '显示' : '隐藏' }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" width="100" />

                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                        <el-button type="danger" link @click="handleDelete(row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>

        </el-card>


        <ExpressEdit ref="editExpressDialog" @complete="getTableList()" />




    </div>
</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { formatImageUrl, ThumbnailPresets } from '@/utils/image'


import { getSysExpressPages, deleteSysExpress } from '@/pages-admin/main/api/system/sysExpress'

import { usePagination } from '@/hooks/usePagination'


import ExpressEdit from './edit.vue'
import { ElMessageBox } from 'element-plus';


const searchParams = reactive({
    name: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysExpressPages,
    searchParams: searchParams

})
getTableList()


// 编辑和添加
const editExpressDialog: Record<string, any> | null = ref(null)
const handleAdd = () => {
    editExpressDialog.value.setDialogData()
    editExpressDialog.value?.openDialog()
}
const handleEdit = (row: any) => {
    editExpressDialog.value.setDialogData(row);
    editExpressDialog.value?.openDialog()
}


// 删除
const handleDelete = (expressId: number) => {
    ElMessageBox.confirm('您确定是否删除此物流公司', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteSysExpress(expressId).then(() => {
            getTableList()
        }).catch(() => {
        })
    })
}



</script>