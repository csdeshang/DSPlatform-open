<template>
    <!-- 积分商品订单列表页面 -->
    <div>
        <!-- 搜索表单 -->
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="订单号">
                    <el-input v-model="searchParams.order_sn" placeholder="输入订单号" clearable />
                </el-form-item>
                <el-form-item label="用户ID">
                    <el-input v-model="searchParams.user_id" placeholder="输入用户ID" clearable />
                </el-form-item>
                <el-form-item label="用户名">
                    <el-input v-model="searchParams.username" placeholder="请输入用户名" clearable />
                </el-form-item>
                <el-form-item label="商品名称">
                    <el-input v-model="searchParams.goods_name" placeholder="输入商品名称" clearable />
                </el-form-item>
                <el-form-item label="订单状态" style="width: 200px;">
                    <el-select v-model="searchParams.order_status" placeholder="选择订单状态" clearable>
                        <el-option v-for="item in orderStatusOptions" :key="item.value" :label="item.label"
                            :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item label="配送方式" style="width: 200px;">
                    <el-select v-model="searchParams.delivery_method" placeholder="选择配送方式" clearable>
                        <el-option v-for="item in deliveryMethodOptions" :key="item.value" :label="item.label"
                            :value="item.value" />
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <!-- 订单列表 -->
        <el-card shadow="never">
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="80" />
                <el-table-column label="订单号" prop="order_sn" width="180">
                    <template #default="{ row }">
                        <el-link type="primary" :underline="false" @click="handleDetail(row)">
                            {{ row.order_sn }}
                        </el-link>
                    </template>
                </el-table-column>
                <el-table-column label="用户ID" prop="user_id" width="100" >
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleUserDtail(row.user_id)">{{ row.user_id }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column label="商品信息" min-width="200">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <div>
                                <el-image :src="formatFileUrl(row.goods_image)" style="width: 50px; height: 50px;"
                                    fit="cover" />
                            </div>
                            <div class="ml-2">
                                <div class="font-medium">{{ row.goods_name }}</div>
                                <div class="text-sm text-gray-500">{{ row.exchange_num }}件</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="积分消耗" prop="total_points" width="100">
                    <template #default="{ row }">
                        <span class="text-orange-500 font-bold">{{ row.total_points }} 积分</span>
                    </template>
                </el-table-column>
                <el-table-column label="配送方式" prop="delivery_method_desc" width="100" />
                <el-table-column label="收货人" prop="receiver_name" width="100" />
                <el-table-column label="收货电话" prop="receiver_mobile" width="120" />
                <el-table-column label="订单状态" prop="order_status_desc" width="100">
                    <template #default="{ row }">
                        <el-tag>{{ row.order_status_desc }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="创建时间" prop="create_at" width="180" />
                <el-table-column label="操作" align="right" fixed="right" width="200">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleDetail(row)">详情</el-button>
                        <el-button v-if="row.admin_available_actions?.includes('ship')" type="success" link
                            @click="handleShip(row)">
                            发货
                        </el-button>
                        <el-button v-if="row.admin_available_actions?.includes('confirm')" type="warning" link
                            @click="handleReceive(row)">
                            确认收货
                        </el-button>
                        <el-button v-if="row.admin_available_actions?.includes('cancel')" type="danger" link
                            @click="handleCancel(row)">
                            取消订单
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <!-- 分页 -->
            <div class="flex justify-end mt-[20px]">
                <el-pagination :current-page="tableData.page_current" :page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>
        </el-card>

        <!-- 发货对话框 -->
        <ShipDialog ref="shipDialogRef" @complete="getTableList" />
        
        <!-- 订单详情抽屉 -->
        <OrderDetailDrawer ref="orderDetailDrawerRef" @complete="getTableList" />

        <!-- 会员详情弹窗 -->
        <UserDetail ref="detailUserDialog" />
    </div>
</template>

<script lang="ts" setup name="PointsGoodsOrderIndex">
import { reactive, ref } from 'vue';
import { ElMessage, ElMessageBox } from 'element-plus'
import { formatFileUrl } from '@/utils/util'
import { usePagination } from '@/hooks/usePagination'
import { useEnum } from '@/hooks/useEnum'
import ShipDialog from './ship-dialog.vue'
import OrderDetailDrawer from './order-detail-drawer.vue'
import {
    getPointsGoodsOrderPages,
    cancelPointsGoodsOrder,
    confirmPointsGoodsOrder
} from '@/pages-admin/main/api/points-goods/pointsGoodsOrder'

// 使用枚举 Hook
const { options: orderStatusOptions } = useEnum('default.points_goods_order.order_status')
const { options: deliveryMethodOptions } = useEnum('default.points_goods_order.delivery_method')

// 发货对话框引用
const shipDialogRef = ref()

// 订单详情抽屉引用
const orderDetailDrawerRef = ref()

// 搜索参数
const searchParams = reactive({
    order_sn: '',
    user_id: '',
    username: '',
    goods_name: '',
    order_status: '',
    delivery_method: ''
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
    requestFun: getPointsGoodsOrderPages,
    searchParams: searchParams
})


// 查看订单详情
const handleDetail = (row: any) => {
    orderDetailDrawerRef.value?.setDialogData(row)
    orderDetailDrawerRef.value?.openDialog()
}

// 发货操作
const handleShip = (row: any) => {
    shipDialogRef.value?.setDialogData(row)
    shipDialogRef.value?.openDialog()
}

// 确认收货
const handleReceive = (row: any) => {
    ElMessageBox.confirm('确认该订单已收货？', '确认收货', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        confirmPointsGoodsOrder(row.id).then(() => {
            ElMessage.success('确认收货成功')
            getTableList()
        }).catch(() => {
            ElMessage.error('确认收货失败')
        })
    })
}

// 取消订单
const handleCancel = (row: any) => {
    ElMessageBox.confirm('确认取消该订单？', '取消订单', {
        confirmButtonText: '确认',
        cancelButtonText: '取消',
        type: 'warning'
    }).then(() => {
        cancelPointsGoodsOrder(row.id).then(() => {
            ElMessage.success('取消订单成功')
            getTableList()
        }).catch(() => {
            ElMessage.error('取消订单失败')
        })
    })
}


// 会员详情弹窗
import UserDetail from '@/pages-admin/components/user/detail.vue'
const detailUserDialog = ref()
const handleUserDtail = (userId: number) => {
    detailUserDialog.value.setDialogData({ id: userId })
    detailUserDialog.value?.openDialog()
}

// 初始化加载数据
getTableList()
</script>

<style scoped></style>