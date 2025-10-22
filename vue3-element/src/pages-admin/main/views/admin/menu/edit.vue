<template>

    <el-dialog v-model="dialogVisible" :title="popTitle" width="650px" :destroy-on-close="true">

        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="菜单类型" prop="type">
                <el-radio-group v-model="formData.type">
                    <el-radio value="directory">目录</el-radio>
                    <el-radio value="menu">菜单</el-radio>
                    <el-radio value="button">按钮</el-radio>
                </el-radio-group>
            </el-form-item>

            <el-form-item label="菜单名称" prop="title">
                <el-input v-model="formData.title" placeholder="请输入菜单名称"></el-input>
            </el-form-item>
            <el-form-item label="父级菜单" prop="pid">
                <el-tree-select class="flex-1" v-model="formData.pid" :data="menuTreeOptions" clearable node-key="id"
                    :props="{
                        label: 'title'
                    }" :default-expand-all="true" placeholder="请选择父级菜单" check-strictly />
            </el-form-item>
            <el-form-item v-if="formData.type != 'button'" label="菜单图标" prop="icon">
                <div class="flex items-center">
                    <IconPicker v-model="formData.icon" />
                    <div class="ml-2 flex-1">
                        <el-input v-model="formData.icon" placeholder="菜单图标"></el-input>
                    </div>
                </div>
                <div class="form-tips">
                    选择或输入图标，格式为 "element ArrowDown"
                </div>
            </el-form-item>

            <el-form-item label="路由地址" v-if="formData.type != 'button'" prop="path">
                <el-input v-model="formData.path" placeholder="请输入路由地址"></el-input>
                <div class="form-tips">
                    跳转访问的路由地址，如：`admin`
                </div>
            </el-form-item>

            <el-form-item v-if="formData.type == 'menu'" label="组件类型" prop="component">
                <el-radio-group v-model="platform">
                    <el-radio value="all" size="large">全部</el-radio>
                    <el-radio v-for="item in platformList" :key="item.id" :value="item.platform" size="large">{{ item.name }}</el-radio>
                </el-radio-group>
            </el-form-item>



            <el-form-item v-if="formData.type == 'menu'" label="组件路径" prop="component">
                <div class="flex-1">
                    <el-autocomplete class="w-full" v-model="formData.component" :fetch-suggestions="querySearch"
                        clearable placeholder="请输入组件路径" />
                    <div class="form-tips">
                        访问的组件路径，例如：`admin/setting`，默认在`views`目录下
                    </div>
                </div>
            </el-form-item>

            <el-form-item label="API地址" v-if="formData.type != 'directory'" prop="api_url">
                <el-input v-model="formData.api_url" placeholder="请输入API接口地址"></el-input>
                <div class="form-tips">
                    服务端API权限使用，接口地址,例如`user/user`
                </div>
            </el-form-item>


            <el-form-item label="菜单显示" prop="is_show" v-if="formData.type != 'button'">
                <el-switch v-model="formData.is_show"  :active-value="1"
                    :inactive-value="0" />
            </el-form-item>
            <el-form-item label="排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="1" placeholder="请输入排序值"></el-input-number>
            </el-form-item>

            <el-form-item label="是否可用" prop="is_enabled" v-if="formData.type != 'button'">
                <el-switch v-model="formData.is_enabled" active-text="正常" inactive-text="禁用" :active-value="1"
                    :inactive-value="0" />
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
import { computed, reactive, ref, toRaw, watch } from 'vue';

import { createAdminMenu, updateAdminMenu, getAdminMenuInfo, getAdminMenuOptions } from '@/pages-admin/main/api/admin/adminMenu'
import { arrayToTree, treeToArray } from '@/utils/util';
import IconPicker from '@/components/icon-picker/index.vue';
import Icon from '@/components/icon/index.vue';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const platform = ref('all')

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    path: '',
    component: '',
    pid: 0,
    api_url: '',
    title: '',
    icon: '',
    is_show: 1,
    is_enabled: 1,
    type: 'directory',
    sort: 255,
}

const formData: Record<string, any> = reactive({ ...initialFormData })

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        title: [
            { required: true, message: '请输入您的用户名', trigger: 'blur' }
        ],
        type: [
            { required: true, message: '请选择菜单类型', trigger: 'blur' }
        ],
        pid: [
            { required: true, message: '请选择父级菜单', trigger: 'blur' }
        ],
        icon: [
            { required: formData.type !== 'button', message: '请输入菜单图标', trigger: 'blur' }
        ],
        path: [
            { required: formData.type !== 'button', message: '请输入路由地址', trigger: 'blur' }
        ],
        component: [
            { required: formData.type === 'menu', message: '请输入组件路径', trigger: 'blur' }
        ],
        api_url: [
            { required: formData.type !== 'directory', message: '请输入API接口地址', trigger: 'blur' }
        ],
        sort: [
            { required: true, message: '请输入排序值', trigger: 'blur' }
        ]

    }
})



// 匹配views里面所有的.vue文件，动态引入
const sysyemModules = import.meta.glob('/src/pages-admin/main/views/**/*.vue')
const mallModules = import.meta.glob('/src/pages-admin/platform/mall/views/**/*.vue')
const foodModules = import.meta.glob('/src/pages-admin/platform/food/views/**/*.vue')
const houseModules = import.meta.glob('/src/pages-admin/platform/house/views/**/*.vue')
const kmsModules = import.meta.glob('/src/pages-admin/platform/kms/views/**/*.vue')

let allModules = { ...sysyemModules, ...mallModules, ...foodModules, ...houseModules, ...kmsModules };


const getModulesKey = () => {
    return Object.keys(allModules).map((item) => item.replace('/src/', '').replace('.vue', ''))
}
let componentsOptions = ref(getModulesKey())


// 监视 platform 的变化
watch(platform, (newPlatform) => {
    // 当 platform 变化时，更新 componentsOptions

    switch (newPlatform) {
        case 'system':
            allModules = { ...sysyemModules }
            break
        case 'house':
            allModules = { ...houseModules }
            break
        case 'kms':
            allModules = { ...kmsModules }
            break
        case 'food':
            allModules = { ...foodModules }
            break
        case 'mall':
            allModules = { ...mallModules }
            break
        case 'all':
            allModules = { ...sysyemModules, ...mallModules, ...houseModules, ...foodModules, ...kmsModules }
            break
    }
    componentsOptions = ref(getModulesKey())
});



const querySearch = (queryString: string, cb: any) => {
    const results = queryString
        ? componentsOptions.value.filter((item) =>
            item.toLowerCase().includes(queryString.toLowerCase())
        )
        : componentsOptions.value
    cb(results.map((item) => ({ value: item })))
}


//初始化菜单数据
const menuTreeOptions = ref<any[]>([])
const fetchMenuTreeOptions = () => {
    // 重置
    menuTreeOptions.value = []
    getAdminMenuOptions({}).then(res => {
        const menu: any = { id: 0, title: '顶级', children: [] }
        menu.children = arrayToTree(
            treeToArray(res.data).filter((item) => item.type != 'button')
        )
        menuTreeOptions.value.push(menu)
    })
}


// 获取平台列表
const platformList = ref<any[]>([])
const fetchPlatformList = async () => {
    try {
        const response = await getSysPlatformList({});
        platformList.value = response.data
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
};




const emit = defineEmits(['complete'])


const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    const requestFun = formData.id ? updateAdminMenu : createAdminMenu
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
    fetchPlatformList()
    Object.assign(formData, initialFormData)
    popTitle = '添加权限'



    if (row.id) {
        popTitle = '更新权限'
        const data = await (await getAdminMenuInfo(row.id)).data
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    } else if (row.pid) {
        popTitle = '添加子权限'
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

<style scoped>
.form-tips {
    font-size: 12px;
    color: #909399;
    margin-top: 4px;
}
</style>