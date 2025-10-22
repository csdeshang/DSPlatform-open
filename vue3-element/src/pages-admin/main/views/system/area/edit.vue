<template>
    <div>
        <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

            <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">


                <el-form-item label="区域名称" prop="name">
                    <el-input v-model="formData.name" placeholder="请输入菜单名称"></el-input>
                </el-form-item>
                <el-form-item label="父级菜单" prop="pid">
                    <el-tree-select class="flex-1" v-model="formData.pid" :data="areaTreeOptions" clearable
                        node-key="id" :props="{
                            label: 'name'
                        }" :default-expand-all="true" placeholder="请选择父级菜单" check-strictly />
                </el-form-item>



                <el-form-item label="菜单显示" prop="is_show" v-if="formData.is_show != 'BUTTON'">
                    <el-switch v-model="formData.is_show" active-text="显示" inactive-text="隐藏" :active-value="1"
                        :inactive-value="0" />
                </el-form-item>
                <el-form-item label="排序" prop="sort">
                    <el-input-number v-model="formData.sort" :min="1" :max="255"
                        placeholder="请输入排序值"></el-input-number>
                </el-form-item>


            </el-form>

            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="dialogVisible = false">取消</el-button>
                    <el-button type="primary" :loading="loading" @click="handleSubmit">确认</el-button>
                </span>
            </template>
        </el-dialog>


    </div>
</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref, toRaw } from 'vue';

import { createSysArea, updateSysArea, getSysAreaInfo, getSysAreaOptions } from '@/pages-admin/main/api/system/sysArea'



const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''


/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    pid: 0,
    name: '',
    is_show: 1,
    sort: '255',
    longitude: '',
    latitude: '',
}

const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入区域名称', trigger: 'blur' }
        ],
        pid: [
            { required: true, message: '请选择父级菜单', trigger: 'blur' }
        ],
        sort: [
            { required: true, message: '请输入排序值', trigger: 'blur' }
        ]

    }
})



//初始化菜单数据
const areaTreeOptions = ref<any[]>([])
const fetchAreaTreeOptions = () => {
    //获取深度为2的地区数据
    getSysAreaOptions({deep:2}).then(data => {
        const area: any = { id: 0, name: '顶级', children: [] }
        area.children = data.data
        areaTreeOptions.value.push(area)
    })
}



const emit = defineEmits(['complete'])


const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateSysArea : createSysArea
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
    fetchAreaTreeOptions()
    Object.assign(formData, initialFormData)
    popTitle = '添加地区'
    


    if (row.id) {
        popTitle = '修改地区'
        const data = await (await getSysAreaInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    } else if (row.pid) {
        popTitle = '添加地区'
        formData.pid = row.pid
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