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
                <el-form-item label="商品名">
                    <el-input v-model="searchParams.goods_name" placeholder="请输入商品名" clearable />
                </el-form-item>
                <el-form-item label="店铺ID">
                    <el-input v-model="searchParams.store_id" placeholder="请输入店铺ID" clearable />
                </el-form-item>
                <el-form-item label="店铺名">
                    <el-input v-model="searchParams.store_name" placeholder="请输入店铺名" clearable />
                </el-form-item>
                <el-form-item label="订单ID">
                    <el-input v-model="searchParams.order_id" placeholder="请输入订单ID" clearable />
                </el-form-item>
                <el-form-item label="评价状态">
                    <el-select v-model="searchParams.is_show" placeholder="请选择评价状态" clearable class="w-[140px]">
                        <el-option label="显示中" value="1" />
                        <el-option label="已隐藏" value="0" />
                    </el-select>
                </el-form-item>
                <el-form-item label="回复状态">
                    <el-select v-model="searchParams.is_reply" placeholder="请选择回复状态" clearable class="w-[140px]">
                        <el-option label="已回复" value="1" />
                        <el-option label="未回复" value="0" />
                    </el-select>
                </el-form-item>
                <el-form-item label="匿名状态">
                    <el-select v-model="searchParams.is_anonymous" placeholder="请选择匿名状态" clearable class="w-[140px]">
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
                <el-table-column label="店铺ID" prop="store_id" width="100">
                    <template #default="{ row }">
                        <div class="text-blue-500 cursor-pointer" @click="handleStoreDtail(row.store_id)">{{
                            row.store_id }}</div>
                    </template>
                </el-table-column>

                <el-table-column label="订单ID" prop="order_id" width="100">
                    <template #default="{ row }">
                        <div class="text-blue-500 cursor-pointer" @click="handleTblOrderDtail(row.order_id)">{{
                            row.order_id }}</div>
                    </template>
                </el-table-column>
                <el-table-column label="评价用户" min-width="120">
                    <template #default="{ row }">
                        <div class="flex items-center">
                            <span class="text-blue-500 cursor-pointer" @click="handleUserDtail(row.user_id)">{{
                                row.user.username }}
                            </span>
                        </div>
                    </template>
                </el-table-column>


                <el-table-column label="商品信息" min-width="300">
                    <template #default="{ row }">
                        <div class="flex items-center" @click="handleTblGoodsDtail(row.goods_id)">
                            <el-image v-if="row.goods?.cover_image" :src="formatFileUrl(row.goods.cover_image)"
                                style="width: 40px; height: 40px;" class="mr-2" />
                            <span class="text-blue-500 cursor-pointer">{{ row.goods?.goods_name || '未知商品' }}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="评价内容" prop="content" min-width="300" show-overflow-tooltip />
                <el-table-column label="评分" min-width="300">
                    <template #default="{ row }">
                        <div>
                            <div>商品评分: <el-rate v-model="row.goods_score" disabled show-score text-color="#ff9900"
                                    size="small" /></div>
                            <div>描述评分: <el-rate v-model="row.describe_score" disabled show-score text-color="#ff9900"
                                    size="small" /></div>
                            <div>物流评分: <el-rate v-model="row.logistics_score" disabled show-score text-color="#ff9900"
                                    size="small" /></div>
                            <div>服务评分: <el-rate v-model="row.service_score" disabled show-score text-color="#ff9900"
                                    size="small" /></div>
                        </div>
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
                <el-table-column label="回复状态" min-width="90">
                    <template #default="{ row }">
                        <el-tag :type="row.is_reply == '1' ? 'success' : 'info'">
                            {{ row.is_reply == '1' ? '已回复' : '未回复' }}
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="评价时间" prop="create_at" min-width="160" />
                <el-table-column label="操作" min-width="200" fixed="right">
                    <template #default="{ row }">
                        <el-button 
                            :type="row.is_show == '1' ? 'danger' : 'success'" 
                            link 
                            @click="handleToggleField(row, 'is_show')"
                        >
                            {{ row.is_show == '1' ? '隐藏' : '显示' }}
                        </el-button>
                        <el-button 
                            type="primary" 
                            link 
                            @click="handleToggleField(row, 'is_anonymous')"
                        >
                            {{ row.is_anonymous == '1' ? '取消匿名' : '设为匿名' }}
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>

        </el-card>

        <tbl-order-detail ref="detailTblOrderDialog" />
        <tbl-store-detail ref="detailTblStoreDialog" />
        <user-detail ref="detailUserDialog" />
        <tbl-goods-detail ref="detailTblGoodsDialog" />




    </div>
</template>
<script setup lang="ts" name="TblGoodsCommentList">
import { reactive, ref } from 'vue'
import { getTblGoodsCommentPages, toggleTblGoodsCommentField } from '@/pages-admin/main/api/tbl-goods/tblGoodsComment'
import { usePagination } from '@/hooks/usePagination'
import { formatFileUrl } from '@/utils/util'
import { ElMessage, ElMessageBox } from 'element-plus'

const props = defineProps({
    platform: {
        type: String,
        default: ''
    }
})

const searchParams = reactive({
    platform: props.platform,
    user_id: '',
    username: '',
    goods_id: '',
    goods_name: '',
    store_id: '',
    store_name: '',
    order_id: '',
    is_show: '',
    is_reply: '',
    is_anonymous: ''
})

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 20,
    requestFun: getTblGoodsCommentPages,
    searchParams: searchParams
})

// 初始化数据
getTableList()

// 切换字段状态
const handleToggleField = async (row: any, field: string) => {
    try {
        const actionName = getActionName(field, row[field])
        
        await ElMessageBox.confirm(`确定要${actionName}吗？`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })
        
        await toggleTblGoodsCommentField({
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
        },
        is_reply: {
            '1': '标记未回复',
            '0': '标记已回复'
        }
    }
    return actionMap[field]?.[currentValue] || '切换状态'
}



// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (user_id: any) => {
    detailUserDialog.value.setDialogData({ id: user_id })
    detailUserDialog.value?.openDialog()
}

// 店铺详情弹窗
import TblStoreDetail from '@/pages-admin/components/tbl-store/store/detail.vue'
const detailTblStoreDialog = ref()
const handleStoreDtail = (store_id: any) => {
    detailTblStoreDialog.value.setDialogData({ id: store_id })
    detailTblStoreDialog.value?.openDialog()
}

//订单详情弹窗
import TblOrderDetail from '@/pages-admin/components/tbl-order/order/detail.vue'
const detailTblOrderDialog = ref()
const handleTblOrderDtail = (order_id: any) => {
    detailTblOrderDialog.value.setDialogData({ id: order_id })
    detailTblOrderDialog.value?.openDialog()
}

// 商品详情弹窗
import TblGoodsDetail from '@/pages-admin/components/tbl-goods/goods/detail.vue'
const detailTblGoodsDialog = ref()
const handleTblGoodsDtail = (goods_id: any) => {
    detailTblGoodsDialog.value.setDialogData({ id: goods_id })
    detailTblGoodsDialog.value?.openDialog()
}

</script>