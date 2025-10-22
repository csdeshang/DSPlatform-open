<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="75%">
        <div v-if="orderData" class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-image :src="formatFileUrl(orderData.goods_image)" style="width: 80px; height: 80px;"
                                fit="cover" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ orderData.goods_name }}
                            </div>
                            <div class="status">
                                <el-tag>
                                    {{ orderData.order_status_desc }}
                                </el-tag>
                            </div>
                        </div>
                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary" @click="isEditMode = !isEditMode">
                            {{ isEditMode ? '取消编辑' : '编辑' }}
                        </el-button>
                    </div>
                </div>
                <div class="section-hd-center">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">订单号：</div>
                                <div class="item-content">{{ orderData.order_sn }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">用户ID：</div>
                                <div class="item-content">{{ orderData.user_id }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">商品ID：</div>
                                <div class="item-content">{{ orderData.goods_id }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">积分价格：</div>
                                <div class="item-content">{{ orderData.points_price }} 积分</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">兑换数量：</div>
                                <div class="item-content">{{ orderData.exchange_num }}件</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">总积分：</div>
                                <div class="item-content">{{ orderData.total_points }} 积分</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">配送方式：</div>
                                <div class="item-content">{{ orderData.delivery_method_desc }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">评价状态：</div>
                                <div class="item-content">{{ orderData.is_evaluate ? '已评价' : '未评价' }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">创建时间：</div>
                                <div class="item-content">{{ orderData.create_at }}</div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">更新时间：</div>
                                <div class="item-content">{{ orderData.update_at }}</div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </div>

            <div class="section-bd">
                <el-tabs v-model="tabSelected">
                    <el-tab-pane label="订单信息" name="order">
                        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="120px" class="mt-6">
                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    基本信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="订单状态">
                                                <div class="form-text">
                                                    <el-tag>
                                                        {{ orderData.order_status_desc }}
                                                    </el-tag>
                                                </div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="配送方式" prop="delivery_method">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.delivery_method" placeholder="请选择配送方式">
                                                        <el-option label="快递" value="express" />
                                                        <el-option label="自提" value="delivery" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.delivery_method_desc }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    收货信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="收货人" prop="receiver_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.receiver_name" placeholder="请输入收货人姓名" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.receiver_name }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="收货电话" prop="receiver_mobile">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.receiver_mobile"
                                                        placeholder="请输入收货电话" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.receiver_mobile }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="收货地址" prop="receiver_address">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.receiver_address" type="textarea"
                                                        :rows="3" placeholder="请输入收货地址" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.receiver_address }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12" v-if="orderData.delivery_method === 'express'">
                                            <el-form-item label="快递公司" prop="express_company">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.express_company"
                                                        placeholder="请输入快递公司" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.express_company || '未设置' }}
                                                    </div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12" v-if="orderData.delivery_method === 'express'">
                                            <el-form-item label="快递单号" prop="express_no">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.express_no" placeholder="请输入快递单号" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.express_no || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="发货时间">
                                                <div class="form-text">{{ orderData.express_time ? new
                                                    Date(orderData.express_time *
                                                        1000).toLocaleString() : '未发货' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="收货时间">
                                                <div class="form-text">{{ orderData.receive_time ? new
                                                    Date(orderData.receive_time *
                                                        1000).toLocaleString() : '未收货' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="备注" prop="remark">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.remark" type="textarea" :rows="3"
                                                        placeholder="请输入备注" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ orderData.remark || '无备注' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <!-- 只在编辑模式下显示按钮 -->
                            <div v-if="isEditMode" class="section-bd-block-footer">
                                <el-button type="primary" @click="handleSubmit" :loading="loading">保存</el-button>
                                <el-button @click="isEditMode = false">取消</el-button>
                            </div>
                        </el-form>
                    </el-tab-pane>

                    <el-tab-pane label="订单日志" name="logs">
                        <div v-loading="logsLoading">
                            <el-card shadow="never" v-if="orderLogs.length > 0">
                                <el-timeline>
                                    <el-timeline-item v-for="log in orderLogs" :key="log.id"
                                        :timestamp="log.create_at" placement="top">
                                        <el-card>
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium">{{ log.message }}</span>
                                                <el-tag>
                                                    {{ log.order_status_desc }}
                                                </el-tag>
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                操作人: {{ log.create_by }} ({{ log.create_role }})
                                            </div>
                                        </el-card>
                                    </el-timeline-item>
                                </el-timeline>
                            </el-card>
                            <el-empty v-else description="暂无订单日志" />
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue'
import { formatFileUrl } from '@/utils/util'
import { ElMessage } from 'element-plus'
import { getPointsGoodsOrderInfo, updatePointsGoodsOrder, getPointsGoodsOrderLogs } from '@/pages-admin/main/api/points-goods/pointsGoodsOrder'

// 弹窗显示状态
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 编辑模式
const isEditMode = ref(false)
const tabSelected = ref('order')

// 订单日志数据
const orderLogs = ref<any[]>([])
const logsLoading = ref(false)

// 订单数据
const orderData = reactive({
    id: 0,
    order_sn: '',
    user_id: 0,
    goods_id: 0,
    goods_name: '',
    goods_image: '',
    points_price: 0,
    exchange_num: 0,
    total_points: 0,
    order_status: 0,
    order_status_desc: '',
    delivery_method: '',
    delivery_method_desc: '',
    receiver_name: '',
    receiver_mobile: '',
    receiver_address: '',
    express_company: '',
    express_no: '',
    express_time: 0,
    receive_time: 0,
    is_evaluate: 0,
    remark: '',
    create_at: '',
    update_at: 0,
    is_deleted: 0,
    deleted_at: null,
    user: null
})

// 表单数据
const initialFormData = {
    delivery_method: '',
    receiver_name: '',
    receiver_mobile: '',
    receiver_address: '',
    express_company: '',
    express_no: '',
    remark: ''
}
const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        receiver_name: [
            {
                required: true,
                message: '请输入收货人姓名',
                trigger: 'blur'
            }
        ],
        receiver_mobile: [
            {
                required: true,
                message: '请输入收货电话',
                trigger: 'blur'
            },
            {
                pattern: /^1[3-9]\d{9}$/,
                message: '请输入正确的手机号',
                trigger: 'blur'
            }
        ],
        receiver_address: [
            {
                required: true,
                message: '请输入收货地址',
                trigger: 'blur'
            }
        ]
    }
})


// 提交表单
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true

    const updateData = {
        id: orderData.id,
        ...formData
    }
    updatePointsGoodsOrder(updateData).then(() => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

// 获取订单日志
const getOrderLogs = async (orderId: number) => {
    logsLoading.value = true
    try {
        const response = await getPointsGoodsOrderLogs(orderId)
        orderLogs.value = response.data || []
    } catch (error) {
        console.error('获取订单日志失败:', error)
        orderLogs.value = []
    } finally {
        logsLoading.value = false
    }
}

// 设置弹窗数据
const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '积分商品订单详情'
    
    if (row.id) {
        const data = await (await getPointsGoodsOrderInfo(row.id)).data
        Object.assign(orderData, data)
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
        
        // 获取订单日志
        await getOrderLogs(row.id)
    }
    loading.value = false
}

const emit = defineEmits(['complete'])

// 暴露方法
defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>

<style scoped>
.ds-detail {
    height: calc(100vh - 60px);
    overflow-y: auto;
}
</style>