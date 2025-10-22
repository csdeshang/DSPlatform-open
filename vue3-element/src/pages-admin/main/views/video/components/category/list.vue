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
            <el-table-column label="分类类型" prop="type_desc" min-width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.type === 'short'" type="primary">短视频</el-tag>
                    <el-tag v-else-if="row.type === 'drama'" type="success">短剧</el-tag>
                    <el-tag v-else-if="row.type === 'live'" type="warning">直播</el-tag>
                    <el-tag v-else type="info">未知</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="描述" prop="description" min-width="150" show-overflow-tooltip />
            <el-table-column label="是否显示" prop="is_show" min-width="100">
                <template #default="{ row }">
                    <el-switch 
                        v-model="row.is_show" 
                        :active-value="1" 
                        :inactive-value="0"
                        @change="handleToggleShow(row)"
                    />
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" min-width="80"></el-table-column>
            <el-table-column label="更新时间" prop="update_at" min-width="180"></el-table-column>
            <el-table-column label="操作" width="160" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleAdd(row.id)">
                        新增
                    </el-button>
                    <el-button type="primary" link @click="handleEdit(row)">
                        编辑
                    </el-button>
                    <el-button type="danger" link @click="handleDelete(row.id)">
                        删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <VideoCategoryEdit :type="props.type" ref="editVideoCategoryDialog" @complete="fetchVideoCategoryTree()" />
    </div>
</template>

<script lang="ts" setup name="VideoCategoryList">
import { reactive, ref } from 'vue';
import { ElMessageBox, ElMessage } from 'element-plus';
import VideoCategoryEdit from './edit.vue'
import { getVideoCategoryTree, deleteVideoCategory, toggleVideoCategoryField } from '@/pages-admin/main/api/video/category';

const props = defineProps({
    // 分类类型：short/drama/live
    type: {
        type: String,
        required: true
    },
})

const searchParams = reactive({
    type: props.type
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载列表
const fetchVideoCategoryTree = () => {
    tableData.loading = true;
    getVideoCategoryTree({
        ...searchParams
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}

// 初始化列表
fetchVideoCategoryTree();

const editVideoCategoryDialog: Record<string, any> | null = ref(null)

/**
 * 添加
 */
const handleAdd = (parentId?: number) => {
    if (parentId) {
        editVideoCategoryDialog.value.setDialogData({ pid: parentId });
    } else {
        editVideoCategoryDialog.value.setDialogData({ pid: 0 });
    }
    editVideoCategoryDialog.value?.openDialog()
}

/**
 * 编辑
 */
const handleEdit = (row: any) => {
    editVideoCategoryDialog.value.setDialogData(row);
    editVideoCategoryDialog.value?.openDialog()
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
        deleteVideoCategory(id).then(() => {
            ElMessage.success('删除成功')
            fetchVideoCategoryTree()
        }).catch(() => {
        })
    })
}

/**
 * 切换显示状态
 */
const handleToggleShow = (row: any) => {
    toggleVideoCategoryField({
        id: row.id,
        field: 'is_show'
    }).then(() => {
        ElMessage.success('状态切换成功')
        fetchVideoCategoryTree()
    }).catch(() => {
        // 切换失败时恢复原状态
        row.is_show = row.is_show === 1 ? 0 : 1
    })
}
</script>
