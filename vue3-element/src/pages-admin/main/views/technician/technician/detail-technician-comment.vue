<template>
    <el-card shadow="never">
        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="60" />
            <el-table-column label="订单ID" prop="order_id" width="100">
                <template #default="{ row }">
                    <div class="text-blue-500 cursor-pointer">
                        {{ row.order_id }}
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="评价用户" min-width="120">
                <template #default="{ row }">
                    <div class="flex items-center">
                        <span class="text-blue-500 cursor-pointer">
                            {{ row.user?.username || '未知用户' }}
                        </span>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="评价内容" prop="content" min-width="200" show-overflow-tooltip />
            <el-table-column label="服务评分" min-width="150">
                <template #default="{ row }">
                    <el-rate v-model="row.service_score" disabled show-score text-color="#ff9900" size="small" />
                </template>
            </el-table-column>
            <el-table-column label="匿名评价" min-width="90">
                <template #default="{ row }">
                    <el-tag :type="row.is_anonymous == '1' ? 'warning' : 'info'">
                        {{ row.is_anonymous == '1' ? '匿名' : '实名' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="评价状态" min-width="90">
                <template #default="{ row }">
                    <el-tag :type="row.is_show == '1' ? 'success' : 'danger'">
                        {{ row.is_show == '1' ? '显示中' : '已隐藏' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="回复状态" min-width="90">
                <template #default="{ row }">
                    <el-tag :type="row.is_reply == '1' ? 'success' : 'info'">
                        {{ row.is_reply == '1' ? '已回复' : '未回复' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="回复内容" prop="reply_content" min-width="150" show-overflow-tooltip />
            <el-table-column label="评价时间" prop="create_at" min-width="160" />
            <el-table-column label="回复时间" prop="reply_time" min-width="160" />
            <el-table-column label="操作" min-width="160" fixed="right">
                <template #default="{ row }">
                    <el-button :type="row.is_show == '1' ? 'danger' : 'success'" link
                        @click="handleToggleField(row, 'is_show')">
                        {{ row.is_show == '1' ? '隐藏' : '显示' }}
                    </el-button>
                    <el-button type="primary" link @click="handleToggleField(row, 'is_anonymous')">
                        {{ row.is_anonymous == '1' ? '取消匿名' : '设为匿名' }}
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination :current-page="tableData.page_current" :page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList"
                @current-change="getTableList" />
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { getTechnicianCommentPages, toggleTechnicianCommentField } from '@/pages-admin/main/api/technician/technicianComment'
import { reactive, watch, ref } from 'vue'
import { usePagination } from '@/hooks/usePagination'
import { ElMessage, ElMessageBox } from 'element-plus'

// 组件名称
defineOptions({
    name: 'TechnicianDetailComment'
})

const props = defineProps({
    // 师傅ID
    technician_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    technician_id: props.technician_id,
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTechnicianCommentPages,
    searchParams: searchParams
})

// 监听 technician_id 的变化并更新 searchParams
watch(
    () => props.technician_id,
    (newTechnicianId) => {
        searchParams.technician_id = newTechnicianId;
        getTableList(); // 重新获取数据
    }
);

// 切换字段状态
const handleToggleField = async (row: any, field: string) => {
    try {
        const actionName = getActionName(field, row[field])

        await ElMessageBox.confirm(`确定要${actionName}吗？`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })

        await toggleTechnicianCommentField({
            id: row.id,
            field: field
        })

        ElMessage.success(`${actionName}成功`)
        getTableList()
    } catch (error) {
        if (error !== 'cancel') {
            console.error('操作失败', error)
            ElMessage.error('操作失败')
        }
    }
}

// 获取操作名称
const getActionName = (field: string, currentValue: string): string => {
    const actionMap: Record<string, Record<string, string>> = {
        is_show: {
            '1': '隐藏评价',
            '0': '显示评价'
        },
        is_anonymous: {
            '1': '取消匿名',
            '0': '设为匿名'
        },
        is_reply: {
            '1': '标记未回复',
            '0': '标记已回复'
        }
    }
    return actionMap[field]?.[currentValue] || '切换状态'
}




</script>
