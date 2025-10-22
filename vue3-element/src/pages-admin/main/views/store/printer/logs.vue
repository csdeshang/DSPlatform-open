<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="店铺名称">
                    <el-input v-model="searchParams.store_name" placeholder="店铺名称" clearable />
                </el-form-item>
                <el-form-item label="订单ID">
                    <el-input v-model="searchParams.order_id" placeholder="订单ID" clearable />
                </el-form-item>
                <el-form-item label="打印状态">
                    <el-select v-model="searchParams.print_status" placeholder="选择状态" clearable style="width: 100px;">
                        <el-option label="成功" :value="1" />
                        <el-option label="失败" :value="0" />
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
                <el-table-column label="操作" align="right" fixed="right" width="100">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleViewLog(row)">查看详情</el-button>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 日志详情弹窗 -->
        <el-dialog v-model="logDetailVisible" title="日志详情" width="600px">
            <div v-if="currentLog">
                <el-descriptions :column="1" border>
                    <el-descriptions-item label="日志ID">{{ currentLog.id }}</el-descriptions-item>
                    <el-descriptions-item label="店铺ID">{{ currentLog.store_id }}</el-descriptions-item>
                    <el-descriptions-item label="打印机ID">{{ currentLog.printer_id }}</el-descriptions-item>
                    <el-descriptions-item label="订单ID">{{ currentLog.order_id }}</el-descriptions-item>
                    <el-descriptions-item label="打印类型">
                        <el-tag :type="currentLog.print_type == 1 ? 'primary' : 'info'">
                            {{ currentLog.print_type == 1 ? '订单打印' : '其他打印' }}
                        </el-tag>
                    </el-descriptions-item>
                    <el-descriptions-item label="打印状态">
                        <el-tag :type="currentLog.print_status == 1 ? 'success' : 'danger'">
                            {{ currentLog.print_status == 1 ? '成功' : '失败' }}
                        </el-tag>
                    </el-descriptions-item>
                    <el-descriptions-item label="创建时间">{{ currentLog.create_at }}</el-descriptions-item>
                </el-descriptions>

                <el-divider content-position="left">打印内容</el-divider>
                <el-input v-model="currentLog.print_content" type="textarea" :rows="8" readonly placeholder="打印内容" />

                <el-divider content-position="left">打印结果</el-divider>
                <el-input v-model="currentLog.print_result" type="textarea" :rows="6" readonly placeholder="打印结果" />
            </div>
        </el-dialog>
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { getTblStorePrinterLogPages } from '@/pages-admin/main/api/tbl-store/tblStorePrinter'
import { usePagination } from '@/hooks/usePagination'

const searchParams = reactive({
    store_name: '',
    order_id: '',
    print_status: '',
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

// 初始化加载数据
getTableList()

// 日志详情弹窗
const logDetailVisible = ref(false)
const currentLog = ref<any>(null)

// 查看日志详情
const handleViewLog = (row: any) => {
    currentLog.value = row
    logDetailVisible.value = true
}
</script>
