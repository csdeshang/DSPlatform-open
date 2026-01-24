<template>


    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="用户名/ID">
                <el-input v-model="searchParams.username" placeholder="用户名/ID" clearable />
            </el-form-item>
            <el-form-item label="手机号">
                <el-input v-model="searchParams.mobile" placeholder="请输入手机号" clearable />
            </el-form-item>
            <el-form-item label="邀请人ID">
                <el-input v-model="searchParams.inviter_id" placeholder="请输入邀请人ID" clearable />
            </el-form-item>
            <el-form-item label="是否启用">
                <el-select v-model="searchParams.is_enabled" placeholder="请选择状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option label="启用" value="1" />
                    <el-option label="禁用" value="0" />
                </el-select>
            </el-form-item>
            <el-form-item label="删除状态">
                <el-select v-model="searchParams.is_deleted" placeholder="请选择状态" clearable class="w-[120px]">
                    <el-option label="全部" value="" />
                    <el-option label="未删除" value="0" />
                    <el-option label="已删除" value="1" />
                </el-select>
            </el-form-item>
            <el-form-item label="可用金额">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.balance_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.balance_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="总收入">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.balance_in_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.balance_in_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item label="总支出">
                <div class="flex items-center gap-2">
                    <el-input v-model="searchParams.balance_out_min" placeholder="最小金额" clearable class="w-[120px]" />
                    <span class="text-gray-500">-</span>
                    <el-input v-model="searchParams.balance_out_max" placeholder="最大金额" clearable class="w-[120px]" />
                </div>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
        <el-button type="primary" @click="handleAdd()">添加</el-button>
    </el-card>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" min-width="60" />
            <el-table-column label="账号" prop="username" />
            <el-table-column label="可用金额" prop="balance" />
            <el-table-column label="总收入" prop="balance_in" />
            <el-table-column label="总支出" prop="balance_out" />
            <el-table-column label="积分" prop="points" />
            <el-table-column label="成长值" prop="growth" />
            <el-table-column label="最近登录" prop="login_time" />
            <el-table-column label="邀请人ID" prop="inviter_id" width="120" />
            <el-table-column label="操作" align="right" fixed="right" width="150">
                <template #default="{ row }">
                    <div class="flex flex-row">
                        <el-button type="primary" link @click="handleDtail(row.id)">详情</el-button>
                        <el-dropdown class="ml-[10px]" @command="(command) => handleMore(command, row)">
                            <el-button type="primary" link>更多<el-icon><arrow-down /></el-icon></el-button>
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item command="balance">调整余额</el-dropdown-item>
                                    <el-dropdown-item command="points">调整积分</el-dropdown-item>
                                    <el-dropdown-item command="growth">调整成长值</el-dropdown-item>
                                    <el-dropdown-item command="inviter">修改推荐人</el-dropdown-item>
                                    <el-dropdown-item v-if="row.is_deleted != 1" command="softDelete" divided>删除</el-dropdown-item>
                                    <el-dropdown-item v-if="row.is_deleted == 1" command="restore" divided>恢复</el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </div>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>


    </el-card>


    <UserAdd ref="createUserDialog" @complete="getTableList()" />
    <UserDetail ref="detailUserDialog" @complete="getTableList()" />
    <UserModifyBalance ref="modifyBalanceDialog" @complete="getTableList()"></UserModifyBalance>
    <UserModifyPoints ref="modifyPointsDialog" @complete="getTableList()"></UserModifyPoints>
    <UserModifyGrowth ref="modifyGrowthDialog" @complete="getTableList()"></UserModifyGrowth>
</template>


<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { ElMessageBox, ElMessage } from 'element-plus';

import { getUserPage, softDeleteUser, restoreUser } from '@/pages-admin/main/api/user/user'
import { usePagination } from '@/hooks/usePagination'

import UserAdd from './add.vue'
// 会员详情弹窗调用
import UserDetail from '@/pages-admin/components/user/detail.vue'
// 调整余额弹窗调用
import UserModifyBalance from './modify-balance.vue'
// 调整积分弹窗调用
import UserModifyPoints from './modify-points.vue'
// 调整成长值弹窗调用
import UserModifyGrowth from './modify-growth.vue'



const searchParams = reactive({
    username: '',
    mobile: '',
    inviter_id: '',
    is_enabled: '',
    is_deleted: '',
    balance_min: '',
    balance_max: '',
    balance_in_min: '',
    balance_in_max: '',
    balance_out_min: '',
    balance_out_max: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserPage,
    searchParams: searchParams
})
getTableList()

const createUserDialog = ref()

const handleAdd = () => {
    createUserDialog.value.setDialogData()
    createUserDialog.value?.openDialog()

}


/**
 * 软删除用户
 */
const handleSoftDelete = (id: number) => {
    ElMessageBox.confirm('确定要删除此用户吗？删除后可以恢复。', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        softDeleteUser(id).then(() => {
            ElMessage.success('删除成功')
            getTableList()
        }).catch(() => {
        })
    }).catch(() => {
        // 用户取消删除
    })
}

/**
 * 恢复用户
 */
const handleRestore = (id: number) => {
    ElMessageBox.confirm('确定要恢复此用户吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        restoreUser(id).then(() => {
            ElMessage.success('恢复成功')
            getTableList()
        }).catch(() => {
        })
    }).catch(() => {
        // 用户取消恢复
    })
}


// 会员详情
const detailUserDialog = ref()
const handleDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}


//更多
const handleMore = (command: string, row: any,) => {

    switch (command) {
        case 'balance':
            handleModifyBalance(row);
            break;
        case 'points':
            handleModifyPoints(row);
            break;
        case 'growth':
            handleModifyGrowth(row);
            break;
        case 'inviter':
            handleModifyInviter(row);
            break;
        case 'softDelete':
            handleSoftDelete(row.id);
            break;
        case 'restore':
            handleRestore(row.id);
            break;
        default:
            console.log('未知命令', command);
    }
}

//修改余额
const modifyBalanceDialog = ref()
const handleModifyBalance = (row: any) => {

    modifyBalanceDialog.value.setDialogData(row)
    modifyBalanceDialog.value?.openDialog()
}

//修改积分
const modifyPointsDialog = ref()
const handleModifyPoints = (row: any) => {
    modifyPointsDialog.value.setDialogData(row)
    modifyPointsDialog.value?.openDialog()
}

//修改成长值
const modifyGrowthDialog = ref()
const handleModifyGrowth = (row: any) => {
    modifyGrowthDialog.value.setDialogData(row)
    modifyGrowthDialog.value?.openDialog()
}

const handleModifyInviter = (row: any) => {
    // 实现修改推荐人的功能
    console.log('修改推荐人', row);
}

</script>