<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" v-loading="loading">

            <!-- 基本信息 -->
            <el-divider>基本信息</el-divider>

            <el-form-item label="商品名称" prop="goods_name">
                <el-input v-model="formData.goods_name" placeholder="请输入商品名称"></el-input>
            </el-form-item>

            <el-form-item label="商品广告词" prop="goods_advword">
                <el-input v-model="formData.goods_advword" placeholder="请输入商品广告词"></el-input>
            </el-form-item>

            <el-form-item label="商品状态" prop="goods_status">
                <el-radio-group v-model="formData.goods_status">
                    <el-radio :value="1">上架</el-radio>
                    <el-radio :value="0">下架</el-radio>
                </el-radio-group>
            </el-form-item>


            <el-form-item label="轮播图" prop="slide_image">
                <PickerImage v-model="formData.slide_image" :limit="10" type="image" />
                <div class="text-sm text-gray-500 mt-1">第一张图片将作为商品主图</div>
            </el-form-item>



            <el-form-item label="积分价格" prop="points_price">
                <el-input-number v-model="formData.points_price" :min="0" placeholder="请输入积分价格">
                    <template #suffix>积分</template>
                </el-input-number>
            </el-form-item>

            <el-form-item label="市场参考价" prop="market_price">
                <el-input-number v-model="formData.market_price" :min="0" :precision="2" placeholder="请输入市场参考价格">
                    <template #suffix>元</template>
                </el-input-number>
            </el-form-item>



            <el-form-item label="库存数量" prop="stock_num">
                <el-input-number v-model="formData.stock_num" :min="0" placeholder="请输入库存数量"></el-input-number>
            </el-form-item>

            <el-form-item label="每人限购" prop="limit_per_user">
                <el-input-number v-model="formData.limit_per_user" :min="0"
                    placeholder="每人限购数量，0为不限制"></el-input-number>
            </el-form-item>

            <el-form-item label="每日限购" prop="limit_per_day">
                <el-input-number v-model="formData.limit_per_day" :min="0" placeholder="每日限购数量，0为不限制"></el-input-number>
            </el-form-item>


            <el-form-item label="排序权重" prop="goods_sort">
                <el-input-number v-model="formData.goods_sort" :min="0" placeholder="请输入排序权重"></el-input-number>
            </el-form-item>

            <el-form-item label="是否热门">
                <el-switch v-model="formData.is_hot" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

            <el-form-item label="是否推荐">
                <el-switch v-model="formData.is_recommend" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

            <el-form-item label="是否新品">
                <el-switch v-model="formData.is_new" :active-value="1" :inactive-value="0"></el-switch>
            </el-form-item>

  

            <el-form-item label="商品详情" prop="goods_body">
                <RichTextEditor v-model="formData.goods_body" />
            </el-form-item>


        </el-form>

        <template #footer>
            <div class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">确定</el-button>
            </div>
        </template>
    </el-drawer>
</template>

<script lang="ts" setup>
import { reactive, ref } from 'vue'
import { ElMessage } from 'element-plus'
import { createPointsGoods, updatePointsGoods } from '@/pages-admin/main/api/points-goods/pointsGoods'
import PickerImage from '@/components/attachment/picker-image.vue'
import RichTextEditor from '@/components/editor/index.vue'

// 弹窗状态
const dialogVisible = ref(false)
const loading = ref(false)
const popTitle = ref('')

// 表单引用
const formRef = ref()

// 表单数据
const formData = reactive({
    id: 0,
    goods_name: '',
    goods_advword: '',
    goods_body: '',
    goods_status: 1,
    category_id: 0,
    slide_image: [],
    points_price: 0,
    market_price: 0,
    stock_num: 0,
    limit_per_user: 0,
    limit_per_day: 0,
    goods_sort: 0,
    is_hot: 0,
    is_recommend: 0,
    is_new: 0
})


// 表单验证规则
const formRules = {
    goods_name: [
        { required: true, message: '请输入商品名称', trigger: 'blur' }
    ],
    slide_image: [
        { required: true, message: '请至少上传一张轮播图', trigger: 'change' }
    ],
    points_price: [
        { required: true, message: '请输入积分价格', trigger: 'blur' }
    ],
    stock_num: [
        { required: true, message: '请输入库存数量', trigger: 'blur' }
    ]
}

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true
}

// 设置弹窗数据
const setDialogData = (row?: any) => {
    if (row) {
        // 编辑模式
        popTitle.value = '编辑积分商品'
        Object.assign(formData, row)

        // 确保轮播图是数组格式
        if (!Array.isArray(formData.slide_image)) {
            formData.slide_image = []
        }
    } else {
        // 新增模式
        popTitle.value = '新增积分商品'
        resetForm()
    }
}

// 重置表单
const resetForm = () => {
    Object.assign(formData, {
        id: 0,
        goods_name: '',
        goods_advword: '',
        goods_body: '',
        goods_status: 1,
        category_id: 0,
        slide_image: [],
        points_price: 0,
        market_price: 0,
        stock_num: 0,
        limit_per_user: 0,
        limit_per_day: 0,
        goods_sort: 0,
        is_hot: 0,
        is_recommend: 0,
        is_new: 0
    })
}


// 提交表单
const handleSubmit = async () => {
    if (!formRef.value) return

    try {
        await formRef.value.validate()
        loading.value = true

        const submitData = { ...formData }

        if (formData.id) {
            // 编辑
            await updatePointsGoods(submitData)
            ElMessage.success('编辑成功')
        } else {
            // 新增
            await createPointsGoods(submitData)
            ElMessage.success('新增成功')
        }

        dialogVisible.value = false
        emit('complete')

    } catch (error) {
        console.error('提交失败:', error)
        ElMessage.error('提交失败')
    } finally {
        loading.value = false
    }
}

// 定义事件
const emit = defineEmits(['complete'])

// 暴露方法
defineExpose({
    openDialog,
    setDialogData
})
</script>

<style scoped>
.dialog-footer {
    text-align: right;
    padding: 20px 0;
}
</style>
