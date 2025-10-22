<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="900px" :destroy-on-close="true">
        <el-table :data="orderRefundLogList" style="width: 100%" v-loading="loading">
            <el-table-column prop="id" label="ID" width="100" />
            <el-table-column prop="refund_id" label="退款ID" width="120" />
            <el-table-column prop="refund_status" label="退款状态" width="120" />
            <el-table-column prop="message" label="日志描述" width="200" />
            <el-table-column prop="create_role" label="创建人角色" width="150" />
            <el-table-column prop="create_uid" label="创建人" width="100" />
            <el-table-column prop="create_at" label="创建时间" width="150" />
        </el-table>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { getTblOrderRefundLogList } from '@/pages-admin/main/api/tbl-order/tblOrderRefund';

const dialogVisible = ref(false);
const orderRefundLogList = ref([]);
const loading = ref(false);
let popTitle: string = '';

const setDialogData = async (row: any) => {
    loading.value = true;
    try {
        const res = await getTblOrderRefundLogList(row.id);
        orderRefundLogList.value = res.data;
        popTitle = '退款操作记录';
    } catch (error) {
        console.error('获取退款操作记录失败:', error);
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
