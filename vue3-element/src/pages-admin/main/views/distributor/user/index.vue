<template>


    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="用户名" clearable />
            </el-form-item>
            <el-form-item label="手机号">
                <el-input v-model="searchParams.mobile" placeholder="请输入手机号" clearable />
            </el-form-item>
            <el-form-item label="邀请人ID">
                <el-input v-model="searchParams.inviter_id" placeholder="请输入邀请人ID" clearable />
            </el-form-item>
            <el-form-item label="可用佣金">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.distributor_balance_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.distributor_balance_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="佣金总收入">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.distributor_balance_in_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.distributor_balance_in_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="佣金总支出">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.distributor_balance_out_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.distributor_balance_out_max" placeholder="最大金额" clearable class="w-[120px]" />
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
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="账号" prop="username" />
            <el-table-column label="可用佣金" prop="distributor_balance" />
            <el-table-column label="佣金总收入" prop="distributor_balance_in" />
            <el-table-column label="佣金总支出" prop="distributor_balance_out" />
            <el-table-column label="最近登录" prop="login_time" />
            <el-table-column label="邀请人" prop="inviter_id" width="80" />
            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <div class="flex flex-row">
                        <el-button type="primary" link @click="handleDtail(row.id)">详情</el-button>
                        <el-button type="primary" link @click="handleModifyBalance(row)">调整佣金</el-button>
                    </div>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>


    </el-card>

    <UserDetail ref="detailUserDialog" @complete="getTableList()" />
    <DistributorUserModifyBalance ref="modifyBalanceDialog" @complete="getTableList()" />
</template>


<script lang="ts" setup>
import { reactive, ref } from 'vue';

// 分销商列表(使用会员列表接口)
import { getUserPage } from '@/pages-admin/main/api/user/user'
import { usePagination } from '@/hooks/usePagination'

// 会员详情弹窗调用
import UserDetail from '@/pages-admin/components/user/detail.vue'

// 修改余额弹窗调用
import DistributorUserModifyBalance from './modify-balance.vue'




const searchParams = reactive({
    username: '',
    mobile: '',
    inviter_id: '',
    distributor_balance_min: '',
    distributor_balance_max: '',
    distributor_balance_in_min: '',
    distributor_balance_in_max: '',
    distributor_balance_out_min: '',
    distributor_balance_out_max: '',
    is_distributor: 1,
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserPage,
    searchParams: searchParams
})
getTableList()


//修改余额
const modifyBalanceDialog = ref()
const handleModifyBalance = (row: any) => {

    modifyBalanceDialog.value.setDialogData(row)
    modifyBalanceDialog.value?.openDialog()
}



// 会员详情
const detailUserDialog = ref()
const handleDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}


</script>