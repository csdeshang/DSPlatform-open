<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd()">新增</el-button>
            </div>
        </el-card>
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading" row-key="id"
            :tree-props="{ children: 'children', hasChildren: 'hasChildren' }">
            <el-table-column label="分类名称" prop="name" min-width="150" show-overflow-tooltip />
            <el-table-column label="分类图片" prop="image" min-width="100">
                <template #default="{ row }">
                    <el-image v-if="row.image" :src="formatImageUrl(row.image, ThumbnailPresets.small)" style="width: 50px; height: 50px" fit="cover" />
                </template>
            </el-table-column>
            <el-table-column label="是否显示" prop="is_show" min-width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.is_show == 1">是</el-tag>
                    <el-tag v-else type="danger">否</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" min-width="180"></el-table-column>
            <el-table-column label="更新时间" prop="update_time" min-width="180"></el-table-column>
            <el-table-column label="操作" width="160" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link
                        @click="handleAdd(row.id)">
                        新增
                    </el-button>
                    <el-button type="primary" link @click="handleEdit(row)">
                        编辑
                    </el-button>
                    <el-button type="danger" link
                        @click="handleDelete(row.id)">
                        删除
                    </el-button>
                </template>
            </el-table-column>


        </el-table>


        <SysArticleCategoryEdit ref="editArticleCategoryDialog" @complete="fetchSysArticleCategoryTree()" />


    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';

import { formatImageUrl, ThumbnailPresets } from '@/utils/image'

import SysArticleCategoryEdit from './category-edit.vue'
import { getSysArticleCategoryTree, deleteSysArticleCategory } from '@/pages-admin/main/api/system/sysArticle';
import { ElMessageBox } from 'element-plus';



const searchParams = reactive({
    
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchSysArticleCategoryTree = () => {
    tableData.loading = true;
    getSysArticleCategoryTree({
        ...searchParams
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchSysArticleCategoryTree();


const editArticleCategoryDialog: Record<string, any> | null = ref(null)

/**
 * 添加
 */
const handleAdd = (parentId?: number) => {
    if (parentId) {
        editArticleCategoryDialog.value.setDialogData({ pid: parentId });
    } else {
        editArticleCategoryDialog.value.setDialogData({ pid: 0 });
    }
    editArticleCategoryDialog.value?.openDialog()
}

/**
 * 编辑
 */
const handleEdit = (row: any) => {
    editArticleCategoryDialog.value.setDialogData(row);
    editArticleCategoryDialog.value?.openDialog()
}

/**
 * 删除
 */
const handleDelete = (id: number) => {
    ElMessageBox.confirm('您确定是否删除此分类', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteSysArticleCategory(id).then(() => {
            fetchSysArticleCategoryTree()
        }).catch(() => {
        })
    })
}


</script>