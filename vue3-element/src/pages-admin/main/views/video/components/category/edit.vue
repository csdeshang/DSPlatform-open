<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">
        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="分类名称" prop="name">
                <el-input v-model="formData.name" placeholder="请输入视频分类名称"></el-input>
            </el-form-item>
            <el-form-item label="分类类型" prop="type">
                <el-input v-model="typeLabel" readonly />
            </el-form-item>
            <el-form-item label="父级分类" prop="pid">
                <el-tree-select 
                    class="flex-1" 
                    v-model="formData.pid" 
                    :data="menuTreeOptions" 
                    clearable
                    node-key="id" 
                    :props="{ label: 'name' }" 
                    :default-expand-all="true" 
                    placeholder="请选择父级分类" 
                    check-strictly 
                />
            </el-form-item>
            <el-form-item label="描述" prop="description">
                <el-input 
                    v-model="formData.description" 
                    :rows="3" 
                    type="textarea" 
                    placeholder="请输入分类描述" 
                />
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

<script lang="ts" setup name="VideoCategoryEdit">
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { ElMessage } from 'element-plus';
import { createVideoCategory, updateVideoCategory, getVideoCategoryInfo, getVideoCategoryTree } from '@/pages-admin/main/api/video/category'

const props = defineProps({
    // 分类类型：short/drama/live
    type: {
        type: String,
        required: true
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
    type: props.type,
    pid: 0,
    name: '',
    description: '',
    is_show: 1,
    sort: 255,
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 分类类型标签
const typeLabel = computed(() => {
    const typeMap: Record<string, string> = {
        'short': '短视频',
        'drama': '短剧',
        'live': '直播'
    }
    return typeMap[props.type] || props.type
})

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入分类名称', trigger: 'blur' },
            { max: 50, message: '分类名称不能超过50个字符', trigger: 'blur' }
        ],
        type: [
            { required: true, message: '请选择分类类型', trigger: 'change' }
        ]
    }
})

//加载上级分类
const menuTreeOptions = ref<any[]>([])
const fetchMenuTreeOptions = () => {
    menuTreeOptions.value = []
    
    getVideoCategoryTree({ type: props.type }).then(res => {
        const menu: any = { id: 0, name: '顶级分类', children: [] }
        menu.children = res.data
        menuTreeOptions.value.push(menu)
    })
}

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    
    const requestFun = formData.id ? updateVideoCategory : createVideoCategory
    loading.value = true
    
    requestFun(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        ElMessage.success(formData.id ? '编辑成功' : '创建成功')
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
    
    if (row.pid !== undefined) {
        formData.pid = row.pid
    }

    if (row.id) {
        popTitle = '编辑分类'
        const data = await (await getVideoCategoryInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] !== undefined) formData[key] = data[key]
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