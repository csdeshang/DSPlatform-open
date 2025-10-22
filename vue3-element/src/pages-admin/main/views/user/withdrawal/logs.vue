<template>
<div>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="用户名" clearable />
            </el-form-item>
            <el-form-item label="转账单号">
                <el-input v-model="searchParams.out_transfer_no" placeholder="转账单号" clearable />
            </el-form-item>
            <el-form-item label="提现金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.withdrawal_amount_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.withdrawal_amount_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">

        <el-tabs v-model="searchParams.status" @tab-change="handleTabChange">
            <el-tab-pane name="" label="全部"></el-tab-pane>
            <el-tab-pane v-for="item in status_options" :key="item.value" :name="item.value" :label="item.label" ></el-tab-pane>
        </el-tabs>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="user_id" label="用户ID" width="80">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user.id)">{{
                        row.user.username
                        }}</el-link>
                </template>
            </el-table-column>
            <el-table-column prop="apply_amount" label="申请金额" width="120" />
            <el-table-column prop="fee_amount" label="手续费" width="120" />
            <el-table-column prop="withdrawal_amount" label="实际到账" width="120" />
            


            <el-table-column prop="account_type_desc" label="账户类型" width="300">
                <template #default="{ row }">
                    <div class="flex flex-col gap-[5px]">
                        <div>{{ row.account_type_desc }}</div>
                        <div>{{ row.account_name}}</div>
                        <div>{{ row.account_holder}}</div>
                        <div>{{ row.account_number}}</div>
                       
                    </div>
                </template>
            </el-table-column>


            <el-table-column prop="create_at" label="申请时间" width="200" />
            <el-table-column prop="update_at" label="处理时间" width="200" />
            <el-table-column prop="status_desc" label="状态" width="100" />

            <el-table-column prop="transfer_type" label="转账方式" width="100">
                <template #default="{ row }">
                    <div v-if="row.transfer_type">
                        {{ row.transfer_type_desc }}
                    </div>
                </template>
            </el-table-column>

            <el-table-column prop="operation_remark" label="操作备注" width="200" />





            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)" v-if="row.status == 0">处理申请</el-button>

                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>



    <UserDetail ref="detailUserDialog" />

    <UserWithdrawalEdit ref="userWithdrawalEditDialog" @complete="getTableList()" />


</div>


</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { getUserWithdrawalLogPages } from '@/pages-admin/main/api/user/userWithdrawal'
import { usePagination } from '@/hooks/usePagination'

import UserWithdrawalEdit from './edit.vue'

import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: status_options } = useEnum('default.user_withdrawal_log.status')
const { options: transfer_type_options } = useEnum('default.user_withdrawal_log.transfer_type')
const { options: account_type_options } = useEnum('default.user_withdrawal_log.account_type')


const searchParams = reactive({
    username: '',
    status: '',
    out_transfer_no: '',
    withdrawal_amount_min: '',
    withdrawal_amount_max: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserWithdrawalLogPages,
    searchParams: searchParams
})
getTableList()



const handleTabChange = (name: any) => {
    searchParams.status = name
    getTableList()
}

// 处理申请
const userWithdrawalEditDialog = ref()
const handleEdit = (row: any) => {
    userWithdrawalEditDialog.value.setDialogData(row)
    userWithdrawalEditDialog.value?.openDialog()
}




// 会员详情弹窗[公共组件]
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}

</script>
