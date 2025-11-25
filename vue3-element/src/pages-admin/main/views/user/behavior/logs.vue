<template>
    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名">
                <el-input v-model="searchParams.username" placeholder="用户名" clearable />
            </el-form-item>
            <el-form-item label="行为类型">
                <el-select v-model="searchParams.behavior_type" placeholder="请选择行为类型" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in behavior_type_options" :key="item.value" :label="item.label"
                        :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="行为状态">
                <el-select v-model="searchParams.behavior_status" placeholder="请选择行为状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in behavior_status_options" :key="item.value" :label="item.label"
                        :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="风险等级">
                <el-select v-model="searchParams.risk_level" placeholder="请选择风险等级" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option v-for="item in risk_level_options" :key="item.value" :label="item.label"
                        :value="item.value" />
                </el-select>
            </el-form-item>
            <el-form-item label="IP地址">
                <el-input v-model="searchParams.ip_address" placeholder="IP地址" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="user_id" label="用户" width="120">
                <template #default="{ row }">
                    <el-link type="primary" :underline="false" @click="handleUserDetail(row.user_id)"
                        v-if="row.user_id">
                        {{ row.username }}
                    </el-link>
                    <span v-else>{{ row.username }}</span>
                </template>
            </el-table-column>
            <el-table-column prop="behavior_type_desc" label="行为类型" width="100" />
            <el-table-column prop="behavior_scene" label="行为场景" width="100" />
            <el-table-column prop="behavior_status_desc" label="行为状态" width="100">
                <template #default="{ row }">
                    <el-tag :type="row.behavior_status === 1 ? 'success' : 'danger'">
                        {{ row.behavior_status_desc }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="failure_reason" label="失败原因" width="150" />
            <el-table-column prop="ip_address" label="IP地址" width="120" />
            <el-table-column prop="device_type_desc" label="设备类型" width="100" />
            <el-table-column prop="browser" label="浏览器" width="100" />
            <el-table-column prop="os" label="操作系统" width="100" />
            <el-table-column prop="risk_level_desc" label="风险等级" width="100">
                <template #default="{ row }">
                    <el-tag :type="getRiskLevelType(row.risk_level)">
                        {{ row.risk_level_desc }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="is_abnormal_desc" label="异常状态" width="100">
                <template #default="{ row }">
                    <el-tag :type="row.is_abnormal === 1 ? 'warning' : 'success'">
                        {{ row.is_abnormal_desc }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="create_at" label="创建时间" width="150" />
            <el-table-column label="操作" align="right" fixed="right" width="180">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleView(row.id)">详情</el-button>
                    <el-button type="danger" link @click="handleDelete(row)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>

    <UserDetail ref="detailUserDialog" />
    <UserBehaviorLogDetail ref="userBehaviorLogDetailDialog" />
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus';
import { getUserBehaviorLogPages, deleteUserBehaviorLog } from '@/pages-admin/main/api/user/userBehavior'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'

// 使用枚举 Hook
const { options: behavior_type_options } = useEnum('default.user_behavior_log.behavior_type')
const { options: behavior_status_options } = useEnum('default.user_behavior_log.behavior_status')
const { options: risk_level_options } = useEnum('default.user_behavior_log.risk_level')

const searchParams = reactive({
    username: '',
    behavior_type: '',
    behavior_status: '',
    risk_level: '',
    ip_address: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserBehaviorLogPages,
    searchParams: searchParams
})

getTableList()

// 获取风险等级标签类型
const getRiskLevelType = (riskLevel: number) => {
    switch (riskLevel) {
        case 0: return 'info'    // 无风险
        case 1: return 'success' // 低风险
        case 2: return 'warning'  // 中风险
        case 3: return 'danger'   // 高风险
        default: return 'info'
    }
}

// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'

const detailUserDialog = ref()


const handleUserDetail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}

// 会员行为详情弹窗
import UserBehaviorLogDetail from './detail.vue'
const userBehaviorLogDetailDialog = ref()
const handleView = (id: any) => {
    userBehaviorLogDetailDialog.value.setDialogData(id)
    userBehaviorLogDetailDialog.value?.openDialog()
}

// 删除行为日志
const handleDelete = async (row: any) => {
    try {
        await ElMessageBox.confirm(
            `确定要删除这条行为日志吗？`,
            '提示',
            {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )
        
        const res = await deleteUserBehaviorLog(row.id)
        if (res.code === 10000) {
            ElMessage.success('删除成功')
            getTableList()
        }
        
        
    } catch (error: any) {
        if (error !== 'cancel') {
            ElMessage.error(error.message || '删除失败')
        }
    }
}
</script>
