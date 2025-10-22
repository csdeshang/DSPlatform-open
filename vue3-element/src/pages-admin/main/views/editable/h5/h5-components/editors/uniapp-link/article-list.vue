<template>
    <div>

        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="文章标题">
                    <el-input v-model="searchParams.title" placeholder="请输入文章标题" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading" @row-click="handleRowClick">
                <el-table-column type="selection" width="55">
                    <template #default="{ row }">
                        <el-radio v-model="selectedRowId" :label="row.id" @change="handleSelect(row)">
                            <!-- 单选按钮内容为空 -->
                        </el-radio>
                    </template>
                </el-table-column>
                <el-table-column label="标题" prop="title" min-width="200" show-overflow-tooltip />
                <el-table-column label="封面图" width="100">
                    <template #default="{ row }">
                        <el-image v-if="row.image" style="width: 60px; height: 60px" :src="formatFileUrl(row.image)"
                            fit="cover" />
                        <span v-else>无图片</span>
                    </template>
                </el-table-column>
                <el-table-column label="排序" prop="sort" width="80" />
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>


    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, reactive } from 'vue'
import { getSysArticlePages } from '@/pages-admin/main/api/system/sysArticle'
import { usePagination } from '@/hooks/usePagination'
import { formatFileUrl } from '@/utils/util'

// 定义组件名称
defineOptions({
    name: 'ArticleList'
})


const emit = defineEmits(['select'])

// 状态管理
const selectedRowId = ref(null)


const searchParams = reactive({
    title: '',
    cid: '',
})
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getSysArticlePages,
    searchParams: searchParams
})

// 行点击事件处理
const handleRowClick = (row) => {
    selectedRowId.value = row.id
    handleSelect(row)
}

// 选择处理
const handleSelect = (row) => {
    // 构建发送到父组件的数据结构
    const selectData = {
        id: row.id,
        title: row.title,
        name: row.title,  // 为兼容index.vue的处理逻辑
        desc: row.description || '',
        image: row.image || '',
        link: `/pages/article/detail?id=${row.id}`
    }

    emit('select', selectData)
}


onMounted(() => {
    getTableList()
})


</script>

<style scoped lang="scss"></style>
