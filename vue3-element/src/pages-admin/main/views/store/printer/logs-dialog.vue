<template>
    <el-dialog v-model="dialogVisible" title="打印日志" width="1000px" :before-close="handleClose">
        <div v-loading="loading">
            <!-- 搜索表单 -->
            <el-form :model="searchParams" inline class="mb-4">
                <el-form-item label="订单ID">
                    <el-input v-model="searchParams.order_id" placeholder="订单ID" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="getTableList">查询</el-button>
                    <el-button @click="resetSearchParamsLocal">重置</el-button>
                </el-form-item>
            </el-form>

            <!-- 日志列表 -->
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" min-width="60" />
                <el-table-column label="订单ID" prop="order_id" min-width="80" />
                <el-table-column label="打印类型" prop="print_type" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="row.print_type == 1 ? 'primary' : 'info'">
                            {{ row.print_type == 1 ? '订单打印' : '其他打印' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="打印状态" prop="print_status" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="row.print_status == 1 ? 'success' : 'danger'">
                            {{ row.print_status == 1 ? '成功' : '失败' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" min-width="160" />
            </el-table>

            <!-- 分页 -->
            <div class="flex justify-end mt-[20px]">
                <el-pagination 
                    v-model:current-page="tableData.page_current" 
                    v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="tableData.total" 
                    @size-change="getTableList()"
                    @current-change="getTableList" />
            </div>
        </div>



        <template #footer>
            <div class="dialog-footer">
                <el-button @click="handleClose">关闭</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue'
import { getTblStorePrinterLogPages } from '@/pages-admin/main/api/tbl-store/tblStorePrinter'
import { usePagination } from '@/hooks/usePagination'

const dialogVisible = ref(false)
const loading = ref(false)

const searchParams = reactive({
    printer_id: 0,
    order_id: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblStorePrinterLogPages,
    searchParams: searchParams
})

// 设置弹窗数据
const setDialogData = (data: { printer_id: number }) => {
    if (data?.printer_id) {
        searchParams.printer_id = data.printer_id
        getTableList()
    }
}

// 重置搜索参数
const resetSearchParamsLocal = () => {
    searchParams.order_id = ''
    resetPage()
}

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true
}

// 关闭弹窗
const handleClose = () => {
    dialogVisible.value = false
    searchParams.printer_id = 0
    searchParams.order_id = ''
    resetSearchParamsLocal()
}

// 暴露方法
defineExpose({
    setDialogData,
    openDialog
})
</script> 