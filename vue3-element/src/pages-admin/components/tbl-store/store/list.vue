<template>
    <!-- 公共店铺列表 显示 TblStore 表中的数据 -->

    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="店铺名称">
                    <el-input v-model="searchParams.store_name" placeholder="店铺名称" clearable />
                </el-form-item>
                <el-form-item label="商户ID">
                    <el-input v-model="searchParams.merchant_id" placeholder="商户ID" clearable />
                </el-form-item>
                <el-form-item label="商户名称">
                    <el-input v-model="searchParams.merchant_name" placeholder="商户名称" clearable />
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
                <el-tab-pane v-for="item in apply_status_options" :key="item.value" :name="item.value"
                    :label="item.label"></el-tab-pane>
            </el-tabs>

            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="店铺ID" prop="id" min-width="30" />
                <el-table-column label="商户ID" prop="merchant_id" min-width="30" />
                <el-table-column label="商户名称" prop="merchant.name" min-width="80" />
                <el-table-column label="店铺类型" prop="platform" min-width="50" />
                <el-table-column label="店铺名称" prop="store_name" min-width="100" />
                <el-table-column label="申请状态" prop="apply_status_desc" min-width="50" />
                <el-table-column label="是否可用" prop="is_enabled" min-width="50">
                    <template #default="{ row }">
                        <el-tag v-if="row.is_enabled" type="success">是</el-tag>
                        <el-tag v-else type="danger">否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="是否推荐" prop="is_recommend" min-width="50">
                    <template #default="{ row }">
                        <el-tag v-if="row.is_recommend" type="success">是</el-tag>
                        <el-tag v-else type="danger">否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="服务费率" prop="service_fee_rate" min-width="50" />


                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleDtail(row.id)">详情</el-button>
                            <el-button type="primary" link @click="handleAudit(row)"
                                v-if="row.apply_status != 1">审核</el-button>
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


        <tbl-store-add :platform="props.platform" ref="addTblStoreDialog" @complete="getTableList()" />

        <tbl-store-detail ref="detailTblStoreDialog" @complete="getTableList()" />

        <tbl-store-apply-audit ref="auditTblStoreDialog" @complete="getTableList()" />

    </div>
</template>



<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { getTblStorePages } from '@/pages-admin/main/api/tbl-store/tblStore'
import { usePagination } from '@/hooks/usePagination'

import TblStoreAdd from '@/pages-admin/components/tbl-store/store/add.vue'
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
import TblStoreApplyAudit from '@/pages-admin/components/tbl-store/store/applyAudit.vue'


const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})

const searchParams = reactive({
    store_name: '',
    merchant_id: '',
    merchant_name: '',
    platform: props.platform,
    apply_status: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblStorePages,
    searchParams: searchParams
})
getTableList()


// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: apply_status_options } = useEnum('default.tbl_store.apply_status')

// 切换状态
const handleTabChange = (name: any) => {
    searchParams.apply_status = name
    getTableList()
}

// 审核店铺申请.
const auditTblStoreDialog: Record<string, any> | null = ref(null)
const handleAudit = (row: any) => {
    auditTblStoreDialog.value.setDialogData(row)
    auditTblStoreDialog.value?.openDialog()
}


// 快速添加店铺
const addTblStoreDialog = ref()
const handleAdd = () => {
    addTblStoreDialog.value.setDialogData()
    addTblStoreDialog.value?.openDialog()

}


// 店铺详情
const detailTblStoreDialog = ref()
const handleDtail = (store_id: any) => {
    detailTblStoreDialog.value.setDialogData({ id: store_id })
    detailTblStoreDialog.value?.openDialog()
}






</script>