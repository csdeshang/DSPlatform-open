<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="分类名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入积分商品分类名称"></el-input>
            </el-form-item>
            <el-form-item label="父级分类" prop="pid">
                <el-tree-select class="flex-1" v-model="formData.pid" :data="menuTreeOptions" clearable
                    node-key="id" :props="{
                        label: 'name'
                    }" :default-expand-all="true" placeholder="请选择父级分类" check-strictly />
            </el-form-item>
            <el-form-item label="分类图片" prop="image">
                <PickerImage v-model="formData.image" :limit=1 type="image" />
            </el-form-item>
            <el-form-item label="是否显示" prop="is_show">
                <el-switch v-model="formData.is_show" :active-value="1" :inactive-value="0" />
            </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <el-input-number v-model="formData.sort" :min="0" :max="999" placeholder="请输入排序"></el-input-number>
                </el-form-item>
        </el-form>
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import PickerImage from '@/components/attachment/picker-image.vue'
import { 
    createPointsGoodsCategory,
    updatePointsGoodsCategory,
    getPointsGoodsCategoryInfo,
    getPointsGoodsCategoryTree
} from '@/pages-admin/main/api/points-goods/pointsGoodsCategory';

// 定义组件名称
defineOptions({
    name: 'PointsGoodsCategoryEdit'
})

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 表单数据
const initialFormData = {
    id: '',
    pid: 0,
    name: '',
    image: '',
    is_show: 1,
    sort: 0,
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入分类名称', trigger: 'blur' }
        ]
    }
})

// 上级分类选项
const menuTreeOptions = ref<any[]>([])

// 加载上级分类选项
const fetchMenuTreeOptions = () => {
    menuTreeOptions.value = []
    getPointsGoodsCategoryTree({}).then(res => {
        const menu: any = { id: 0, name: '顶级', children: [] }
        menu.children = res.data
        menuTreeOptions.value.push(menu)
    })
}

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updatePointsGoodsCategory : createPointsGoodsCategory
    loading.value = true
    requestFun(formData).then(() => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

const setDialogData = async (row: any = null) => {
    loading.value = true
    fetchMenuTreeOptions()
    Object.assign(formData, initialFormData)
    popTitle = '添加分类'
    formData.pid = row.pid

    if (row.id) {
        popTitle = '编辑分类'
        try {
            const data = await (await getPointsGoodsCategoryInfo(row.id)).data
            Object.keys(formData).forEach((key: string) => {
                if (data[key] != undefined) formData[key] = data[key]
            })
        } catch (error) {
            console.error('获取分类详情失败:', error)
        }
    }
    loading.value = false
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>
