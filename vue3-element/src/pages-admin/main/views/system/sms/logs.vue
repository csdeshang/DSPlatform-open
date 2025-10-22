<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="用户ID">
                    <el-input v-model="searchParams.user_id" placeholder="请输入用户ID" clearable />
                </el-form-item>
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



            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="80" />
                <el-table-column label="用户ID" prop="user_id" width="100" />
                <el-table-column label="模板标识" prop="key" width="250" />
                <el-table-column label="短信服务商" prop="sms_provider" width="120" />

                <el-table-column label="模板ID" prop="sms_template_id" width="150" />
                <el-table-column label="手机号" prop="mobile" width="150" />
                <el-table-column label="短信内容" prop="content" width="300" />
                <el-table-column label="验证码" prop="code" width="150" />
                <el-table-column label="是否验证" prop="is_verify" width="150" >
                    <template #default="{ row }">
                        <el-tag v-if="row.is_verify == 1" type="success">是</el-tag>
                        <el-tag v-else type="danger">否</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="发送状态" prop="send_status_desc" width="150" />
                <el-table-column label="发送参数" prop="send_params" width="150" />
                <el-table-column label="发送结果" prop="send_result" width="150" />

                <el-table-column label="创建时间" prop="create_at" width="150" />




                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleView(row.id)">详情</el-button>
                    </template>
                </el-table-column>
            </el-table>


            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>

        </el-card>
    </div>



</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { getSysNoticeSmsLogPages } from '@/pages-admin/main/api/system/sysNoticeSms'

import { usePagination } from '@/hooks/usePagination'



const searchParams = reactive({
    user_id: '',
    username: '',
    key: '',
    sms_provider: '',
    mobile: '',
    is_verify: '',
    send_status: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysNoticeSmsLogPages,
    searchParams: searchParams

})
getTableList()





// 消息通知日志详情
const sysNoticeLogDetailDialog: Record<string, any> | null = ref(null)
const handleView = (id: any) => {
    sysNoticeLogDetailDialog.value.setDialogData(id)
    sysNoticeLogDetailDialog.value?.openDialog()
}




</script>