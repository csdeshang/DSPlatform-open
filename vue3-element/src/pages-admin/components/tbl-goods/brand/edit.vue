<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入商品品牌名称"></el-input>
            </el-form-item>
            <el-form-item label="父级品牌" prop="pid">
                <el-tree-select class="flex-1" v-model="formData.pid" :data="menuTreeOptions" clearable
                    node-key="id" :props="{
                        label: 'name'
                    }" :default-expand-all="true" placeholder="请选择父级菜单" check-strictly />
            </el-form-item>
            <el-form-item label="品牌图片" prop="brand_logo">
                <PickerImage v-model="formData.brand_logo" :limit=1 type="image" />
            </el-form-item>
            <el-form-item label="品牌描述" prop="description">
                <el-input v-model="formData.description" placeholder="请输入品牌描述" type="textarea"></el-input>
            </el-form-item>

            <el-form-item label="是否推荐" prop="is_recommend">
                <el-switch v-model="formData.is_recommend" :active-value="1" :inactive-value="0" />
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
import { computed, reactive, ref, toRaw } from 'vue';
import { createTblGoodsBrand, updateTblGoodsBrand, getTblGoodsBrandInfo,getTblGoodsBrandTree } from '@/pages-admin/main/api/tbl-goods/tblGoodsBrand'
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
    description: '',
    brand_logo: '',
    is_recommend: 0,
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

//加载上级品牌
const menuTreeOptions = ref<any[]>([])
const fetchMenuTreeOptions = () => {

    menuTreeOptions.value = []

    getTblGoodsBrandTree({ platform: props.platform }).then(res => {
        const menu: any = { id: 0, name: '顶级', children: [] }
        menu.children = res.data
        menuTreeOptions.value.push(menu)
    })
}




const emit = defineEmits(['complete'])
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateTblGoodsBrand : createTblGoodsBrand
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
    popTitle = '添加品牌'
    formData.pid = row.pid

    if (row.id) {
        popTitle = '编辑品牌'
        const data = await (await getTblGoodsBrandInfo(row.id,)).data
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