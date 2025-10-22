<template>
    <el-alert title="在师傅服务完成之后，并不会增加师傅余额， 需要用户确认收货之后才会增加师傅余额" type="warning" :closable="false" show-icon
        style="margin-bottom: 15px;" />

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="师傅ID">
                <el-input v-model="searchParams.technician_id" placeholder="请输入师傅ID" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>

    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column label="师傅ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleTechnicianDetail(row.technician_id)">
                        {{ row.technician_id }}
                    </el-link>
                </template>
            </el-table-column>

            <el-table-column prop="change_type_desc" label="记录类型" width="100" />
            <el-table-column prop="change_mode_desc" label="变动方式" width="100" />
            <el-table-column label="变动金额" width="100">
                <template #default="{ row }">
                    <span :style="{ color: row.change_mode === 1 ? '#67C23A' : '#F56C6C' }">
                        {{ row.change_mode === 1 ? '+' : '-' }}{{ row.change_amount }}
                    </span>
                </template>
            </el-table-column>
            <el-table-column prop="before_balance" label="变动前余额" width="100" />
            <el-table-column prop="after_balance" label="变动后余额" width="100" />
            <el-table-column prop="change_desc" label="描述" />
            <el-table-column prop="create_at" label="变动时间" width="150" />

        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>

    <technician-detail ref="detailTechnicianDialog" />

</template>
<script lang="ts" setup name="TechnicianBalanceList">

import { reactive, ref } from 'vue';
import { getTechnicianBalanceLogPages } from '@/pages-admin/main/api/technician/technicianBalance'

import { usePagination } from '@/hooks/usePagination'

const searchParams = reactive({
    technician_id: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getTechnicianBalanceLogPages,
    searchParams: searchParams
})
getTableList()

// 师傅详情弹窗
import TechnicianDetail from '@/pages-admin/main/views/technician/technician/detail.vue'
const detailTechnicianDialog = ref()
const handleTechnicianDetail = (technician_id: any) => {
    detailTechnicianDialog.value.setDialogData({ id: technician_id })
    detailTechnicianDialog.value?.openDialog()
}

</script>