<template>


    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="骑手名称">
                <el-input v-model="searchParams.name" placeholder="骑手名称" clearable />
            </el-form-item>
            <el-form-item label="手机号">
                <el-input v-model="searchParams.mobile" placeholder="手机号" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="用户名" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
        <el-button type="primary" @click="handleAdd()">添加</el-button>
    </el-card>

    <el-card shadow="never">

            <el-tabs v-model="searchParams.apply_status" @tab-change="handleTabChange">
                <el-tab-pane name="" label="全部"></el-tab-pane>
                <el-tab-pane v-for="item in apply_status_options" :key="item.value" :name="item.value" :label="item.label"></el-tab-pane>
            </el-tabs>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="关联用户" prop="user.id" min-width="60" >
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user.id)">{{ row.user.username }}</el-link>
                </template>
            </el-table-column>
            <el-table-column label="骑手名称" prop="name" />
            <el-table-column label="手机号" prop="mobile" />
            <el-table-column label="余额" prop="balance" />
            <el-table-column label="总收入" prop="balance_in" />
            <el-table-column label="总支出" prop="balance_out" />

            <el-table-column label="是否可用" prop="is_enabled_desc" />


            <el-table-column label="状态" prop="status_desc" />
            <el-table-column label="审核状态" prop="apply_status_desc" />

            <el-table-column label="创建时间" prop="create_at" />
            <el-table-column label="更新时间" prop="update_at" />
            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <div class="flex flex-row">
                        <el-button type="primary" link @click="handleDtail(row.id)">详情</el-button>
                        <el-button type="primary" link @click="handleApplyAudit(row)" v-if="row.apply_status != 1">审核</el-button>
                        <el-dropdown class="ml-[10px]" @command="(command) => handleMore(command, row)">
                            <el-button type="primary" link>更多<el-icon><arrow-down /></el-icon></el-button>
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item command="balance">调整余额</el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
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


    <rider-add ref="createRiderDialog" @complete="getTableList()" />
    <rider-detail ref="detailRiderDialog" @complete="getTableList()" />
    <rider-modify-balance ref="modifyBalanceDialog" @complete="getTableList()"></rider-modify-balance>

    <!-- 审核 -->
    <rider-apply-audit ref="applyAuditDialog" @complete="getTableList()" />

    <!-- 会员详情弹窗 -->
    <user-detail ref="detailUserDialog" @complete="getTableList()" />

</template>


<script lang="ts" setup>
import { reactive, ref } from 'vue';

import { getRiderPage } from '@/pages-admin/main/api/rider/rider'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'

import RiderAdd from './add.vue'
import RiderModifyBalance from './modify-balance.vue'
import RiderApplyAudit from './applyAudit.vue'

// 骑手详情弹窗调用
import RiderDetail from './detail.vue'


const searchParams = reactive({
    name: '',
    mobile: '',
    username: '',
    apply_status: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getRiderPage,
    searchParams: searchParams
})
getTableList()

// 使用枚举 Hook
const { options: apply_status_options } = useEnum('default.rider.apply_status')

// 切换状态
const handleTabChange = (name: any) => {
    searchParams.apply_status = name
    getTableList()
}

//更多
const handleMore = (command: string, row: any,) => {

    switch (command) {
        case 'balance':
            handleModifyBalance(row);
            break;
        default:
            console.log('未知命令', command);
    }
}



// 添加骑手
const createRiderDialog = ref()

const handleAdd = () => {
    createRiderDialog.value.setDialogData()
    createRiderDialog.value?.openDialog()

}

// 骑手详情
const detailRiderDialog = ref()
const handleDtail = (rider_id: any) => {
    detailRiderDialog.value.setDialogData({ id: rider_id })
    detailRiderDialog.value?.openDialog()
}

//修改余额
const modifyBalanceDialog = ref()
const handleModifyBalance = (row: any) => {
    modifyBalanceDialog.value.setDialogData(row)
    modifyBalanceDialog.value?.openDialog()
}

// 审核
const applyAuditDialog = ref()
const handleApplyAudit = (row: any) => {
    applyAuditDialog.value.setDialogData(row)
    applyAuditDialog.value?.openDialog()
}





// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



</script>