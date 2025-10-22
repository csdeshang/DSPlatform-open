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
import { getSysNoticeLogInfo } from '@/pages-admin/main/api/system/sysNotice';

const dialogVisible = ref(false);
const logDetail = ref<Array<{ field: string, value: any }>>([]);
const loading = ref(false);
let popTitle = ref('消息通知详情');


const setDialogData = async (logId: number) => {
    loading.value = true;
    try {
        const res = await getSysNoticeLogInfo(logId);
        
        if (res.code === 10000 && res.data) {
            logDetail.value = [
                { field: 'ID', value: res.data.id },
                { field: '用户ID', value: res.data.user_id },
                { field: '模板标识', value: res.data.key },
                { field: '通知渠道', value: res.data.notice_channel_desc },
                { field: '接收者', value: res.data.receiver || '无' },
                { field: '标题', value: res.data.title || '无标题' },
                { field: '内容', value: res.data.content },
                { field: '是否已读', value: res.data.is_read },
                { field: '发送状态', value: res.data.send_status },
                { field: '发送请求参数', value: res.data.send_params },
                { field: '发送结果', value: res.data.send_result },
                { field: '创建时间', value: res.data.create_at }
            ];
            
            popTitle.value = `消息通知详情 #${res.data.id}`;
        } else {
            logDetail.value = [{ field: '错误', value: '未找到消息通知记录或数据异常' }];
        }
    } catch (error) {
        console.error('获取消息通知详情失败:', error);
        logDetail.value = [{ field: '错误', value: '获取消息通知详情失败' }];
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
