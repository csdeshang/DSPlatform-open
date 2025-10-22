<template>

        <el-card shadow="never" class="mb-[10px]">
            <div>
                <el-button type="primary" @click="handleAdd()">新增</el-button>
            </div>
        </el-card>
        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading" row-key="id"
                :tree-props="{ children: 'children', hasChildren: 'hasChildren' }" lazy :load="handleNodeExpand">

                <el-table-column label="菜单名称" prop="name" min-width="150" show-overflow-tooltip />

                <el-table-column label="排序" prop="sort" min-width="180"></el-table-column>
                <el-table-column label="深度" prop="deep" min-width="180"></el-table-column>
                <el-table-column label="经度" prop="longitude" min-width="180"></el-table-column>
                <el-table-column label="纬度" prop="latitude" min-width="180"></el-table-column>
                <el-table-column label="是否显示" prop="is_show" min-width="100">
                    <template #default="{ row }">
                        <el-tag v-if="row.is_show == 1">是</el-tag>
                        <el-tag v-else type="danger">否</el-tag>
                    </template>
                </el-table-column>
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
        </el-card>

        <area-edit ref="editSysAreaDialog" @complete="fetchSysAreaList()" />


</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue';

import areaEdit from './edit.vue'
import { getSysAreaList, deleteSysArea } from '@/pages-admin/main/api/system/sysArea';
import { ElMessageBox } from 'element-plus';


// 数据
const tableData = reactive({
    loading: true,
    data: [],
})


// 加载子节点
const handleNodeExpand = (
    row: any,
    treeNode: unknown,
    resolve: (data: any[]) => void
) => {
    getSysAreaList({ pid: row.id }).then(res => {
        resolve(res.data);
    }).catch(() => {
    });

}





// 加载列表
const fetchSysAreaList = () => {
    tableData.loading = true;
    getSysAreaList({
        pid: 0
    }).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })

}
// 初始化列表
fetchSysAreaList();



const editSysAreaDialog: Record<string, any> | null = ref(null)

/**
 * 添加
 */
const handleAdd = (parentId?: number) => {
    if (parentId) {
        editSysAreaDialog.value.setDialogData({ pid: parentId });
    } else {
        editSysAreaDialog.value.setDialogData({ pid: 0 });
    }
    editSysAreaDialog.value?.openDialog()
}

/**
 * 编辑
 */
const handleEdit = (row: any) => {
    editSysAreaDialog.value.setDialogData(row);
    editSysAreaDialog.value?.openDialog()
}

/**
 * 删除
 */
const handleDelete = (sysAreaId: number) => {
    ElMessageBox.confirm('您确定是否删除此管理员', 'Warning',
        {
            confirmButtonText: '确认',
            cancelButtonText: '取消',
            type: 'warning'
        }
    ).then(() => {
        deleteSysArea(sysAreaId).then(() => {
            fetchSysAreaList()
        }).catch(() => {
        })
    })
}


</script>