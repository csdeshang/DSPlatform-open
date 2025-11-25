<template>
    <div>
        <!-- 积分商品分类列表 -->
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd()">新增</el-button>
            </div>
        </el-card>
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading" row-key="id"
            :tree-props="{ children: 'children', hasChildren: 'hasChildren' }">
            <el-table-column label="分类名称" prop="name" min-width="150" show-overflow-tooltip />
            <el-table-column label="图标" prop="image" min-width="100">
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
            <el-table-column label="排序" prop="sort" min-width="100"></el-table-column>
            <el-table-column label="创建时间" prop="create_at" min-width="180"></el-table-column>
            <el-table-column label="更新时间" prop="update_at" min-width="180"></el-table-column>
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

        <!-- 编辑对话框组件 -->
        <PointsGoodsCategoryEdit ref="editDialog" @complete="fetchPointsGoodsCategoryTree()" />
    </div>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'
import PointsGoodsCategoryEdit from './edit.vue'
import { 
    getPointsGoodsCategoryTree, 
    deletePointsGoodsCategory
} from '@/pages-admin/main/api/points-goods/pointsGoodsCategory';
import { ElMessageBox } from 'element-plus';

// 定义组件名称
defineOptions({
    name: 'PointsGoodsCategory'
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 编辑对话框引用
const editDialog: Record<string, any> | null = ref(null)

// 加载列表
const fetchPointsGoodsCategoryTree = () => {
    tableData.loading = true;
    getPointsGoodsCategoryTree({}).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}

// 添加
const handleAdd = (parentId?: number) => {
    if (parentId) {
        editDialog.value.setDialogData({ pid: parentId });
    } else {
        editDialog.value.setDialogData({ pid: 0 });
    }
    editDialog.value?.openDialog()
}

// 编辑
const handleEdit = (row: any) => {
    editDialog.value.setDialogData(row);
    editDialog.value?.openDialog()
}

// 删除
const handleDelete = (id: number) => {
    ElMessageBox.confirm('您确定是否删除此分类', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deletePointsGoodsCategory(id).then(() => {
            fetchPointsGoodsCategoryTree()
        }).catch(() => {
        })
    })
}

// 初始化列表
fetchPointsGoodsCategoryTree();
</script>