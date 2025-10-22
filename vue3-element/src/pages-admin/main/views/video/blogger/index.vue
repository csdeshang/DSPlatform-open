<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="博主昵称">
                    <el-input v-model="searchParams.blogger_name" placeholder="请输入博主昵称" clearable />
                </el-form-item>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
                </el-form-item>
                <el-form-item label="认证状态">
                    <el-select v-model="searchParams.verification_status" placeholder="请选择认证状态" clearable style="width:120px">
                        <el-option v-for="item in blogger_verification_status_options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="认证类型">
                    <el-select v-model="searchParams.verification_type" placeholder="请选择认证类型" clearable style="width: 120px;">
                        <el-option v-for="item in blogger_verification_type_options" :key="item.value" :label="item.label" :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="是否可用">
                    <el-select v-model="searchParams.is_enabled" placeholder="请选择状态" clearable style="width: 120px;">
                        <el-option label="可用" value="1" />
                        <el-option label="禁用" value="0" />
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
                <el-table-column label="关联用户" prop="user_id" min-width="80">
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleUserDetail(row.user_id)">
                            {{ row.user_id }}
                        </el-link>
                    </template>
                </el-table-column>
                <el-table-column label="博主昵称" prop="blogger_name" min-width="120" />
                <el-table-column label="头像" min-width="80">
                    <template #default="{ row }">
                        <el-avatar v-if="row.avatar" :src="row.avatar" :size="40" />
                        <span v-else>--</span>
                    </template>
                </el-table-column>
                <el-table-column label="描述" prop="description" min-width="150" show-overflow-tooltip />
                <el-table-column label="统计信息" min-width="200">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <div>粉丝：{{ row.follower_count || 0 }}</div>
                            <div>关注：{{ row.following_count || 0 }}</div>
                            <div>视频：{{ row.video_count || 0 }}</div>
                            <div>短剧：{{ row.drama_count || 0 }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="数据统计" min-width="180">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <div>获赞：{{ row.total_likes || 0 }}</div>
                            <div>播放：{{ row.total_views || 0 }}</div>
                            <div>收藏：{{ row.total_collect || 0 }}</div>
                            <div>直播：{{ row.live_count || 0 }}次</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="认证状态" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="getVerificationStatusType(row.verification_status)">
                            {{ row.verification_status_desc }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="认证类型" prop="verification_type" min-width="100">
                    <template #default="{ row }">
                        <span v-if="row.verification_type === 'personal'">个人认证</span>
                        <span v-else-if="row.verification_type === 'enterprise'">企业认证</span>
                        <span v-else>--</span>
                    </template>
                </el-table-column>
                <el-table-column label="权限设置" min-width="120">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <el-tag :type="row.is_live_enabled ? 'success' : 'info'" size="small" class="mb-1">
                                {{ row.is_live_enabled ? '可直播' : '禁直播' }}
                            </el-tag>
                            <br />
                            <el-tag :type="row.is_drama_enabled ? 'success' : 'info'" size="small">
                                {{ row.is_drama_enabled ? '可短剧' : '禁短剧' }}
                            </el-tag>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="状态" min-width="80">
                    <template #default="{ row }">
                        <el-tag :type="row.is_enabled ? 'success' : 'danger'">
                            {{ row.is_enabled ? '可用' : '禁用' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" min-width="160" />
                <el-table-column label="操作" align="right" fixed="right" width="180">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleDetail(row.id)">详情</el-button>
                            <el-dropdown class="ml-[10px]" @command="(command) => handleMore(command, row)">
                                <el-button type="primary" link>更多<el-icon><arrow-down /></el-icon></el-button>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item command="edit">编辑信息</el-dropdown-item>
                                        <el-dropdown-item command="toggleEnabled">
                                            {{ row.is_enabled ? '禁用' : '启用' }}
                                        </el-dropdown-item>
                                        <el-dropdown-item command="toggleLive">
                                            {{ row.is_live_enabled ? '禁止直播' : '允许直播' }}
                                        </el-dropdown-item>
                                        <el-dropdown-item command="toggleDrama">
                                            {{ row.is_drama_enabled ? '禁止短剧' : '允许短剧' }}
                                        </el-dropdown-item>
                                    </el-dropdown-menu>
                                </template>
                            </el-dropdown>
                        </div>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination 
                    v-model:current-page="tableData.page_current" 
                    v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="tableData.total" 
                    @size-change="getTableList()"
                    @current-change="getTableList" 
                />
            </div>
        </el-card>

        <blogger-detail ref="detailBloggerDialog" @complete="getTableList()" />
        <user-detail ref="detailUserDialog" @complete="getTableList()" />
    </div>
</template>

<script lang="ts" setup name="BloggerIndex">
import { reactive, ref } from 'vue';
import { getBloggerPages, toggleBloggerField } from '@/pages-admin/main/api/video/blogger'
import { usePagination } from '@/hooks/usePagination'
import { ElMessage, ElMessageBox } from 'element-plus'

import BloggerDetail from './detail.vue'
import UserDetail from '@/pages-admin/components/user/detail.vue'

const searchParams = reactive({
    blogger_name: '',
    username: '',
    verification_status: '',
    verification_type: '',
    is_enabled: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getBloggerPages,
    searchParams: searchParams
})

getTableList()

import { useEnum } from '@/hooks/useEnum'
// 使用枚举 Hook
const { options: blogger_verification_status_options } = useEnum('default.blogger.verification_status')
const { options: blogger_verification_type_options } = useEnum('default.blogger.verification_type')

// 更多操作
const handleMore = (command: string, row: any) => {
    switch (command) {
        case 'edit':
            handleEdit(row);
            break;
        case 'toggleEnabled':
            handleToggleField(row, 'is_enabled');
            break;
        case 'toggleLive':
            handleToggleField(row, 'is_live_enabled');
            break;
        case 'toggleDrama':
            handleToggleField(row, 'is_drama_enabled');
            break;
        default:
            console.log('未知命令', command);
    }
}

// 博主详情
const detailBloggerDialog = ref()
const handleDetail = (blogger_id: any) => {
    detailBloggerDialog.value.setDialogData({ id: blogger_id })
    detailBloggerDialog.value?.openDialog()
}

// 编辑博主 - 直接在详情页面编辑
const handleEdit = (row: any) => {
    // 打开详情页面进行编辑
    handleDetail(row.id)
}

// 用户详情弹窗
const detailUserDialog = ref()
const handleUserDetail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}

// 切换字段状态
const handleToggleField = async (row: any, field: string) => {
    try {
        const actionName = getActionName(field, row[field])
        
        await ElMessageBox.confirm(`确定要${actionName}吗？`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })
        
        await toggleBloggerField({
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
const getActionName = (field: string, currentValue: number): string => {
    const actionMap: Record<string, Record<string, string>> = {
        is_enabled: {
            '1': '禁用博主',
            '0': '启用博主'
        },
        is_live_enabled: {
            '1': '禁止直播',
            '0': '允许直播'
        },
        is_drama_enabled: {
            '1': '禁止短剧',
            '0': '允许短剧'
        }
    }
    return actionMap[field]?.[currentValue.toString()] || '切换状态'
}

// 认证状态相关方法
const getVerificationStatusType = (status: number) => {
    const typeMap: Record<number, string> = {
        0: 'info',
        1: 'success',
        2: 'danger'
    }
    return typeMap[status] || 'info'
}


</script>