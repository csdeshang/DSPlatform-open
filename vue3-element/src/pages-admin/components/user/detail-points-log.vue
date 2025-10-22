<template>
    <!-- 详情页中显示积分记录列表   根据用户ID 显示积分记录 显示 TblUserPointsLog 表中的数据 -->
    <div>
        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="记录ID" width="80" />
            <el-table-column prop="user_id" label="用户ID" width="80" />
            <el-table-column label="用户名称" width="120">
                <template #default="{ row }">
                    {{ row.user?.nickname || row.user?.username || `用户${row.user_id}` }}
                </template>
            </el-table-column>
            <el-table-column prop="change_type_desc" label="记录类型" width="100" />
            <el-table-column prop="change_mode_desc" label="变动方式" width="100" />
            <el-table-column label="变动积分" width="100">
                <template #default="{ row }">
                    <span :style="{ color: row.change_mode === 1 ? '#67C23A' : '#F56C6C' }">
                        {{ row.change_mode === 1 ? '+' : '-' }}{{ row.change_num }}
                    </span>
                </template>
            </el-table-column>
            <el-table-column prop="before_num" label="变动前积分" width="100" />
            <el-table-column prop="after_num" label="变动后积分" width="100" />
            <el-table-column prop="change_desc" label="变动描述" min-width="150" />
            <el-table-column prop="create_at" label="创建时间" width="160" />
        </el-table>
        <div class="flex justify-end mt-[20px]">
            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
        </div>

    </div>

</template>


<script lang="ts" setup>
import { getUserPointsLogPages } from '@/pages-admin/main/api/user/userPoints'
import { reactive, watch } from 'vue';
import { usePagination } from '@/hooks/usePagination'


const props = defineProps({
    // 用户ID
    user_id: {
        type: Number,
        default: 0
    },
})

const searchParams = reactive({
    user_id: props.user_id,
})


const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getUserPointsLogPages,
    searchParams: searchParams
})



// 监听 user_id 的变化并更新 searchParams
watch(
    () => props.user_id,
    (newUserId) => {
        searchParams.user_id = newUserId;
        getTableList(); // 重新获取数据
    }
);


</script>