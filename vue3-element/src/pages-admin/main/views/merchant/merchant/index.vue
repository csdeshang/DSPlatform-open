<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="商户名称">
                    <el-input v-model="searchParams.name" placeholder="请输入商户名称" clearable />
                </el-form-item>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
                </el-form-item>
                <el-form-item label="联系人">
                    <el-input v-model="searchParams.contact_name" placeholder="请输入联系人" clearable />
                </el-form-item>
                <el-form-item label="联系电话">
                    <el-input v-model="searchParams.contact_phone" placeholder="请输入联系电话" clearable />
                </el-form-item>
                <el-form-item label="可用金额">
                    <div class="flex items-center gap-2">
                        <el-input v-model="searchParams.balance_min" placeholder="最小金额" clearable class="w-[120px]" />
                        <span class="text-gray-500">-</span>
                        <el-input v-model="searchParams.balance_max" placeholder="最大金额" clearable class="w-[120px]" />
                    </div>
                </el-form-item>
                <el-form-item label="总收入">
                    <div class="flex items-center gap-2">
                        <el-input v-model="searchParams.balance_in_min" placeholder="最小金额" clearable class="w-[120px]" />
                        <span class="text-gray-500">-</span>
                        <el-input v-model="searchParams.balance_in_max" placeholder="最大金额" clearable class="w-[120px]" />
                    </div>
                </el-form-item>
                <el-form-item label="总支出">
                    <div class="flex items-center gap-2">
                        <el-input v-model="searchParams.balance_out_min" placeholder="最小金额" clearable class="w-[120px]" />
                        <span class="text-gray-500">-</span>
                        <el-input v-model="searchParams.balance_out_max" placeholder="最大金额" clearable class="w-[120px]" />
                    </div>
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
                <el-table-column label="关联用户" prop="user_id" min-width="60" >
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleUserDtail(row.user_id)">{{ row.user.username }}</el-link>
                    </template>
                </el-table-column>
                <el-table-column label="商户名称" prop="name" />
                <el-table-column label="联系人" prop="contact_name" />
                <el-table-column label="联系电话" prop="contact_phone" />
                <el-table-column label="申请状态" prop="apply_status_desc" />
                <el-table-column label="金额" prop="balance" />
                <el-table-column label="总收入" prop="balance_in" />
                <el-table-column label="总支出" prop="balance_out" />
                <el-table-column label="允许开店数量" prop="allowed_store_count" />
 

                <el-table-column label="操作" align="right" fixed="right" width="200">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleDetail(row)">详情</el-button>
                        <el-button type="primary" link @click="handleModifyBalance(row)">调整余额</el-button>
                        <el-button type="primary" link @click="handleApplyAudit(row)" v-if="row.apply_status != 1">审核</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>

        </el-card>
        <!-- 查看和修改 -->
        <merchant-detail ref="detailMerchantDialog" @complete="getTableList()" />

        <!-- 添加 -->
        <merchant-add ref="addMerchantDialog" @complete="getTableList()" />

        <!-- 调整余额 -->
        <merchant-modify-balance ref="modifyBalanceDialog" @complete="getTableList()" />

        <!-- 会员详情弹窗 -->
        <user-detail ref="detailUserDialog" @complete="getTableList()" />

        <!-- 审核 -->
        <merchant-apply-audit ref="applyAuditDialog" @complete="getTableList()" />


    </div>
</template>
<script lang="ts" setup>
import { ElMessageBox } from 'element-plus';
import { reactive, ref } from 'vue';
import { getMerchantPages } from '@/pages-admin/main/api/merchant/merchant'
import merchantDetail from './detail.vue'
import merchantAdd from './add.vue'
import merchantModifyBalance from './modify-balance.vue'
import merchantApplyAudit from './applyAudit.vue'

import { usePagination } from '@/hooks/usePagination'


const searchParams = reactive({
    name: '',
    username: '',
    apply_status: '',
    contact_name: '',
    contact_phone: '',
    balance_min: '',
    balance_max: '',
    balance_in_min: '',
    balance_in_max: '',
    balance_out_min: '',
    balance_out_max: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getMerchantPages,
    searchParams: searchParams
})
getTableList()


// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: apply_status_options } = useEnum('default.merchant.apply_status')

// 审核
const applyAuditDialog: Record<string, any> | null = ref(null)
const handleApplyAudit = (row: any) => {
    applyAuditDialog.value.setDialogData(row)
    applyAuditDialog.value?.openDialog()
}




// 切换状态
const handleTabChange = (name: any) => {
    searchParams.apply_status = name
    getTableList()
}

const addMerchantDialog: Record<string, any> | null = ref(null)
/**
 * 添加
 */
const handleAdd = () => {
    addMerchantDialog.value.setDialogData()
    addMerchantDialog.value?.openDialog()
}
const detailMerchantDialog: Record<string, any> | null = ref(null)
// 查看
const handleDetail = (data: any) => {
    detailMerchantDialog.value.setDialogData(data)
    detailMerchantDialog.value?.openDialog()
}

// 调整余额
const modifyBalanceDialog: Record<string, any> | null = ref(null)

const handleModifyBalance = (data: any) => {
    modifyBalanceDialog.value.setDialogData(data)
    modifyBalanceDialog.value?.openDialog()
}




// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



</script>