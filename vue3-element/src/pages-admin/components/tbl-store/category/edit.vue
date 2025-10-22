<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="600px" :destroy-on-close="true">

        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="分类名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入商品分类名称"></el-input>
            </el-form-item>
            <el-form-item label="父级菜单" prop="pid">
                <el-tree-select class="flex-1" v-model="formData.pid" :data="menuTreeOptions" clearable
                    node-key="id" :props="{
                        label: 'name'
                    }" :default-expand-all="true" placeholder="请选择父级菜单" check-strictly />
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
            <el-form-item label="服务费率" prop="service_fee_rate">
                <el-input-number v-model="formData.service_fee_rate" :min="0" :max="100" placeholder="请输入服务费率"></el-input-number>
                <span>默认服务费率，用于店铺申请默认服务费率,单位为百分比，如2.00，具体比例以店铺实际费率为准</span>
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
import { computed, reactive, ref, toRaw } from 'vue';
import { createTblStoreCategory, updateTblStoreCategory, getTblStoreCategoryInfo, getTblStoreCategoryTree } from '@/pages-admin/main/api/tbl-store/tblStoreCategory'
import PickerImage from '@/components/attachment/picker-image.vue'

const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})



const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

/**
* 表单数据
*/
const initialFormData = {
    id: '',
    platform: props.platform,
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

    }
})


//加载上级分类
const menuTreeOptions = ref<any[]>([])
const fetchMenuTreeOptions = () => {

    menuTreeOptions.value = []

    getTblStoreCategoryTree({ platform: props.platform }).then(res => {
        const menu: any = { id: 0, name: '顶级', children: [] }
        menu.children = res.data
        menuTreeOptions.value.push(menu)
    })
}




const emit = defineEmits(['complete'])
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateTblStoreCategory : createTblStoreCategory
    loading.value = true
    requestFun(formData).then(res => {
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
        const data = await (await getTblStoreCategoryInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })

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