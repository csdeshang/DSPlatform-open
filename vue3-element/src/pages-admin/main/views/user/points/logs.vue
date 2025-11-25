<template>

    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="用户名" clearable />
                </el-form-item>
                <el-form-item label="变动类型">
                    <el-select v-model="searchParams.change_type" placeholder="请选择变动类型" clearable class="w-[100px]">
                        <el-option label="全部" value="" />
                        <el-option v-for="item in change_type_options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="变动方式">
                    <el-select v-model="searchParams.change_mode" placeholder="请选择变动方式" clearable class="w-[100px]">
                        <el-option label="全部" value="" />
                        <el-option v-for="item in change_mode_options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="关联ID">
                    <el-input v-model="searchParams.related_id" placeholder="请输入关联ID" clearable />
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
                <el-table-column prop="user_id" label="用户ID" width="200">
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleUserDtail(row.user.id)">{{
                            row.user.username
                            }}</el-link>
                    </template>
                </el-table-column>
                <el-table-column prop="change_type_desc" label="变动类型" width="80" />
                <el-table-column prop="before_num" label="变动前积分" width="100" />
                <el-table-column prop="change_mode_desc" label="变动方式" width="100" />
                <el-table-column prop="change_num" label="变动积分" width="80" />
                <el-table-column prop="after_num" label="变动后积分" width="80" />
                <el-table-column prop="change_desc" label="变动原因" />
                <el-table-column prop="create_at" label="变动时间" width="200" />

            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>



        <UserDetail ref="detailUserDialog" />


    </div>



</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { getUserPointsLogPages } from '@/pages-admin/main/api/user/userPoints'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'

// 使用枚举 Hook
const { options: change_mode_options, } = useEnum('default.user_points_log.change_mode')
const { options: change_type_options, } = useEnum('default.user_points_log.change_type')

const searchParams = reactive({
    username: '',
    change_type: '',
    change_mode: '',
    related_id: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserPointsLogPages,
    searchParams: searchParams
})
getTableList()






// 会员详情弹窗[公共组件]
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}



</script>
