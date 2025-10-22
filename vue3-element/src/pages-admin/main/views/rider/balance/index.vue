<template>
    <el-alert title="在骑手配送完成之后，并不会增加骑手余额， 需要用户确认收货之后才会增加骑手余额" type="warning" :closable="false" show-icon
        style="margin-bottom: 15px;" />

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="骑手名称">
                <el-input v-model="searchParams.rider_id" placeholder="请输入骑手ID" clearable />
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
            <el-table-column label="骑手ID" width="100">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleRiderDtail(row.rider_id)">
                        {{ row.rider_id }}
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


    <rider-detail ref="detailRiderDialog" />


</template>
<script lang="ts" setup name="RiderBalanceList">

import { reactive, ref } from 'vue';
import { getRiderBalanceLogPages } from '@/pages-admin/main/api/rider/riderBalance'


import { usePagination } from '@/hooks/usePagination'


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
    requestFun: getRiderBalanceLogPages,
    searchParams: searchParams
})
getTableList()




// 骑手详情弹窗
import RiderDetail from '@/pages-admin/main/views/rider/rider/detail.vue'
const detailRiderDialog = ref()
const handleRiderDtail = (rider_id: any) => {
    detailRiderDialog.value.setDialogData({ id: rider_id })
    detailRiderDialog.value?.openDialog()
}



</script>