<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="店铺名称">
                    <el-input v-model="searchParams.store_name" placeholder="店铺名称" clearable />
                </el-form-item>
                <el-form-item label="打印机名称">
                    <el-input v-model="searchParams.printer_name" placeholder="打印机名称" clearable />
                </el-form-item>
                <el-form-item label="服务商">
                    <el-select v-model="searchParams.printer_provider" placeholder="选择服务商" clearable style="width: 100px;">
                        <el-option v-for="item in printer_provider_options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" min-width="60" />
                <el-table-column label="店铺信息" min-width="200">
                    <template #default="{ row }">
                        <div v-if="row.store">
                            <div class="font-medium">{{ row.store.store_name }}</div>
                            <div class="text-gray-500 text-sm">ID: {{ row.store.id }}</div>
                        </div>
                        <span v-else class="text-gray-400">-</span>
                    </template>
                </el-table-column>
                <el-table-column label="打印机名称" prop="printer_name" min-width="120" />
                <el-table-column label="打印机序列号" prop="printer_sn" min-width="120" />
                <el-table-column label="服务商" prop="printer_provider_desc" min-width="80" />
                <el-table-column label="绑定状态" prop="printer_status" min-width="80">
                    <template #default="{ row }">
                        <el-tag :type="row.printer_status == 1 ? 'success' : 'danger'">
                            {{ row.printer_status == 1 ? '已绑定' : '未绑定' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="启用状态" prop="is_enabled" min-width="80">
                    <template #default="{ row }">
                        <el-tag :type="row.is_enabled == 1 ? 'success' : 'info'">
                            {{ row.is_enabled == 1 ? '启用' : '禁用' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" min-width="160" />
                <el-table-column label="更新时间" prop="update_at" min-width="160" />
                <el-table-column label="操作" align="right" fixed="right" width="100">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleLogs(row)">打印日志</el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination 
                    v-model:current-page="tableData.page_current" 
                    v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="tableData.total" 
                    @size-change="getTableList()"
                    @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 打印日志弹窗 -->
        <printer-logs-dialog ref="logsDialog" @complete="getTableList()" />
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { getTblStorePrinterPages } from '@/pages-admin/main/api/tbl-store/tblStorePrinter'
import { usePagination } from '@/hooks/usePagination'

// 打印日志弹窗
import PrinterLogsDialog from './logs-dialog.vue'

const searchParams = reactive({
    store_name: '',
    printer_name: '',
    printer_provider: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblStorePrinterPages,
    searchParams: searchParams
})

// 初始化加载数据
getTableList()



import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: printer_provider_options, } = useEnum('default.tbl_store_printer.printer_provider')


// 打印日志
const logsDialog = ref()
const handleLogs = (row: any) => {
    logsDialog.value.setDialogData({ printer_id: row.id, printer_name: row.printer_name })
    logsDialog.value?.openDialog()
}
</script>
