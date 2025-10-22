<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>

    </el-card>

    <el-card shadow="never">
        <el-tabs v-model="searchParams.apply_status" @tab-change="handleTabChange">
            <el-tab-pane name="" label="全部"></el-tab-pane>
            <el-tab-pane v-for="item in apply_status_options" :key="item.value" :name="item.value"
                :label="item.label"></el-tab-pane>
        </el-tabs>


        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="user_id" label="用户ID" width="80">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDtail(row.user_id)">{{
                        row.user_id }}</el-link>
                </template>
            </el-table-column>

            <el-table-column prop="apply_remark" label="申请备注" width="300" />
            <el-table-column prop="apply_time" label="申请时间" width="200" />
            <el-table-column prop="apply_status_desc" label="审核状态" width="100" />
            <el-table-column prop="audit_time" label="审核时间" width="200" />
            <el-table-column prop="audit_remark" label="审核备注" />

            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleAudit(row)" v-if="row.apply_status != 1">审核</el-button>
                </template>
            </el-table-column>

        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>


    <user-detail ref="detailUserDialog" />
    <apply-audit ref="applyAuditDialog" @complete="getTableList()" />


</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getDistributorApplyPages } from '@/pages-admin/main/api/distributor/distributorApply'
import { usePagination } from '@/hooks/usePagination'
import applyAudit from './applyAudit.vue'


// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



const searchParams = reactive({
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
    page_size: 20,
    requestFun: getDistributorApplyPages,
    searchParams: searchParams
})
getTableList()


import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: apply_status_options, } = useEnum('default.distributor_apply.apply_status')

// 审核状态切换
const handleTabChange = (val: any) => {
    searchParams.apply_status = val
    getTableList()
}



// 审核分销商申请.
const applyAuditDialog: Record<string, any> | null = ref(null)
const handleAudit = (row: any) => {
    applyAuditDialog.value.setDialogData(row)
    applyAuditDialog.value?.openDialog()
}




</script>