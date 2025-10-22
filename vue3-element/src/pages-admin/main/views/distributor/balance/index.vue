<template>


    <el-card shadow="never" class="mb-[10px]">

        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="变动类型">
                <el-select v-model="searchParams.change_type" placeholder="请选择变动类型" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in change_type_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="变动方式">
                <el-select v-model="searchParams.change_mode" placeholder="请选择变动方式" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in change_mode_options" :key="item.value" :label="item.label" :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="变动金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.change_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.change_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
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
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="distributor_user_id" label="用户ID" width="80">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.distributorUser.id)">{{
                        row.distributorUser.username }}</el-link>
                </template>
            </el-table-column>

            <el-table-column prop="change_type_desc" label="变动类型" width="100" />
            <el-table-column prop="related_id" label="关联ID" width="100" />
            <el-table-column prop="before_balance" label="变动前余额" width="100" />
            <el-table-column prop="change_mode_desc" label="变动方式" width="100" />
            <el-table-column prop="change_amount" label="变动金额" width="100" />
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


    <user-detail ref="detailUserDialog" />

</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getDistributorBalanceLogPages } from '@/pages-admin/main/api/distributor/distributorBalance'


import { usePagination } from '@/hooks/usePagination'


const searchParams = reactive({
    username: '',
    change_type: '',
    change_mode: '',
    change_amount_min: '',
    change_amount_max: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getDistributorBalanceLogPages,
    searchParams: searchParams
})
getTableList()

// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: change_type_options } = useEnum('default.distributor_balance_log.change_type')
const { options: change_mode_options } = useEnum('default.distributor_balance_log.change_mode')

// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



</script>