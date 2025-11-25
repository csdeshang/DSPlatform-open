<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">
        <div v-if="loading" class="text-center">加载中...</div>
        <div v-else>
            <el-table :data="logDetail" size="large" class="min-w-full">
                <el-table-column label="字段" prop="field" class="font-bold" width="150" />
                <el-table-column label="值" prop="value" />
            </el-table>
        </div>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { getSysTaskQueueInfo } from '@/pages-admin/main/api/system/sysTaskQueue';

const dialogVisible = ref(false);
const logDetail = ref<any[]>([]);
const loading = ref(false);
let popTitle: string = '';

const setDialogData = async (id: number) => {
    loading.value = true;
    try {
        const res = await getSysTaskQueueInfo(id);
        const d = res.data || {};
        logDetail.value = [
            { field: '任务ID', value: d.id },
            { field: '任务类型', value: d.queue_type },
            { field: '队列分组', value: d.queue_group_desc },
            { field: '业务键', value: d.biz_key },
            { field: '任务载荷', value: d.payload },
            { field: '状态', value: d.status_desc },
            { field: '优先级', value: d.priority },
            { field: '重试次数', value: d.retry_count },
            { field: '最大重试', value: d.max_retries },
            { field: '错误信息', value: d.error_message },
            { field: '消费耗时(毫秒)', value: d.consume_ms },
            { field: '计划时间', value: d.scheduled_at },
            { field: '版本', value: d.version },
            { field: '创建时间', value: d.create_at },
            { field: '更新时间', value: d.update_at },
        ];
        popTitle = '任务详情';
    } catch (error) {
        console.error('获取任务详情失败:', error);
    } finally {
        loading.value = false;
    }
};

defineExpose({
    openDialog: () => {
        dialogVisible.value = true;
    },
    setDialogData
});
</script>
