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
import { getSysAccessLogsInfo } from '@/pages-admin/main/api/system/sysAccessLogs';

const dialogVisible = ref(false);
const logDetail = ref([]);
const loading = ref(false);
let popTitle: string = '';


const setDialogData = async (logId: number) => {
    loading.value = true;
    try {
        const res = await getSysAccessLogsInfo(logId);
        logDetail.value = [
            { field: '访问者ID', value: res.data.user_id },
            { field: '用户名', value: res.data.username },
            { field: 'IP', value: res.data.ip },
            { field: '访问类型', value: res.data.method },
            { field: '访问跟目录', value: res.data.root },
            { field: '控制器', value: res.data.controller },
            { field: '方法', value: res.data.action },
            { field: 'url', value: res.data.url },
            { field: '请求参数', value: res.data.params },
            { field: '请求耗时(毫秒)', value: res.data.duration },
            { field: 'HTTP状态码', value: res.data.http_code },
            { field: '返回CODE', value: res.data.code },
            { field: '操作时间', value: res.data.create_at },
            { field: '请求结果', value: res.data.result },
        ];
        popTitle = '日志详情';
    } catch (error) {
        console.error('获取日志详情失败:', error);
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

<style scoped>
/* 添加样式 */
</style>
