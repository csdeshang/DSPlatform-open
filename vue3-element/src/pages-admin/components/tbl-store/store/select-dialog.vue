<template>

    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">



        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="店铺名称">
                    <el-input v-model="searchParams.store_name" placeholder="店铺名称" clearable />
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
                <el-table-column label="店铺名称" prop="store_name" />
                <el-table-column label="店铺地址" prop="store_address" />
                <el-table-column label="联系电话" prop="store_phone" />
                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="confirmStore(row)">确认</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">

                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />

            </div>

        </el-card>

    </el-dialog>


</template>



<script setup lang="ts">

import { ref, reactive } from 'vue'
import { getTblStorePages } from '@/pages-admin/main/api/tbl-store/tblStore'
import { usePagination } from '@/hooks/usePagination'
const dialogVisible = ref(false)



let popTitle: string = ''


const searchParams = reactive({
    platform: '',
    store_name: '',
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



//确认选择店铺
const emit = defineEmits(['confirm', 'select'])
const confirmStore = async (row: any) => {
    emit('confirm', row)
    dialogVisible.value = false
}

const setDialogData = async (row: any = null) => {
    searchParams.platform = row.platform
    getTableList()
}



defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>
