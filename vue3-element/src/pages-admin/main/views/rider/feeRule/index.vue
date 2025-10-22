<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="规则名称">
                <el-input v-model="searchParams.rule_name" placeholder="请输入规则名称" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>

        <div class="mb-[10px]">
            <el-button type="primary" @click="handleAddRule">新增规则</el-button>
        </div>
    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="rule_name" label="规则名称" width="120" />
            <el-table-column prop="base_fee" label="基础配送费" width="100" />
            <el-table-column label="距离费用" width="100">
                <template #default="{ row }">
                    {{ row.distance_fee_type === 1 ? '阶梯式' : '连续式' }}
                </template>
            </el-table-column>
            <el-table-column label="重量费用" width="100">
                <template #default="{ row }">
                    {{ row.weight_fee_type === 0 ? '不计算' : row.weight_fee_type === 1 ? '阶梯式' : '连续式' }}
                </template>
            </el-table-column>
            <el-table-column label="时段费用" width="100">
                <template #default="{ row }">
                    {{ row.time_period_fee_type === 0 ? '不计算' : '按时段加价' }}
                </template>
            </el-table-column>
            <el-table-column label="天气费用" width="100">
                <template #default="{ row }">
                    {{ row.weather_fee_type === 0 ? '不计算' : '恶劣天气加价' }}
                </template>
            </el-table-column>
            <el-table-column prop="rider_fee_rate" label="平台抽成(%)" width="100" />
            <el-table-column label="状态" width="80">
                <template #default="{ row }">
                    <el-tag :type="row.is_enabled ? 'success' : 'danger'">
                        {{ row.is_enabled ? '启用' : '禁用' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="create_at" label="创建时间" width="150" />
            <el-table-column label="操作" width="200" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" size="small" plain @click="handleEditRule(row)">编辑</el-button>
                    <el-button :type="row.is_enabled ? 'danger' : 'success'" size="small" plain
                        @click="handleToggleStatus(row)">
                        {{ row.is_enabled ? '禁用' : '启用' }}
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </el-card>

    <FeeRuleForm ref="feeRuleFormRef" @refresh="getTableList" />

</template>
<script lang="ts" setup name="RiderFeeRuleIndex">

import { reactive, ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';
import { getRiderFeeRulePages, updateRiderFeeRuleStatus } from '@/pages-admin/main/api/rider/feeRule';
import { usePagination } from '@/hooks/usePagination';
import FeeRuleForm from './form.vue';

const searchParams = reactive({
    rule_name: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getRiderFeeRulePages,
    searchParams: searchParams
})

getTableList();

// 表单弹窗
const feeRuleFormRef = ref();

// 新增规则
const handleAddRule = () => {
    feeRuleFormRef.value.openDialog();
}

// 编辑规则
const handleEditRule = (row) => {
    feeRuleFormRef.value.openDialog(row);
}

// 切换启用状态
const handleToggleStatus = (row) => {
    const statusText = row.is_enabled ? '禁用' : '启用';

    ElMessageBox.confirm(`确定要${statusText}该规则吗？`, '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
    }).then(async () => {
        try {
            await updateRiderFeeRuleStatus({
                id: row.id,
                is_enabled: row.is_enabled ? 0 : 1
            });
            ElMessage.success(`${statusText}成功`);
            getTableList();
        } catch (error) {
            console.error(error);
            ElMessage.error(`${statusText}失败`);
        }
    }).catch(() => { });
}

</script>
