<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户ID">
                <el-input v-model="searchParams.user_id" placeholder="请输入用户ID" clearable />
            </el-form-item>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
            </el-form-item>
            <el-form-item label="模板标识">
                <el-input v-model="searchParams.key" placeholder="请输入模板标识" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">

        <el-tabs v-model="searchParams.notice_channel" @tab-change="handleTabChange">
            <el-tab-pane name="" label="全部"></el-tab-pane>
            <el-tab-pane v-for="item in notice_channel_options" :key="item.value" :name="item.value"
                :label="item.label"></el-tab-pane>
        </el-tabs>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" width="80" />
            <el-table-column label="用户ID" prop="user_id" width="100" />
            <el-table-column label="模板标识" prop="key" width="250" />
            <el-table-column label="通知类型" prop="notice_channel" width="150">
                <template #default="{ row }">
                    {{ row.notice_channel_desc }}
                </template>
            </el-table-column>
            <el-table-column label="接收者" prop="receiver" width="250" />
            <el-table-column label="标题" prop="title" width="250" show-overflow-tooltip />
            <el-table-column label="发送状态" prop="send_status_desc" width="100" />
            <el-table-column label="阅读状态" prop="is_read" width="100">
                <template #default="{ row }">
                    <el-tag :type="row.is_read === 1 ? 'success' : 'info'" size="small">
                        {{ row.is_read === 1 ? '已读' : '未读' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="发送时间" prop="create_at" width="200">
                <template #default="{ row }">
                    {{ row.create_at }}
                </template>
            </el-table-column>
            <el-table-column label="操作" align="right" fixed="right" width="130">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleView(row.id)">详情</el-button>
                </template>
            </el-table-column>
        </el-table>


        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>


    <sys-notice-log-detail ref="sysNoticeLogDetailDialog" />




</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getSysNoticeLogPages } from '@/pages-admin/main/api/system/sysNotice'

import { usePagination } from '@/hooks/usePagination'

import SysNoticeLogDetail from './logs-detail.vue'



const searchParams = reactive({
    user_id: '',
    username: '',
    key: '',
    notice_channel: '',
    receiver: '',
    title: '',
    is_read: '',
    send_status: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysNoticeLogPages,
    searchParams: searchParams

})
getTableList()

// 使用枚举 Hook
import { useEnum } from '@/hooks/useEnum'
const { options: notice_channel_options, } = useEnum('default.sys_notice_log.notice_channel')

// 切换订单状态
const handleTabChange = (name: any) => {
    searchParams.notice_channel = name
    getTableList()
}



// 消息通知日志详情
const sysNoticeLogDetailDialog: Record<string, any> | null = ref(null)
const handleView = (id: any) => {
    sysNoticeLogDetailDialog.value.setDialogData(id)
    sysNoticeLogDetailDialog.value?.openDialog()
}




</script>