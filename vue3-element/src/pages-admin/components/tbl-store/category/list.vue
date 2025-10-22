<template>
    <!-- 店铺分类列表 显示 TblStoreCategory 表中的数据 -->
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd()">新增</el-button>
            </div>
        </el-card>
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading" row-key="id"
            :tree-props="{ children: 'children', hasChildren: 'hasChildren' }">
            <el-table-column label="菜单名称" prop="name" min-width="150" show-overflow-tooltip />
            <el-table-column label="图标" prop="image" min-width="100">
                <template #default="{ row }">
                    <el-image v-if="row.image" :src="formatFileUrl(row.image)" style="width: 50px; height: 50px" fit="cover" />
                </template>
            </el-table-column>
            <el-table-column label="是否显示" prop="is_show" min-width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.is_show == 1">是</el-tag>
                    <el-tag v-else type="danger">否</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" min-width="180"></el-table-column>
            <el-table-column label="服务费率" prop="service_fee_rate" min-width="180"></el-table-column>
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


        <TblStoreCategoryEdit :platform="props.platform" ref="editTblStoreCategoryDialog" @complete="fetchTblStoreCategoryTree()" />


    </div>
</template>


<script lang="ts" setup>
import { reactive, ref } from 'vue';

import { formatFileUrl } from '@/utils/util'

import TblStoreCategoryEdit from './edit.vue'
import { getTblStoreCategoryTree, deleteTblStoreCategory } from '@/pages-admin/main/api/tbl-store/tblStoreCategory';
import { ElMessageBox } from 'element-plus';

const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})

const searchParams = reactive({
    platform: props.platform
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载列表
const fetchTblStoreCategoryTree = () => {
    tableData.loading = true;
    getTblStoreCategoryTree({
        ...searchParams
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchTblStoreCategoryTree();


// 编辑及添加
const editTblStoreCategoryDialog: Record<string, any> | null = ref(null)
const handleAdd = (parentId?: number) => {
    if (parentId) {
        editTblStoreCategoryDialog.value.setDialogData({ pid: parentId });
    } else {
        editTblStoreCategoryDialog.value.setDialogData({ pid: 0 });
    }
    editTblStoreCategoryDialog.value?.openDialog()
}
const handleEdit = (row: any) => {
    editTblStoreCategoryDialog.value.setDialogData(row);
    editTblStoreCategoryDialog.value?.openDialog()
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
        deleteTblStoreCategory(id).then(() => {
            fetchTblStoreCategoryTree()
        }).catch(() => {
        })
    })
}


</script>
