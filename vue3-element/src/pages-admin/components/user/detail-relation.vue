<template>
    <div>
        <!-- 推广关系树形表格 -->
        <el-table 
            :data="tableData.data" 
            style="width: 100%" 
            v-loading="tableData.loading"
            row-key="id"
            :tree-props="{ children: 'children', hasChildren: 'hasChildren' }" 
            lazy 
            :load="handleNodeExpand"
        >
            <el-table-column label="用户ID" prop="id" width="100" />
            
            <el-table-column label="用户信息" prop="username" width="200" />

            <el-table-column label="是否分销商" prop="is_distributor" width="200">
                <template #default="{ row }">
                    <el-tag v-if="row.is_distributor == 1" type="success" size="small">是</el-tag>
                    <el-tag v-else type="info" size="small">否</el-tag>
                </template>
            </el-table-column>

            <el-table-column label="佣金" prop="distributor_balance" width="200">
                <template #default="{ row }">
                    {{ row.is_distributor == 1 ? `¥${row.distributor_balance || '0.00'}` : '--' }}
                </template>
            </el-table-column>


            <el-table-column label="佣金总收入" prop="distributor_balance_in" width="200">
                <template #default="{ row }">
                    {{ row.is_distributor == 1 ? `¥${row.distributor_balance_in || '0.00'}` : '--' }}
                </template>
            </el-table-column>

            <el-table-column label="佣金总支出" prop="distributor_balance_out" width="200">
                <template #default="{ row }">
                    {{ row.is_distributor == 1 ? `¥${row.distributor_balance_out || '0.00'}` : '--' }}
                </template>
            </el-table-column>

            <el-table-column label="直推人数" prop="direct_count" width="100" />

            
            <el-table-column label="注册时间" prop="create_at" width="150">
                <template #default="{ row }">
                    {{ row.create_at ? new Date(row.create_at).toLocaleDateString() : '--' }}
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script lang="ts" setup>
// @ts-ignore
import { getUserRelationList } from '@/pages-admin/main/api/user/user'
import { reactive, watch } from 'vue';

const props = defineProps({
    user_id: {
        type: Number,
        default: 0
    },
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载子节点
const handleNodeExpand = (
    row: any,
    treeNode: unknown,
    resolve: (data: any[]) => void
) => {
    getUserRelationList({ 
        inviter_id: row.id , 
    }).then(res => {
        resolve(res.data || []);
    }).catch(() => {
        resolve([]);
    });
}

// 加载列表
const fetchUserRelationList = () => {
    tableData.loading = true;
    getUserRelationList({
        inviter_id: props.user_id
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data || []
    }).catch(() => {
        tableData.loading = false
    })
}

// 监听参数变化
watch(
    () => props.user_id,
    (newId) => {
        if (newId && newId > 0) {
            fetchUserRelationList();
        }
    },
    { immediate: true }
);
</script>

<style lang="scss" scoped>
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    
    .user-details {
        .phone {
            font-size: 12px;
            color: #909399;
            margin-top: 2px;
        }
    }
}

// 表格行的层级缩进优化
:deep(.el-table__row) {
    .cell {
        padding: 8px 0;
    }
}

// 树形表格展开图标优化
:deep(.el-table__expand-icon) {
    color: #409EFF;
}
</style>