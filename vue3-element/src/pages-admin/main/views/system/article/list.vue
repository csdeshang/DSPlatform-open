<template>

    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="文章标题">
                <el-input v-model="searchParams.title" placeholder="请输入文章标题" clearable />
            </el-form-item>
            <el-form-item label="分类">
                <el-tree-select
                    v-model="searchParams.cid"
                    :data="categoryList"
                    node-key="id"
                    :props="{
                        label: 'name',
                        children: 'children'
                    }"
                    :default-expand-all="false"
                    placeholder="请选择分类"
                    clearable
                    check-strictly
                    class="w-[240px]"
                />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>

    <el-card shadow="never">
        <el-button type="primary" @click="handleAdd()">新增</el-button>

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="ID" prop="id" width="80" />
            <el-table-column label="分类" prop="category_name" width="120" />
            <el-table-column label="标题" prop="title" min-width="200" show-overflow-tooltip />
            <el-table-column label="封面图" width="100">
                <template #default="{ row }">
                    <el-image 
                        v-if="row.image"
                        style="width: 60px; height: 60px"
                        :src="formatImageUrl(row.image, ThumbnailPresets.small)"
                        fit="cover"
                    />
                    <span v-else>无图片</span>
                </template>
            </el-table-column>
            <el-table-column label="发布作者" prop="publish_author" width="120" />
            <el-table-column label="发布时间" width="160">
                <template #default="{ row }">
                    {{ row.publish_time }}
                </template>
            </el-table-column>
            <el-table-column label="浏览量" width="160">
                <template #default="{ row }">
                    {{ row.actual_views }} / {{ row.virtual_views }} <el-tooltip content="实际浏览/虚拟浏览" placement="top"><el-icon><InfoFilled /></el-icon></el-tooltip>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" width="80" />
            <el-table-column label="状态" width="100">
                <template #default="{ row }">
                    <el-tag :type="row.is_show ? 'success' : 'info'">
                        {{ row.is_show ? '显示' : '隐藏' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="创建时间" width="160">
                <template #default="{ row }">
                    {{ row.create_at }}
                </template>
            </el-table-column>
            <el-table-column label="操作" align="right" fixed="right" width="160">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                    <el-button type="danger" link @click="handleDelete(row.id)">删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>
    </el-card>


    <SysArticleEdit ref="editSysArticleDialog" @complete="getTableList" />

</template>
<script lang="ts" setup>

import { reactive, ref, onMounted } from 'vue';
import { getSysArticlePages, deleteBatchSysArticle, getSysArticleCategoryTree } from '@/pages-admin/main/api/system/sysArticle'
import { usePagination } from '@/hooks/usePagination'
import { ElMessage, ElMessageBox } from 'element-plus';
import { InfoFilled } from '@element-plus/icons-vue';
import SysArticleEdit from './edit.vue'
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'



const categoryList = ref<any[]>([]);

// 获取分类列表
const fetchSysArticleCategoryTree = async () => {
    try {
        const res = await getSysArticleCategoryTree({});
        categoryList.value = res.data || [];
    } catch (error) {
        console.error('获取文章分类失败', error);
    }
};

onMounted(() => {
    fetchSysArticleCategoryTree();
});

const searchParams = reactive({
    title: '',
    cid: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysArticlePages,
    searchParams: searchParams
})
getTableList()


// 新增 编辑
const editSysArticleDialog: Record<string, any> | null = ref(null)
const handleAdd = () => {
    editSysArticleDialog.value?.setDialogData({})
    editSysArticleDialog.value?.openDialog()
}
const handleEdit = (data: any) => {
    editSysArticleDialog.value?.setDialogData(data)
    editSysArticleDialog.value?.openDialog()
}

// 删除文章
const handleDelete = (id: number) => {
    ElMessageBox.confirm('确定要删除这篇文章吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
    }).then(() => {
        deleteBatchSysArticle({ ids: [id] }).then(() => {
            ElMessage.success('删除成功');
            getTableList();
        }).catch((error: any) => {
            console.error('删除文章失败', error);
            ElMessage.error('删除失败');
        });
    }).catch(() => {
        // 取消删除
    });
};

</script>