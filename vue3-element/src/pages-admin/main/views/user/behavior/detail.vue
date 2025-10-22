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
import { getUserBehaviorLogInfo } from '@/pages-admin/main/api/user/userBehavior';

const dialogVisible = ref(false);
const logDetail = ref([]);
const loading = ref(false);
let popTitle: string = '';

const setDialogData = async (logId: number) => {
    loading.value = true;
    try {
        const res = await getUserBehaviorLogInfo(logId);
        logDetail.value = [
            { field: 'ID', value: res.data.id },
            { field: '用户ID', value: res.data.user_id },
            { field: '用户名', value: res.data.username },
            { field: '行为类型', value: res.data.behavior_type_desc },
            { field: '行为场景', value: res.data.behavior_scene },
            { field: 'IP地址', value: res.data.ip_address },
            { field: '用户代理', value: res.data.user_agent },
            { field: '设备类型', value: res.data.device_type },
            { field: '浏览器', value: res.data.browser },
            { field: '操作系统', value: res.data.os },
            { field: '行为状态', value: res.data.behavior_status_desc },
            { field: '失败原因', value: res.data.failure_reason || '无' },
            { field: '是否异常', value: res.data.is_abnormal_desc },
            { field: '异常原因', value: res.data.abnormal_reason || '无' },
            { field: '风险等级', value: res.data.risk_level_desc },
            { field: '额外数据', value: res.data.extra_data || '无' },
            { field: '创建时间', value: res.data.create_at },
        ];
        popTitle = '用户行为日志详情';
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
