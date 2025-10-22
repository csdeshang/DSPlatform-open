<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="根目录">
                <el-input v-model="searchParams.root" placeholder="请输入根目录" clearable />
            </el-form-item>
            <el-form-item label="控制器">
                <el-input v-model="searchParams.controller" placeholder="请输入控制器名称" clearable />
            </el-form-item>
            <el-form-item label="IP地址">
                <el-input v-model="searchParams.ip" placeholder="请输入IP地址" clearable />
            </el-form-item>
            <el-form-item label="错误代码">
                <el-input v-model="searchParams.code" placeholder="请输入错误代码" clearable />
            </el-form-item>
            <el-form-item label="请求耗时">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.duration_min" placeholder="最小耗时(毫秒)" clearable class="w-[140px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.duration_max" placeholder="最大耗时(毫秒)" clearable class="w-[140px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" width="120" />
            <el-table-column label="IP" prop="ip" width="200" />
            <el-table-column label="访问类型" prop="method" width="120" />
            <el-table-column label="控制器" prop="controller" width="120" />
            <el-table-column label="方法" prop="action" width="120" />
            <el-table-column label="请求耗时(毫秒)" prop="duration" width="100" />
            <el-table-column label="返回CODE" prop="code" width="100" />
            <el-table-column label="操作时间" prop="create_at" width="200" />
            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleView(row.id)">详情</el-button>
                </template>
            </el-table-column>
        </el-table>


        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>


    <sys-error-logs-detail ref="sysErrorLogsDetailDialog" />




</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getSysErrorLogsPages } from '@/pages-admin/main/api/system/sysErrorLogs'

import { usePagination } from '@/hooks/usePagination'

import SysErrorLogsDetail from './detail.vue'



const searchParams = reactive({
    controller: '',
    root: '',
    ip: '',
    code: '',
    duration_min: '',
    duration_max: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysErrorLogsPages,
    searchParams: searchParams

})
getTableList()

const sysErrorLogsDetailDialog: Record<string, any> | null = ref(null)


const handleView = (id: any) => {
    sysErrorLogsDetailDialog.value.setDialogData(id)
    sysErrorLogsDetailDialog.value?.openDialog()
}




</script>