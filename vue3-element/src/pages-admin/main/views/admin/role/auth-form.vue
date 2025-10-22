<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="500px" :destroy-on-close="true">

        <el-form :model="formData" label-width="90px" ref="formRef" :rules="formRules" v-loading="loading">

            <el-form-item label="权限" prop="rules">
                <div class="flex items-center justify-between w-11/12">

                    <el-checkbox label="全选" @change="handleSelectAll" />
                    <el-checkbox label="展开/折叠" @change="handleExpand" />

                    <el-checkbox v-model="checkStrictly" label="父子联动" />

                </div>
                <el-scrollbar height="350" class="w-full">
                    <el-tree :data="admin_menu_list" :props="{
                        label: 'title',
                        children: 'children'
                    }" :default-checked-keys="formData.rules" :check-strictly="checkStrictly" show-checkbox
                        @check-change="handleCheckChange" default-expand-all node-key="id" ref="treeRef" />
                </el-scrollbar>
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
import type { CheckboxValueType, FormInstance } from 'element-plus';
import { computed, reactive, ref, toRaw, watch } from 'vue';
import { updateAdminRoleRules, getAdminRoleInfo } from '@/pages-admin/main/api/admin/adminRole'
import { getAdminMenuTree } from '@/pages-admin/main/api/admin/adminMenu';


const checkStrictly = ref(false)
const treeRef: Record<string, any> | null = ref(null)
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 获取权限数据
const admin_menu_list = ref<Record<string, any>[]>([])
getAdminMenuTree({}).then((res) => {
    admin_menu_list.value = res.data
})

const handleExpand = (check: CheckboxValueType) => {
    const treeList = admin_menu_list.value
    for (let i = 0; i < treeList.length; i++) {
        treeRef.value.store.nodesMap[treeList[i].id].expanded = check
    }
}

const handleSelectAll = (check: CheckboxValueType) => {
    if (check) {
        treeRef.value.setCheckedNodes(toRaw(admin_menu_list.value))
    } else {
        treeRef.value?.setCheckedKeys([])
    }
}
const handleCheckChange = () => {
    const checkedKeys = treeRef.value?.getCheckedKeys()
    const halfCheckedKeys = treeRef.value?.getHalfCheckedKeys()!
    checkedKeys?.unshift.apply(checkedKeys, halfCheckedKeys)
    formData.rules = treeRef.value.getCheckedKeys()
}



/**
* 表单数据
*/
const initialFormData = {
    id: '',
    rules: [],
}
const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {

    }
})


const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()

    loading.value = true
    updateAdminRoleRules(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}



const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '添加角色权限'

    if (row) {
        popTitle = '更新角色权限'
        const data = await (await getAdminRoleInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) {
                if (key == 'rules') {
                    const newArr: any = []
                    Object.keys(data.rules).forEach((i) => {
                        checked(data.rules[i], admin_menu_list.value, newArr)
                    })
                    formData[key] = newArr
                } else {
                    formData[key] = data[key]
                }

            }
        })
    }

    loading.value = false
}

function checked(menuKey: string, data: any, newArr: any) {
    Object.keys(data).forEach((key: string) => {
        const item = data[key]
        if (item.id == menuKey) {
            if (!item.children || item.children.length == 0) {
                newArr.push(item.id)
            }
        } else {
            if (item.children && item.children.length > 0) {
                checked(menuKey, item.children, newArr)
            }
        }
    })
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})




</script>