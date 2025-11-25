<template>

    <div>
        <!--
        <el-card shadow="never" class="mb-5">
            <el-form-item label="是否开启消息队列">
                <el-switch v-model="queue_enabled" size="large" @change="updateQueueEnabled" active-value="1"
                    inactive-value="0" />
            </el-form-item>
        </el-card>
        -->

        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="任务类型">
                    <el-input v-model="searchParams.queue_type" placeholder="请输入任务类型" clearable />
                </el-form-item>
                <el-form-item label="分组">
                    <el-input v-model="searchParams.queue_group" placeholder="请输入分组" clearable class="w-[160px]" />
                </el-form-item>
                <el-form-item label="业务键">
                    <el-input v-model="searchParams.biz_key" placeholder="请输入业务幂等键" clearable class="w-[220px]" />
                </el-form-item>
                <el-form-item label="状态">
                    <el-select v-model="searchParams.status" placeholder="请选择状态" clearable class="w-[160px]">
                        <el-option label="待处理" :value="0" />
                        <el-option label="完成" :value="1" />
                        <el-option label="失败" :value="2" />
                        <el-option label="处理中" :value="3" />
                    </el-select>
                </el-form-item>
                <el-form-item label="优先级≥">
                    <el-input v-model="searchParams.priority" placeholder="优先级" clearable class="w-[140px]" />
                </el-form-item>
                <el-form-item label="重试次数≥">
                    <el-input v-model="searchParams.retry_count" placeholder="重试次数" clearable class="w-[140px]" />
                </el-form-item>
                <el-form-item label="耗时(ms)">
                    <div class="flex items-center gap-2">
                        <el-input v-model="searchParams.consume_ms_min" placeholder="最小耗时" clearable
                            class="w-[140px]" />
                        <span class="text-gray-500">-</span>
                        <el-input v-model="searchParams.consume_ms_max" placeholder="最大耗时" clearable
                            class="w-[140px]" />
                    </div>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                    <el-button type="warning" @click="handleRecoverOrphanedTasks">恢复孤儿任务</el-button>
                    <el-button type="danger" @click="handleRetryFailedTasks">批量恢复失败任务</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="100" />
                <el-table-column label="任务类型" prop="queue_type" min-width="160" />
                <el-table-column label="分组" prop="queue_group_desc" min-width="120" />
                <el-table-column label="业务键" prop="biz_key" min-width="200" />
                <el-table-column label="状态" prop="status_desc" width="120" />
                <el-table-column label="优先级" prop="priority" width="100" />
                <el-table-column label="重试次数" prop="retry_count" width="120" />
                <el-table-column label="耗时(ms)" prop="consume_ms" width="120" />
                <el-table-column label="计划时间" prop="scheduled_at" width="180" />
                <el-table-column label="创建时间" prop="create_at" width="180" />
                <el-table-column label="更新时间" prop="update_at" width="180" />
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


        <SysTaskQueueDetail ref="sysTaskQueueDetailDialog" />

        
    </div>
</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';
import { usePagination } from '@/hooks/usePagination'
import { getSysTaskQueuePages, recoverOrphanedTasks, retryFailedTasks } from '@/pages-admin/main/api/system/sysTaskQueue'
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'
import SysTaskQueueDetail from './detail.vue'

const searchParams = reactive({
    queue_type: '',
    queue_group: '',
    biz_key: '',
    status: '',
    priority: '',
    retry_count: '',
    consume_ms_min: '',
    consume_ms_max: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysTaskQueuePages,
    searchParams: searchParams
})
getTableList()

const sysTaskQueueDetailDialog: Record<string, any> | null = ref(null)

const handleView = (id: any) => {
    sysTaskQueueDetailDialog.value.setDialogData(id)
    sysTaskQueueDetailDialog.value?.openDialog()
}

const handleRecoverOrphanedTasks = async () => {
    try {
        await ElMessageBox.confirm(
            '将恢复超过30分钟未更新的 PROCESSING 状态任务，是否继续？',
            '恢复孤儿任务',
            {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )

        const res = await recoverOrphanedTasks()
        if (res.code === 10000) {
            ElMessage.success(`成功恢复 ${res.data.count} 个孤儿任务`)
            getTableList() // 刷新列表
        }
    } catch (error: any) {
        if (error !== 'cancel') {
            console.error('恢复失败:', error)
            ElMessage.error('恢复失败：' + (error.message || '未知错误'))
        }
    }
}

const handleRetryFailedTasks = async () => {
    try {
        await ElMessageBox.confirm(
            '将恢复所有失败状态的任务，重置重试次数和错误信息，是否继续？',
            '批量恢复失败任务',
            {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )

        const res = await retryFailedTasks()
        if (res.code === 10000) {
            ElMessage.success(`成功恢复 ${res.data.count} 个失败任务`)
            getTableList() // 刷新列表
        }
    } catch (error: any) {
        if (error !== 'cancel') {
            console.error('恢复失败:', error)
            ElMessage.error('恢复失败：' + (error.message || '未知错误'))
        }
    }
}

// 是否开启消息队列
const queue_enabled = ref('0');
// 获取消息队列开关配置
const fetchQueueEnabled = async () => {
    try {
        let key = 'queue_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            queue_enabled.value = response.data.config_value || '0';
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchQueueEnabled();

// 更新消息队列开关配置
const updateQueueEnabled = async () => {
    try {
        let key = 'queue_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'system',
            config_key: key,
            config_value: queue_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新消息队列配置成功');
        } else {
            ElMessage.error('更新消息队列配置失败');
        }
    } catch (error) {
        console.error('更新系统配置时出错:', error);
        ElMessage.error('更新消息队列配置失败');
    }
}


</script>
