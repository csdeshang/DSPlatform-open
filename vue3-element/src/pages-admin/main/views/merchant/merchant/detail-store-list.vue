<template>
    <!-- 详情页中显示店铺列表   根据商户ID 显示店铺 显示 TblStore 表中的数据 -->

    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="店铺ID" />
        <el-table-column prop="platform" label="应用类型" />
        <el-table-column prop="merchant_id" label="商户ID" />
        <el-table-column prop="store_name" label="店铺名称" />
        <el-table-column prop="contact_name" label="联系人" />
        <el-table-column prop="contact_phone" label="联系电话" />
        <el-table-column prop="is_enabled" label="是否启用">
            <template #default="scope">
                <el-tag :type="scope.row.is_enabled ? 'success' : 'danger'">
                    {{ scope.row.is_enabled ? '已启用' : '已禁用' }}
                </el-tag>
            </template>
        </el-table-column>
        <el-table-column prop="apply_status_desc" label="申请状态" />
    </el-table>


</template>


<script lang="ts" setup>
import { getTblStoreList } from '@/pages-admin/main/api/tbl-store/tblStore'
import { reactive, watch } from 'vue';


const props = defineProps({
    // 商户ID 商户下的店铺
    merchant_id: {
        type: Number,
        default: 0
    },
})


const searchParams = reactive({
    merchant_id: props.merchant_id
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})


// 加载数据
const fetchTblStoreList = () => {
    tableData.loading = true;
    getTblStoreList(searchParams).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}



// 监听 merchant_id 的变化并更新 searchParams
watch(
    () => props.merchant_id,
    (newMerchantId) => {
        searchParams.merchant_id = newMerchantId;
        if (newMerchantId > 0) {
            fetchTblStoreList(); // 重新获取数据
        }
    },
    { immediate: true } // 立即执行一次以加载数据
);

defineExpose({
    fetchTblStoreList
});

</script>