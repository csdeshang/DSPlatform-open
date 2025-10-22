<template>
    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="用户ID">
                    <el-input v-model="searchParams.user_id" placeholder="请输入用户ID" clearable />
                </el-form-item>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
                </el-form-item>
                <el-form-item label="商品ID">
                    <el-input v-model="searchParams.goods_id" placeholder="请输入商品ID" clearable />
                </el-form-item>
                <el-form-item label="订单ID">
                    <el-input v-model="searchParams.order_id" placeholder="请输入订单ID" clearable />
                </el-form-item>
                <el-form-item label="评价状态">
                    <el-select v-model="searchParams.is_show" placeholder="请选择评价状态" clearable class="w-[120px]">
                        <el-option label="显示中" value="1" />
                        <el-option label="已隐藏" value="0" />
                    </el-select>
                </el-form-item>
                <el-form-item label="匿名状态">
                    <el-select v-model="searchParams.is_anonymous" placeholder="请选择匿名状态" clearable class="w-[120px]">
                        <el-option label="匿名评价" value="1" />
                        <el-option label="实名评价" value="0" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" min-width="60" />

                <el-table-column label="订单ID" prop="order_id" width="100">
                    <template #default="{ row }">
                        <div class="text-blue-500 cursor-pointer" @click="handleOrderDetail(row.order_id)">{{
                            row.order_id }}</div>
                    </template>
                </el-table-column>

                <el-table-column label="用户ID" min-width="120">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <span class="text-blue-500 cursor-pointer" @click="handleUserDetail(row.user_id)">{{
                                row.user_id }}
                            </span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="商品ID" min-width="120">
                    <template #default="{ row }">
                        <div class="flex items-center" @click="handleGoodsDetail(row.goods_id)">
                            <el-image v-if="row.goods?.goods_image" :src="formatFileUrl(row.goods.goods_image)"
                                style="width: 40px; height: 40px;" class="mr-2" />
                            <span class="text-blue-500 cursor-pointer">{{ row.goods_id }}</span>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column label="评价内容" prop="evaluate_content" min-width="300" show-overflow-tooltip />

                <el-table-column label="评价图片" min-width="120">
                    <template #default="{ row }">
                        <div v-if="row.evaluate_images" class="flex flex-wrap gap-1">
                            <el-image v-for="(image, index) in getEvaluateImages(row.evaluate_images)" :key="index"
                                :src="formatFileUrl(image)" style="width: 40px; height: 40px;" fit="cover"
                                :preview-src-list="getEvaluateImages(row.evaluate_images).map(img => formatFileUrl(img))" />
                        </div>
                        <span v-else class="text-gray-400">无图片</span>
                    </template>
                </el-table-column>

                <el-table-column label="评分" min-width="100">
                    <template #default="{ row }">
                        <el-rate v-model="row.evaluate_score" disabled show-score text-color="#ff9900" size="small" />
                    </template>
                </el-table-column>

                <el-table-column label="匿名评价" min-width="90">
                    <template #default="{ row }">
                        <el-tag :type="row.is_anonymous == '1' ? 'warning' : 'info'">
                            {{ row.is_anonymous == '1' ? '匿名' : '实名' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="评价状态" min-width="90">
                    <template #default="{ row }">
                        <el-tag :type="row.is_show == '1' ? 'success' : 'danger'">
                            {{ row.is_show == '1' ? '显示中' : '已隐藏' }}
                        </el-tag>
                    </template>
                </el-table-column>

                <el-table-column label="商家回复" prop="reply_content" min-width="200" show-overflow-tooltip>
                    <template #default="{ row }">
                        <div v-if="row.reply_content">
                            {{ row.reply_content }}
                            <div class="text-xs text-gray-500 mt-1">
                                回复时间: {{ row.reply_time ? new Date(row.reply_time * 1000).toLocaleString() : '未知' }}
                            </div>
                        </div>
                        <span v-else class="text-gray-400">暂无回复</span>
                    </template>
                </el-table-column>

                <el-table-column label="评价时间" prop="create_at" min-width="160" />

                <el-table-column label="操作" min-width="200" fixed="right">
                    <template #default="{ row }">
                        <el-button :type="row.is_show == '1' ? 'danger' : 'success'" link
                            @click="handleToggleField(row, 'is_show')">
                            {{ row.is_show == '1' ? '隐藏' : '显示' }}
                        </el-button>
                        <el-button type="primary" link @click="handleToggleField(row, 'is_anonymous')">
                            {{ row.is_anonymous == '1' ? '取消匿名' : '设为匿名' }}
                        </el-button>
                        <el-button type="warning" link @click="handleReply(row)">
                            回复
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination :current-page="tableData.page_current" :page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 详情弹窗 -->
        <OrderDetailDrawer ref="orderDetailDrawerRef" />
        <UserDetail ref="userDetailDialog" />
        <GoodsDetailDrawer ref="goodsDetailDrawerRef" />

        <!-- 回复弹窗 -->
        <el-dialog v-model="replyDialogVisible" title="商家回复" width="500px">
            <el-form :model="replyForm" label-width="80px">
                <el-form-item label="回复内容">
                    <el-input v-model="replyForm.reply_content" type="textarea" :rows="4" placeholder="请输入回复内容"
                        maxlength="500" show-word-limit />
                </el-form-item>
            </el-form>
            <template #footer>
                <el-button @click="replyDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleReplySubmit" :loading="replyLoading">确定</el-button>
            </template>
        </el-dialog>
    </div>
</template>

<script setup lang="ts" name="PointsGoodsEvaluateIndex">
import { reactive, ref } from 'vue'
import { getPointsGoodsEvaluatePages, togglePointsGoodsEvaluateField, replyPointsGoodsEvaluate } from '@/pages-admin/main/api/points-goods/pointsGoodsEvaluate'
import { usePagination } from '@/hooks/usePagination'
import { formatFileUrl } from '@/utils/util'
import { ElMessage, ElMessageBox } from 'element-plus'

// 搜索参数
const searchParams = reactive({
    user_id: '',
    username: '',
    goods_id: '',
    order_id: '',
    is_show: '',
    is_anonymous: ''
})

// 分页数据
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getPointsGoodsEvaluatePages,
    searchParams: searchParams
})

// 初始化数据
getTableList()

// 获取评价图片数组
const getEvaluateImages = (images: string): string[] => {
    if (!images) return []
    return images.split(',').filter(img => img.trim())
}

// 切换字段状态
const handleToggleField = async (row: any, field: string) => {
    try {
        const actionName = getActionName(field, row[field])

        await ElMessageBox.confirm(`确定要${actionName}吗？`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })

        await togglePointsGoodsEvaluateField({
            id: row.id,
            field: field
        })

        ElMessage.success(`${actionName}成功`)
        getTableList()
    } catch (error) {
        if (error !== 'cancel') {
            console.error('操作失败', error)
            ElMessage.error('操作失败')
        }
    }
}

// 获取操作名称
const getActionName = (field: string, currentValue: string): string => {
    const actionMap: Record<string, Record<string, string>> = {
        is_show: {
            '1': '隐藏评价',
            '0': '显示评价'
        },
        is_anonymous: {
            '1': '取消匿名',
            '0': '设为匿名'
        }
    }
    return actionMap[field]?.[currentValue] || '切换状态'
}

// 回复相关
const replyDialogVisible = ref(false)
const replyLoading = ref(false)
const replyForm = reactive({
    id: 0,
    reply_content: ''
})

// 处理回复
const handleReply = (row: any) => {
    replyForm.id = row.id
    replyForm.reply_content = row.reply_content || ''
    replyDialogVisible.value = true
}

// 提交回复
const handleReplySubmit = async () => {
    if (!replyForm.reply_content.trim()) {
        ElMessage.warning('请输入回复内容')
        return
    }

    replyLoading.value = true
    try {
        await replyPointsGoodsEvaluate({
            id: replyForm.id,
            reply_content: replyForm.reply_content
        })

        ElMessage.success('回复成功')
        replyDialogVisible.value = false
        getTableList()
    } catch (error) {
        ElMessage.error('回复失败')
    } finally {
        replyLoading.value = false
    }
}

// 订单详情弹窗
import OrderDetailDrawer from '../order/order-detail-drawer.vue'
const orderDetailDrawerRef = ref()
const handleOrderDetail = (orderId: number) => {
    orderDetailDrawerRef.value?.setDialogData({ id: orderId })
    orderDetailDrawerRef.value?.openDialog()
}

// 用户详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const userDetailDialog = ref()
const handleUserDetail = (userId: number) => {
    userDetailDialog.value?.setDialogData({ id: userId })
    userDetailDialog.value?.openDialog()
}

// 商品详情弹窗
import GoodsDetailDrawer from '../goods/goods-drawer.vue'
const goodsDetailDrawerRef = ref()
const handleGoodsDetail = (goodsId: number) => {
    goodsDetailDrawerRef.value?.setDialogData({ id: goodsId })
    goodsDetailDrawerRef.value?.openDialog()
}
</script>
