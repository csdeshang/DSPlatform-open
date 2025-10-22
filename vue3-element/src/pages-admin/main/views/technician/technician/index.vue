<template>
    <div class="technician-list">
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="师傅名称">
                    <el-input v-model="searchParams.name" placeholder="师傅名称" clearable />
                </el-form-item>
                <el-form-item label="手机号">
                    <el-input v-model="searchParams.mobile" placeholder="手机号" clearable />
                </el-form-item>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
                </el-form-item>
                <el-form-item label="审核状态">
                    <el-select v-model="searchParams.apply_status" placeholder="审核状态" clearable class="w-[150px]">
                        <el-option label="审核中" :value="0" />
                        <el-option label="审核通过" :value="1" />
                        <el-option label="审核拒绝" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item label="师傅状态">
                    <el-select v-model="searchParams.technician_status" placeholder="师傅状态" clearable class="w-[150px]">
                        <el-option label="休息" :value="0" />
                        <el-option label="接单" :value="1" />
                        <el-option label="忙碌" :value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item label="是否可用">
                    <el-select v-model="searchParams.is_enabled" placeholder="是否可用" clearable class="w-[150px]">
                        <el-option label="禁用" :value="0" />
                        <el-option label="可用" :value="1" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" min-width="60" />
                <el-table-column label="关联用户" prop="user.id" min-width="100">
                    <template #default="{ row }">
                        <el-link v-if="row.user" type="primary" :underline="false"
                            @click="handleUserDetail(row.user.id)">
                            {{ row.user.username }}
                        </el-link>
                        <span v-else class="text-gray-400">-</span>
                    </template>
                </el-table-column>
                <el-table-column label="所属店铺" prop="store.id" min-width="100">
                    <template #default="{ row }">
                        <el-link v-if="row.store" type="primary" :underline="false"
                            @click="handleStoreDetail(row.store.id)">
                            {{ row.store.store_name }}
                        </el-link>
                        <span v-else class="text-gray-400">-</span>
                    </template>
                </el-table-column>
                <el-table-column label="师傅名称" prop="name" min-width="100" />
                <el-table-column label="手机号" prop="mobile" min-width="120" />
                <el-table-column label="性别" prop="gender" min-width="60">
                    <template #default="{ row }">
                        <el-tag v-if="row.gender === 1" type="primary" size="small">男</el-tag>
                        <el-tag v-else-if="row.gender === 2" type="danger" size="small">女</el-tag>
                        <el-tag v-else type="info" size="small">未知</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="工作年限" prop="work_years" min-width="80">
                    <template #default="{ row }">{{ row.work_years }}年</template>
                </el-table-column>
                <el-table-column label="平均评分" prop="avg_score" min-width="100">
                    <template #default="{ row }">
                        <el-rate :model-value="Number(row.avg_score || 0)" disabled size="small" />
                    </template>
                </el-table-column>
                <el-table-column label="服务次数" prop="service_count" min-width="80" />
                <el-table-column label="余额" prop="balance" min-width="100">
                    <template #default="{ row }">
                        <span class="text-green-600 font-bold">¥{{ row.balance }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="总收入" prop="balance_in" min-width="100">
                    <template #default="{ row }">
                        <span class="text-blue-600">¥{{ row.balance_in }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="总支出" prop="balance_out" min-width="100">
                    <template #default="{ row }">
                        <span class="text-red-600">¥{{ row.balance_out }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="师傅状态" prop="technician_status" min-width="80">
                    <template #default="{ row }">
                        <el-tag v-if="row.technician_status === 0" type="info" size="small">休息</el-tag>
                        <el-tag v-else-if="row.technician_status === 1" type="success" size="small">接单</el-tag>
                        <el-tag v-else-if="row.technician_status === 2" type="warning" size="small">忙碌</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="是否可用" prop="is_enabled" min-width="80">
                    <template #default="{ row }">
                        <el-tag v-if="row.is_enabled === 1" type="success" size="small">可用</el-tag>
                        <el-tag v-else type="danger" size="small">禁用</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="审核状态" prop="apply_status" min-width="80">
                    <template #default="{ row }">
                        <el-tag v-if="row.apply_status === 0" type="warning" size="small">审核中</el-tag>
                        <el-tag v-else-if="row.apply_status === 1" type="success" size="small">审核通过</el-tag>
                        <el-tag v-else-if="row.apply_status === 2" type="danger" size="small">审核拒绝</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" min-width="160">
                    <template #default="{ row }">{{ row.create_at }}</template>
                </el-table-column>
                <el-table-column label="更新时间" prop="update_at" min-width="160">
                    <template #default="{ row }">{{ row.update_at }}</template>
                </el-table-column>
                <el-table-column label="操作" align="right" fixed="right" width="150">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleDetail(row.id)">详情</el-button>
                            <el-dropdown class="ml-[10px]" @command="(command: string) => handleMore(command, row)">
                                <el-button type="primary" link>更多<el-icon><arrow-down /></el-icon></el-button>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item command="balance">调整余额</el-dropdown-item>
                                        <el-dropdown-item command="bindStore">更换绑定店铺</el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 师傅详情弹窗 -->
        <TechnicianDetail ref="detailTechnicianDialog" @complete="getTableList()" />

        <!-- 调整余额弹窗 -->
        <TechnicianModifyBalance ref="modifyBalanceDialog" @complete="getTableList()" />

        <!-- 更换绑定店铺弹窗 -->
        <TechnicianModifyBindStore ref="modifyBindStoreDialog" @complete="getTableList()" />

        <!-- 会员详情弹窗 -->
        <UserDetail ref="detailUserDialog" @complete="getTableList()" />

        <!-- 店铺详情弹窗 -->
        <TblStoreDetail ref="handleStoreDetailRef" />
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { ArrowDown } from '@element-plus/icons-vue'
import { getTechnicianPage } from '@/pages-admin/main/api/technician/technician'
import { usePagination } from '@/hooks/usePagination'


import TechnicianDetail from './detail.vue'
import TechnicianModifyBalance from './modify-balance.vue'
import TechnicianModifyBindStore from './modify-bind-store.vue'
import UserDetail from '@/pages-admin/components/user/detail.vue'

const searchParams = reactive({
    name: '',
    mobile: '',
    username: '',
    apply_status: '',
    technician_status: '',
    is_enabled: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTechnicianPage,
    searchParams: searchParams
})

// 页面加载时获取数据
getTableList()

// 更多操作
const handleMore = (command: string, row: any) => {
    switch (command) {
        case 'balance':
            handleModifyBalance(row)
            break
        case 'bindStore':
            handleModifyBindStore(row)
            break
        default:
            console.log('未知命令', command)
    }
}

// 师傅详情
const detailTechnicianDialog = ref()
const handleDetail = (technicianId: number) => {
    detailTechnicianDialog.value.setDialogData({ id: technicianId })
    detailTechnicianDialog.value?.openDialog()
}

// 调整余额
const modifyBalanceDialog = ref()
const handleModifyBalance = (row: any) => {
    modifyBalanceDialog.value.setDialogData(row)
    modifyBalanceDialog.value?.openDialog()
}

// 更换绑定店铺
const modifyBindStoreDialog = ref()
const handleModifyBindStore = (row: any) => {
    modifyBindStoreDialog.value.setDialogData(row)
    modifyBindStoreDialog.value?.openDialog()
}

// 会员详情弹窗
const detailUserDialog = ref()
const handleUserDetail = (userId: number) => {
    detailUserDialog.value.setDialogData({ id: userId })
    detailUserDialog.value?.openDialog()
}

// 店铺详情弹窗
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
const handleStoreDetailRef = ref<any>()
const handleStoreDetail = (store_id: any) => {
    handleStoreDetailRef.value.setDialogData({ id: store_id })
    handleStoreDetailRef.value?.openDialog()
}
</script>

<style scoped>
.technician-list {
    padding: 0;
}
</style>