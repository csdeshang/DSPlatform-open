<template>
    <!-- 视频评论 短视频 短剧 直播-->
    <el-card shadow="never">
        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="100" />
            <el-table-column prop="pid" label="父级评论ID" width="120" />
            <el-table-column prop="content_type" label="内容类型" width="120" />

            <el-table-column label="用户信息" width="150">
                <template #default="{ row }">
                    <div v-if="row.user">
                        <div>{{ row.user.nickname || row.user.username }}</div>
                        <div class="text-gray-500 text-xs">ID: {{ row.user.id }}</div>
                    </div>
                    <div v-else class="text-gray-500">未知用户</div>
                </template>
            </el-table-column>
            <el-table-column prop="comment_content" label="评论内容" min-width="200" />
            <el-table-column prop="like_count" label="点赞数" width="100" />
            <el-table-column prop="reply_count" label="回复数" width="100" />
            <el-table-column label="显示状态" width="100">
                <template #default="{ row }">
                    <el-switch
                        v-model="row.is_show"
                        :active-value="1"
                        :inactive-value="0"
                        @change="handleToggleField(row.id, 'is_show')"
                    />
                </template>
            </el-table-column>
            <el-table-column label="置顶状态" width="100">
                <template #default="{ row }">
                    <el-switch
                        v-model="row.is_top"
                        :active-value="1"
                        :inactive-value="0"
                        @change="handleToggleField(row.id, 'is_top')"
                    />
                </template>
            </el-table-column>
            <el-table-column prop="create_at" label="评论时间" width="160" />
        </el-table>
        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { getVideoCommentPages, toggleVideoCommentField } from '@/pages-admin/main/api/video/videoComment'
import { reactive, watch } from 'vue';
import { usePagination } from '@/hooks/usePagination'
import { ElMessage } from 'element-plus'

const props = defineProps({
    // 内容ID
    content_id: {
        type: Number,
        default: 0
    },
    // 内容类型
    content_type: {
        type: String,
        default: ''
    }
})

const searchParams = reactive({
    content_id: props.content_id,
    content_type: props.content_type,
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getVideoCommentPages,
    searchParams: searchParams
})

// 切换字段状态
const handleToggleField = async (id: number, field: string) => {
    try {
        const res = await toggleVideoCommentField({ id, field })
        if (res.code == 10000) {
            ElMessage.success('操作成功')
            getTableList()
        } 
    } catch (error) {
        ElMessage.error('操作失败')
        getTableList()
    }
}

// 监听 content_id 和 content_type 的变化并更新 searchParams
watch(
    () => [props.content_id, props.content_type],
    ([newContentId, newContentType]) => {
        searchParams.content_id = newContentId as number;
        searchParams.content_type = newContentType as string;
        if ((newContentId as number) > 0 && newContentType) {
            getTableList(); // 重新获取数据
        }
    }
);

</script>
