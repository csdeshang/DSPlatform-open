<template>

        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd()">新增</el-button>
            </div>
        </el-card>
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading" row-key="id"
            :tree-props="{ children: 'children', hasChildren: 'hasChildren' }" :default-expand-all="false">
            <el-table-column label="菜单名称" prop="title" width="150" show-overflow-tooltip />
            <el-table-column label="图标" prop="icon" width="100">
                <template #default="{ row }">
                    <div class="flex">
                        <icon :icon="row.icon" :size="20" />
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="路由" prop="type">
                <template #default="{ row }">
                    <div class="flex flex-row">
                        <div v-if="row.type == 'directory'">目录</div>
                        <div v-else-if="row.type == 'menu'">菜单</div>
                        <div v-else-if="row.type == 'button'">按钮</div>
                        <div class="ml-2">{{row.path}}</div>
                    </div>
                </template>
            </el-table-column>
            
            <el-table-column label="是否显示" prop="is_show" width="100">
                <template #default="{ row }">
                    <el-tag v-if="row.is_show == 1">是</el-tag>
                    <el-tag v-else type="danger">否</el-tag>
                </template>
            </el-table-column>
            <el-table-column label="排序" prop="sort" width="100"></el-table-column>
            <el-table-column label="更新时间" prop="update_at" width="160"></el-table-column>
            <el-table-column label="操作" width="160" fixed="right">
                <template #default="{ row }">
                    <el-button v-if="row.type !== 'BUTTON'" type="primary" link
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

        <menu-edit ref="editAdminMenuDialog" @complete="fetchAdminMenuTree()" />

</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';

import menuEdit from './edit.vue'
import { getAdminMenuTree, deleteAdminMenu } from '@/pages-admin/main/api/admin/adminMenu';
import { ElMessageBox } from 'element-plus';
import Icon from '@/components/icon/index.vue'


// 数据
const tableData = reactive({
    loading: true,
    data: [],
})



// 加载数据
const fetchAdminMenuTree = () => {
    tableData.loading = true;
    getAdminMenuTree({
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化
fetchAdminMenuTree();




// 编辑弹窗
const editAdminMenuDialog: Record<string, any> | null = ref(null)

const handleAdd = (parentId?: number) => {
    if (parentId) {
        editAdminMenuDialog.value.setDialogData({ pid: parentId });
    } else {
        editAdminMenuDialog.value.setDialogData({ pid: 0 });
    }
    editAdminMenuDialog.value?.openDialog()
}

const handleEdit = (row: any) => {
    editAdminMenuDialog.value.setDialogData(row);
    editAdminMenuDialog.value?.openDialog()
}

/**
 * 删除
 */
const handleDelete = (adminMenuId: number) => {
    ElMessageBox.confirm('您确定是否删除此管理员', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteAdminMenu(adminMenuId).then(() => {
            fetchAdminMenuTree()
        }).catch(() => {
        })
    })
}


</script>