<template>

    <div>
        <el-card shadow="never" class="mb-5">
            <el-form-item label="是否开启错误日志">
                <el-switch v-model="error_log_enabled" size="large" @change="updateErrorLogEnabled" active-value="1"
                    inactive-value="0" />
            </el-form-item>
        </el-card>

        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="根目录">
                    <el-input v-model="searchParams.root" placeholder="请输入根目录" clearable />
                </el-form-item>
                <el-form-item label="控制器">
                    <el-input v-model="searchParams.controller" placeholder="请输入控制器名称" clearable />
                </el-form-item>
                <el-form-item label="IP地址">
                    <el-input v-model="searchParams.ip" placeholder="请输入IP地址" clearable />
                </el-form-item>
                <el-form-item label="错误代码">
                    <el-input v-model="searchParams.code" placeholder="请输入错误代码" clearable />
                </el-form-item>
                <el-form-item label="包含异常类名">
                    <el-select v-model="searchParams.include_exception_class" placeholder="请选择要包含的异常类名" multiple
                        clearable class="w-[280px]">
                        <el-option v-for="item in exception_class_options" :key="item.value" :label="item.label"
                            :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="排除异常类名">
                    <el-select v-model="searchParams.exclude_exception_class" placeholder="请选择要排除的异常类名" multiple
                        clearable class="w-[280px]">
                        <el-option v-for="item in exception_class_options" :key="item.value" :label="item.label"
                            :value="item.value" />
                    </el-select>
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
                <el-table-column label="IP" prop="ip" width="200" />
                <el-table-column label="访问类型" prop="method" width="100" />
                <el-table-column label="控制器" prop="controller" width="200" />
                <el-table-column label="方法" prop="action" width="250" />
                <el-table-column label="请求耗时(毫秒)" prop="duration" width="100" />
                <el-table-column label="返回CODE" prop="code" width="100" />
                <el-table-column label="异常类名" prop="exception_class" width="200" />
                <el-table-column label="错误信息" prop="message" width="400" />
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

        <SysErrorLogsDetail ref="sysErrorLogsDetailDialog" />

    </div>

</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { ElMessage } from 'element-plus';
import { getSysErrorLogsPages } from '@/pages-admin/main/api/system/sysErrorLogs'
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { usePagination } from '@/hooks/usePagination'

import SysErrorLogsDetail from './detail.vue'

import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: exception_class_options, } = useEnum('default.sys_error_logs.exception_class')

const searchParams = reactive({
    controller: '',
    root: '',
    ip: '',
    code: '',
    include_exception_class: [] as string[],
    exclude_exception_class: [] as string[],
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
    requestFun: getSysErrorLogsPages,
    searchParams: searchParams

})
getTableList()

const sysErrorLogsDetailDialog: Record<string, any> | null = ref(null)


const handleView = (id: any) => {
    sysErrorLogsDetailDialog.value.setDialogData(id)
    sysErrorLogsDetailDialog.value?.openDialog()
}

// 是否开启错误日志
const error_log_enabled = ref('1');
// 获取错误日志开关配置
const fetchErrorLogEnabled = async () => {
    try {
        let key = 'error_log_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            error_log_enabled.value = response.data.config_value || '1';
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchErrorLogEnabled();

// 更新错误日志开关配置
const updateErrorLogEnabled = async () => {
    try {
        let key = 'error_log_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'system',
            config_key: key,
            config_value: error_log_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新错误日志配置成功');
        } else {
            ElMessage.error('更新错误日志配置失败');
        }
    } catch (error) {
        console.error('更新系统配置时出错:', error);
        ElMessage.error('更新错误日志配置失败');
    }
}




</script>