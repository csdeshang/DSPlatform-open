<template>

    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">



        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item lable="用户名">
                    <el-input v-model="searchParams.username" placeholder="用户名" clearable />
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
                <el-table-column label="头像" prop="avatar" />
                <el-table-column label="账号" prop="username" />
                <el-table-column label="可用金额" prop="balance" />
                <el-table-column label="积分" prop="points" />
                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="confirmUser(row)">确认</el-button>
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
import { getUserPage } from '@/pages-admin/main/api/user/user'
import { usePagination } from '@/hooks/usePagination'
const dialogVisible = ref(false)



let popTitle: string = ''


const searchParams = reactive({
    username: '',
    mobile: '',
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



//确认选择用户
const emit = defineEmits(['confirm', 'select'])
const confirmUser = async (row: any) => {
    emit('confirm', row)
    dialogVisible.value = false
}




defineExpose({
    openDialog: () => {
        getTableList()
        dialogVisible.value = true
    }
})
</script>
