<template>
    <!-- 积分商品列表页面 -->
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="商品名">
                    <el-input v-model="searchParams.goods_name" placeholder="输入商品名" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
            <el-button type="primary" @click="handleAdd()">新增</el-button>
        </el-card>

        <el-card shadow="never">
            
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="60" />
                <el-table-column label="商品" prop="goods_name">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <div>
                                <el-image :src="formatImageUrl(row.goods_image, ThumbnailPresets.small, 'goods')" style="width: 50px; height: 50px;" fit="cover" />
                            </div>
                            <div class="ml-2">
                                <div class="font-medium">{{ row.goods_name }}</div>
                                <div class="text-sm text-gray-500">{{ row.goods_advword || '暂无描述' }}</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="积分价格" prop="points_price" width="100">
                    <template #default="{ row }">
                        <span class="text-orange-500 font-bold">{{ row.points_price }} 积分</span>
                    </template>
                </el-table-column>
                <el-table-column label="市场参考价格" prop="market_price" width="100">
                    <template #default="{ row }">
                        <span class="text-green-500">¥{{ row.market_price || '0.00' }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="兑换数量" prop="exchange_num" width="100" />
                <el-table-column label="总库存" prop="stock_num" width="100" />
                <el-table-column label="排序" prop="goods_sort" width="100" />
                <el-table-column label="状态" prop="goods_status" width="100">
                    <template #default="{ row }">
                        <el-tag :type="getStatusTagType(row.goods_status)">
                            {{ getStatusText(row.goods_status) }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="操作" align="right" fixed="right" width="150">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleDetail(row)">详情</el-button>
                        <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                        <el-button type="danger" link @click="handleDelete(row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination 
                    :current-page="tableData.page_current" 
                    :page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" 
                    :total="tableData.total"
                    @size-change="getTableList()" 
                    @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 编辑弹窗 -->
        <GoodsDrawer ref="editGoodsDialog" @complete="getTableList" />

    </div>
</template>

<script lang="ts" setup name="PointsGoodsList">
import { reactive, ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus'
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'
import { usePagination } from '@/hooks/usePagination'
import { getPointsGoodsPages, deletePointsGoods } from '@/pages-admin/main/api/points-goods/pointsGoods'
import GoodsDrawer from './goods-drawer.vue'

// 搜索参数
const searchParams = reactive({
    goods_name: ''
})

// 使用分页钩子
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getPointsGoodsPages,
    searchParams: searchParams
})

// 获取状态标签类型
const getStatusTagType = (status: number) => {
    const statusMap: Record<number, string> = {
        0: 'info',     // 下架
        1: 'success',  // 上架
    }
    return statusMap[status] || 'info'
}

// 获取状态文本
const getStatusText = (status: number) => {
    const statusMap: Record<number, string> = {
        0: '已下架',
        1: '上架中',
    }
    return statusMap[status] || '未知状态'
}



// 编辑弹窗引用
const editGoodsDialog = ref()

// 新增商品
const handleAdd = () => {
    editGoodsDialog.value.setDialogData()
    editGoodsDialog.value?.openDialog()
}

// 商品详情
const handleDetail = (row: any) => {
    console.log('查看详情:', row)
    ElMessage.info('查看详情功能待实现')
}

// 商品编辑
const handleEdit = (row: any) => {
    editGoodsDialog.value.setDialogData(row)
    editGoodsDialog.value?.openDialog()
}

// 删除商品
const handleDelete = (id: number) => {
    ElMessageBox.confirm('您确定要删除这个积分商品吗？', '警告', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        deletePointsGoods(id).then(() => {
            ElMessage.success('删除成功')
            getTableList()
        }).catch(() => {
            ElMessage.error('删除失败')
        })
    })
}



// 初始化加载数据
getTableList()
</script>

<style scoped>
</style>