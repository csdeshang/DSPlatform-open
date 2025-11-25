<template>

    <div>

        <el-card shadow="never" class="mb-5">
            <el-form-item label="是否开启访问日志">
                <el-switch v-model="access_log_enabled" size="large" @change="updateAccessLogEnabled" active-value="1"
                    inactive-value="0" />
            </el-form-item>
        </el-card>

        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="管理员账号">
                    <el-input v-model="searchParams.username" placeholder="请输入管理员账号" clearable />
                </el-form-item>
                <el-form-item label="IP地址">
                    <el-input v-model="searchParams.ip" placeholder="请输入IP地址" clearable />
                </el-form-item>
                <el-form-item label="请求耗时">
                    <div class="flex items-center gap-2">
                        <el-input v-model="searchParams.duration_min" placeholder="最小耗时(毫秒)" clearable
                            class="w-[140px]" />
                        <span class="text-gray-500">-</span>
                        <el-input v-model="searchParams.duration_max" placeholder="最大耗时(毫秒)" clearable
                            class="w-[140px]" />
                    </div>
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
                <el-table-column label="访问ID" prop="user_id" width="100" />
                <el-table-column label="访问用户名" prop="username" width="120" />
                <el-table-column label="IP" prop="ip" width="200" />
                <el-table-column label="访问类型" prop="method" width="120" />
                <el-table-column label="控制器" prop="controller" width="120" />
                <el-table-column label="方法" prop="action" width="120" />
                <el-table-column label="请求耗时(毫秒)" prop="duration" width="100" />
                <el-table-column label="HTTP状态码" prop="http_code" width="100" />
                <el-table-column label="返回CODE" prop="code" width="100" />
                <el-table-column label="操作时间" prop="create_at" width="200" />
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


        <sys-access-logs-detail ref="sysAccessLogsDetailDialog" />


    </div>

</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { ElMessage } from 'element-plus';
import { getSysAccessLogsPages } from '@/pages-admin/main/api/system/sysAccessLogs'
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { usePagination } from '@/hooks/usePagination'

import SysAccessLogsDetail from './detail.vue'


const searchParams = reactive({
    username: '',
    ip: '',
    duration_min: '',
    duration_max: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysAccessLogsPages,
    searchParams: searchParams

})
getTableList()

const sysAccessLogsDetailDialog: Record<string, any> | null = ref(null)


const handleView = (id: any) => {
    sysAccessLogsDetailDialog.value.setDialogData(id)
    sysAccessLogsDetailDialog.value?.openDialog()
}

// 是否开启访问日志
const access_log_enabled = ref('0');
// 获取访问日志开关配置
const fetchAccessLogEnabled = async () => {
    try {
        let key = 'access_log_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            access_log_enabled.value = response.data.config_value || '0';
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchAccessLogEnabled();

// 更新访问日志开关配置
const updateAccessLogEnabled = async () => {
    try {
        let key = 'access_log_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'system',
            config_key: key,
            config_value: access_log_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新访问日志配置成功');
        } else {
            ElMessage.error('更新访问日志配置失败');
        }
    } catch (error) {
        console.error('更新系统配置时出错:', error);
        ElMessage.error('更新访问日志配置失败');
    }
}




</script>