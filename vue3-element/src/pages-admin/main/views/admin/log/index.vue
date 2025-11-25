<template>

    <div>
        <el-card shadow="never" class="mb-5">
            <el-form-item label="是否开启管理员操作日志">
                <el-switch v-model="admin_log_enabled" size="large" @change="updateAdminLogEnabled" active-value="1"
                    inactive-value="0" />
            </el-form-item>
        </el-card>

        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="管理员账号">
                    <el-input v-model="searchParams.username" placeholder="请输入管理员账号" clearable />
                </el-form-item>
                <el-form-item label="HTTP状态码">
                    <el-input v-model="searchParams.http_code" placeholder="请输入HTTP状态码" clearable />
                </el-form-item>
                <el-form-item label="控制器">
                    <el-input v-model="searchParams.controller" placeholder="请输入控制器名称" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">

            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="120" />
                <el-table-column label="管理员ID" prop="user_id" width="100" />
                <el-table-column label="管理员" prop="username" width="120" />
                <el-table-column label="IP" prop="ip" width="120" />
                <el-table-column label="访问类型" prop="method" width="120" />
                <el-table-column label="控制器" prop="controller" width="200" />
                <el-table-column label="方法" prop="action" width="200" />
                <el-table-column label="请求耗时(毫秒)" prop="duration" width="100" />
                <el-table-column label="HTTP状态码" prop="http_code" width="100" />
                <el-table-column label="返回CODE" prop="code" width="100" />
                <el-table-column label="操作时间" prop="create_at" width="160" />
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

        <AdminLogDetail ref="adminLogDetailDialog" />

    </div>

</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { ElMessage } from 'element-plus';
import { getAdminLogPages } from '@/pages-admin/main/api/admin/adminLog'
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { usePagination } from '@/hooks/usePagination'

import AdminLogDetail from './detail.vue'

const searchParams = reactive({
    username: '',
    http_code: '',
    controller: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getAdminLogPages,
    searchParams: searchParams
})
getTableList()

const adminLogDetailDialog: Record<string, any> | null = ref(null)

const handleView = (id: any) => {
    adminLogDetailDialog.value.setDialogData(id)
    adminLogDetailDialog.value?.openDialog()
}

// 是否开启管理员操作日志
const admin_log_enabled = ref('1');
// 获取管理员操作日志开关配置
const fetchAdminLogEnabled = async () => {
    try {
        let key = 'admin_log_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            admin_log_enabled.value = response.data.config_value || '1';
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchAdminLogEnabled();

// 更新管理员操作日志开关配置
const updateAdminLogEnabled = async () => {
    try {
        let key = 'admin_log_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'system',
            config_key: key,
            config_value: admin_log_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新管理员操作日志配置成功');
        } else {
            ElMessage.error('更新管理员操作日志配置失败');
        }
    } catch (error) {
        console.error('更新系统配置时出错:', error);
        ElMessage.error('更新管理员操作日志配置失败');
    }
}



</script>