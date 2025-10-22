<template>
    <!-- 详情页中显示店铺列表   根据商户ID 显示店铺 显示 TblStore 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="user.id" label="用户ID" />
        <el-table-column prop="user.username" label="用户名" />
        <el-table-column prop="create_at" label="创建时间" />
        <el-table-column prop="create_by" label="创建人" />

        <el-table-column label="操作">
            <template #default="{ row }">
                <el-button type="primary" link
                    @click="handledeleteTblStoreAuthUser(row.user_id, row.store_id)">删除</el-button>
            </template>
        </el-table-column>
    </el-table>


</template>


<script lang="ts" setup>
import { getTblStoreAuthUserList, deleteTblStoreAuthUser } from '@/pages-admin/main/api/tbl-store/tblStoreAuthUser'
import { ElMessage, ElMessageBox } from 'element-plus';
import { reactive, watch } from 'vue';


const props = defineProps({
    // 店铺ID
    store_id: {
        type: Number,
        default: 0
    },
})


const searchParams = reactive({
    store_id: props.store_id
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})


// 加载数据
const fetchTblStoreAuthUserList = () => {
    tableData.loading = true;
    getTblStoreAuthUserList(searchParams).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}


// 删除授权管理用户
const handledeleteTblStoreAuthUser = (user_id: number, store_id: number) => {
    ElMessageBox.confirm('确定删除该用户吗？').then(() => {
        deleteTblStoreAuthUser({
            user_id: user_id,
            store_id: store_id
        }).then(() => {
            fetchTblStoreAuthUserList();
        });
    })
}


// 监听 store_id 的变化并更新 searchParams
watch(
    () => props.store_id,
    (newMerchantId) => {
        searchParams.store_id = newMerchantId;
        fetchTblStoreAuthUserList(); // 重新获取数据
    }
);

// 暴露方法
defineExpose({
    fetchTblStoreAuthUserList
});

</script>