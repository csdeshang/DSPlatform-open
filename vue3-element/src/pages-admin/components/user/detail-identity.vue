<template>
    <!-- 详情页中显示店铺列表   根据用户ID 显示店铺 显示 TblStoreAuthUser 表中的数据 -->


    <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
        <el-table-column prop="id" label="ID" width="80" />
        <el-table-column prop="user_id" label="用户ID" width="100" />
        <el-table-column prop="merchant_id" label="商户ID">
            <template #default="{ row }">
                <el-tag v-if="row.merchant_id == '0'" type="info">系统</el-tag>
                <el-tag v-else type="success">{{ row.merchant_id }}</el-tag>
            </template>
        </el-table-column>
        <el-table-column label="微信绑定信息" width="280">
            <template #default="{ row }">
                <div class="text-xs">
                    <div v-if="row.wx_event_openid" class="mb-1">公众号: {{ row.wx_event_openid }}</div>
                    <div v-if="row.wx_oauth_openid" class="mb-1">网页授权: {{ row.wx_oauth_openid }}</div>
                    <div v-if="row.wx_mini_openid" class="mb-1">小程序: {{ row.wx_mini_openid }}</div>
                    <div v-if="row.wx_app_openid" class="mb-1">APP: {{ row.wx_app_openid }}</div>
                    <div v-if="row.wx_unionid" class="mb-1">UnionID: {{ row.wx_unionid }}</div>
                </div>
            </template>
        </el-table-column>
        <el-table-column prop="create_at" label="创建时间" width="180" />

    </el-table>


</template>


<script lang="ts" setup>
import { getUserIdentityList } from '@/pages-admin/main/api/user/userIdentity'

import { reactive, watch } from 'vue';


const props = defineProps({
    // 用户ID
    user_id: {
        type: Number,
        default: 0
    },
})


const searchParams = reactive({
    user_id: props.user_id
})

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})


// 加载数据
const fetchUserIdentityList = () => {
    tableData.loading = true;
    getUserIdentityList(searchParams).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}




// 监听 user_id 的变化并更新 searchParams
watch(
    () => props.user_id,
    (newUserId) => {
        searchParams.user_id = newUserId;
        fetchUserIdentityList(); // 重新获取数据
    }
);


</script>