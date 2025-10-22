<template>
    <el-dialog v-model="dialogVisible" title="订单发货" width="500px" :before-close="handleClose">
        <el-form ref="formRef" :model="formData" :rules="rules" label-width="100px">
            <el-form-item label="订单号">
                <el-input :value="orderData?.order_sn || ''" disabled />
            </el-form-item>
            <el-form-item label="收货人">
                <el-input :value="orderData?.receiver_name || ''" disabled />
            </el-form-item>
            <el-form-item label="收货地址">
                <el-input :value="orderData?.receiver_address || ''" disabled type="textarea" :rows="2" />
            </el-form-item>
            <el-form-item label="配送方式" prop="delivery_method">
                <el-select v-model="formData.delivery_method" placeholder="请选择配送方式" style="width: 100%">
                    <el-option 
                        v-for="item in deliveryMethodOptions" 
                        :key="item.value" 
                        :label="item.label" 
                        :value="item.value" 
                    />
                </el-select>
            </el-form-item>
            <el-form-item label="快递公司" v-if="formData.delivery_method === 'express'">
                <el-select v-model="formData.express_company" placeholder="请选择快递公司（可选）" clearable style="width: 100%">
                    <el-option label="顺丰快递" value="顺丰快递" />
                    <el-option label="圆通快递" value="圆通快递" />
                    <el-option label="中通快递" value="中通快递" />
                    <el-option label="申通快递" value="申通快递" />
                    <el-option label="韵达快递" value="韵达快递" />
                    <el-option label="京东快递" value="京东快递" />
                    <el-option label="邮政EMS" value="邮政EMS" />
                    <el-option label="其他" value="其他" />
                </el-select>
            </el-form-item>
            <el-form-item label="快递单号" v-if="formData.delivery_method === 'express'">
                <el-input v-model="formData.express_no" placeholder="请输入快递单号（可选）" />
            </el-form-item>
            <el-form-item label="发货备注">
                <el-input v-model="formData.remark" type="textarea" :rows="3" placeholder="请输入发货备注（可选）" />
            </el-form-item>
        </el-form>

        <template #footer>
            <div class="dialog-footer">
                <el-button @click="handleClose">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">
                    确认发货
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, watch } from 'vue'
import { ElMessage } from 'element-plus'
import { useEnum } from '@/hooks/useEnum'
import { shipPointsGoodsOrder } from '@/pages-admin/main/api/points-goods/pointsGoodsOrder'

// 使用枚举 Hook
const { options: deliveryMethodOptions } = useEnum('default.points_goods_order.delivery_method')

// 弹窗显示状态
const dialogVisible = ref(false)
const loading = ref(false)

// 订单数据
const orderData = ref<any>(null)

// 表单引用
const formRef = ref()

// 表单数据
const formData = reactive({
    delivery_method: 'express', // 默认快递配送
    express_company: '',
    express_no: '',
    remark: ''
})

// 表单验证规则
const rules = {
    delivery_method: [
        { required: true, message: '请选择配送方式', trigger: 'change' }
    ]
}

// 监听配送方式变化，清空快递相关字段
watch(() => formData.delivery_method, (newValue) => {
    if (newValue === 'delivery') {
        // 选择自提时，清空快递相关字段
        formData.express_company = ''
        formData.express_no = ''
    }
})

// 设置弹窗数据
const setDialogData = (data: any) => {
    orderData.value = data
    // 重置表单数据
    formData.delivery_method = 'express' // 默认快递配送
    formData.express_company = ''
    formData.express_no = ''
    formData.remark = ''
}

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true
}

// 关闭弹窗
const handleClose = () => {
    dialogVisible.value = false
    orderData.value = null
    // 重置表单
    if (formRef.value) {
        formRef.value.resetFields()
    }
}

// 提交发货
const handleSubmit = async () => {
    if (!formRef.value || !orderData.value) return

    try {
        await formRef.value.validate()

        loading.value = true

        const params = {
            delivery_method: formData.delivery_method,
            express_company: formData.express_company,
            express_no: formData.express_no,
            remark: formData.remark
        }

        await shipPointsGoodsOrder(orderData.value?.id, params)

        ElMessage.success('发货成功')
        handleClose()

        // 触发父组件刷新
        emit('complete')

    } catch (error) {
        console.error('发货失败:', error)
        ElMessage.error('发货失败')
    } finally {
        loading.value = false
    }
}

// 定义事件
const emit = defineEmits(['complete'])

// 暴露方法
defineExpose({
    setDialogData,
    openDialog
})
</script>

<style scoped></style>
