<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="标题">
                    <el-input v-model="searchParams.title" placeholder="请输入视频标题" clearable />
                </el-form-item>
                <el-form-item label="博主ID">
                    <el-input v-model="searchParams.blogger_id" placeholder="请输入博主ID" clearable />
                </el-form-item>
                <el-form-item label="内容类型">
                    <el-select v-model="searchParams.type" placeholder="请选择内容类型" clearable style="width: 120px">
                        <el-option label="视频" value="1" />
                        <el-option label="图片" value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item label="审核状态">
                    <el-select v-model="searchParams.audit_status" placeholder="请选择审核状态" clearable style="width: 120px">
                        <el-option label="审核中" value="0" />
                        <el-option label="已发布" value="1" />
                        <el-option label="已下架" value="2" />
                    </el-select>
                </el-form-item>
                <el-form-item label="是否推荐">
                    <el-select v-model="searchParams.is_recommend" placeholder="请选择" clearable style="width: 120px">
                        <el-option label="推荐" value="1" />
                        <el-option label="不推荐" value="0" />
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
                <el-table-column label="封面" min-width="100">
                    <template #default="{ row }">
                        <el-image 
                            v-if="row.cover_image" 
                            :src="formatImageUrl(row.cover_image, ThumbnailPresets.medium)" 
                            fit="cover"
                            style="width: 60px; height: 40px; border-radius: 4px;"
                        />
                        <span v-else>--</span>
                    </template>
                </el-table-column>
                <el-table-column label="标题" prop="title" min-width="150" show-overflow-tooltip />
                <el-table-column label="博主" min-width="120">
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleBloggerDetail(row.blogger_id)">
                            {{ row.blogger_name || `ID:${row.blogger_id}` }}
                        </el-link>
                    </template>
                </el-table-column>
                <el-table-column label="类型" min-width="80">
                    <template #default="{ row }">
                        <el-tag :type="row.type === 1 ? 'primary' : 'success'">
                            {{ row.type === 1 ? '视频' : '图片' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="描述" prop="description" min-width="200" show-overflow-tooltip />
                <el-table-column label="数据统计" min-width="180">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <div>观看：{{ row.view_count || 0 }}</div>
                            <div>点赞：{{ row.like_count || 0 }}</div>
                            <div>评论：{{ row.comment_count || 0 }}</div>
                            <div>收藏：{{ row.collect_count || 0 }}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="状态标签" min-width="120">
                    <template #default="{ row }">
                        <div class="text-sm">
                            <el-tag v-if="row.is_recommend" type="warning" size="small" class="mb-1">推荐</el-tag>
                            <el-tag v-if="row.is_top" type="danger" size="small" class="mb-1">置顶</el-tag>
                            <el-tag v-if="row.is_hot" type="success" size="small">热门</el-tag>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="审核状态" min-width="100">
                    <template #default="{ row }">
                        <el-tag :type="getAuditStatusType(row.audit_status)">
                            {{ getAuditStatusText(row.audit_status) }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="发布时间" min-width="160">
                    <template #default="{ row }">
                        {{ row.publish_at || '--' }}
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" min-width="160" />
                <el-table-column label="操作" align="right" fixed="right" width="200">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <el-button type="primary" link @click="handleDetail(row.id)">详情</el-button>
                            <el-dropdown class="ml-[10px]" @command="(command) => handleMore(command, row)">
                                <el-button type="primary" link>更多<el-icon><arrow-down /></el-icon></el-button>
                                <template #dropdown>
                                    <el-dropdown-menu>
                                        <el-dropdown-item command="edit">编辑</el-dropdown-item>
                                        <el-dropdown-item command="audit" v-if="row.audit_status === 0">审核</el-dropdown-item>
                                        <el-dropdown-item command="toggleRecommend">
                                            {{ row.is_recommend ? '取消推荐' : '设为推荐' }}
                                        </el-dropdown-item>
                                        <el-dropdown-item command="toggleTop">
                                            {{ row.is_top ? '取消置顶' : '设为置顶' }}
                                        </el-dropdown-item>
                                        <el-dropdown-item command="toggleHot">
                                            {{ row.is_hot ? '取消热门' : '设为热门' }}
                                        </el-dropdown-item>
                                        <el-dropdown-item command="offline" v-if="row.audit_status === 1">下架</el-dropdown-item>
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

        <video-short-detail ref="detailVideoShortDialog" @complete="getTableList()" />
        <video-short-audit ref="auditVideoShortDialog" @complete="getTableList()" />
        <blogger-detail ref="detailBloggerDialog" />
    </div>
</template>

<script lang="ts" setup name="VideoShortIndex">
import { reactive, ref } from 'vue';
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'
import { getVideoShortPages, toggleVideoShortField, auditVideoShort } from '@/pages-admin/main/api/video/short'
import { usePagination } from '@/hooks/usePagination'
import { ElMessage, ElMessageBox } from 'element-plus'

import VideoShortDetail from './detail.vue'
import VideoShortAudit from './audit.vue'
import BloggerDetail from '../blogger/detail.vue'

const searchParams = reactive({
    title: '',
    blogger_id: '',
    type: '',
    audit_status: '',
    is_recommend: '',
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getVideoShortPages,
    searchParams: searchParams
})

getTableList()

// 更多操作
const handleMore = (command: string, row: any) => {
    switch (command) {
        case 'edit':
            handleEdit(row);
            break;
        case 'audit':
            handleAudit(row);
            break;
        case 'toggleRecommend':
            handleToggleField(row, 'is_recommend');
            break;
        case 'toggleTop':
            handleToggleField(row, 'is_top');
            break;
        case 'toggleHot':
            handleToggleField(row, 'is_hot');
            break;
        case 'offline':
            handleOffline(row);
            break;
        default:
            console.log('未知命令', command);
    }
}

// 短视频详情
const detailVideoShortDialog = ref()
const handleDetail = (video_id: any) => {
    detailVideoShortDialog.value.setDialogData({ id: video_id })
    detailVideoShortDialog.value?.openDialog()
}

// 编辑短视频 - 直接在详情页面编辑
const handleEdit = (row: any) => {
    // 打开详情页面进行编辑
    handleDetail(row.id)
}

// 审核短视频
const auditVideoShortDialog = ref()
const handleAudit = (row: any) => {
    auditVideoShortDialog.value.setDialogData(row)
    auditVideoShortDialog.value?.openDialog()
}

// 博主详情弹窗
const detailBloggerDialog = ref()
const handleBloggerDetail = (blogger_id: any) => {
    detailBloggerDialog.value.setDialogData({ id: blogger_id })
    detailBloggerDialog.value?.openDialog()
}

// 下架视频
const handleOffline = async (row: any) => {
    try {
        await ElMessageBox.confirm('确定要下架该视频吗？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })
        
        await auditVideoShort({
            id: row.id,
            audit_status: 2,
            audit_remark: '管理员下架'
        })
        
        ElMessage.success('下架成功')
        getTableList()
    } catch (error) {
        if (error !== 'cancel') {
            console.error('下架失败', error)
            ElMessage.error('下架失败')
        }
    }
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
        
        await toggleVideoShortField({
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
        is_recommend: {
            '1': '取消推荐',
            '0': '设为推荐'
        },
        is_top: {
            '1': '取消置顶',
            '0': '设为置顶'
        },
        is_hot: {
            '1': '取消热门',
            '0': '设为热门'
        }
    }
    return actionMap[field]?.[currentValue.toString()] || '切换状态'
}

// 审核状态相关方法
const getAuditStatusType = (status: number) => {
    const typeMap: Record<number, string> = {
        0: 'warning',
        1: 'success',
        2: 'danger'
    }
    return typeMap[status] || 'info'
}

const getAuditStatusText = (status: number) => {
    const textMap: Record<number, string> = {
        0: '审核中',
        1: '已发布',
        2: '已下架'
    }
    return textMap[status] || '未知'
}
</script>