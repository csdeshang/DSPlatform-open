<template>

    <el-card shadow="never" class="mb-5">
        <el-form-item label="是否启用地图">
            <el-switch v-model="lbs_is_enabled" size="large" @change="updateLbsIsEnabled" active-value="1" inactive-value="0"  />
        </el-form-item>
    </el-card>



    <el-card shadow="never">




        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="地图名称" prop="name" width="250" />
            <el-table-column label="服务商标识" prop="provider" width="300" />
            <el-table-column label="默认" prop="is_default" width="300" align="center">
                <template #default="{ row }">
                    <el-tag v-if="row.is_default" type="warning" effect="dark">
                        默认
                    </el-tag>
                    <span v-else class="text-gray-400">-</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="right" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">配置</el-button>
                </template>
            </el-table-column>
        </el-table>

    </el-card>

    <!-- 引入高德地图配置组件 -->
    <GaodeLbsEdit v-if="currentProvider?.provider === 'gaode'" ref="gaodeProviderRef"
        @complete="fetchLbsProviderList" />

    <!-- 引入腾讯云 地图配置组件 -->
    <TencentLbsEdit v-if="currentProvider?.provider === 'tencent'" ref="tencentProviderRef"
        @complete="fetchLbsProviderList" />

    <!-- 引入百度地图配置组件 -->
    <BaiduLbsEdit v-if="currentProvider?.provider === 'baidu'" ref="baiduProviderRef"
        @complete="fetchLbsProviderList" />

    <!-- 引入天地图配置组件 -->
    <TianDiTuLbsEdit v-if="currentProvider?.provider === 'tianditu'" ref="tiandituProviderRef"
        @complete="fetchLbsProviderList" />



</template>
<script lang="ts" setup>

import { reactive, ref, nextTick } from 'vue';
import { ElMessage } from 'element-plus';
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { getLbsProviderList } from '@/pages-admin/main/api/system/sysLbsProvider'

// 直接引入所有配置组件
import GaodeLbsEdit from './providers/gaode-edit.vue';
import TencentLbsEdit from './providers/tencent-edit.vue';
import BaiduLbsEdit from './providers/baidu-edit.vue';
import TianDiTuLbsEdit from './providers/tianditu-edit.vue';

// 当前选中的服务商
const currentProvider = ref<any>(null);

// 单独引用各个配置组件
const gaodeProviderRef = ref();
const tencentProviderRef = ref();
const baiduProviderRef = ref();
const tiandituProviderRef = ref();

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载列表
const fetchLbsProviderList = () => {
    tableData.loading = true;
    getLbsProviderList({}).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
        ElMessage.error('获取服务商列表失败');
    })
}
// 初始化列表
fetchLbsProviderList();

// 获取当前选中服务商的组件引用
const getCurrentProviderRef = () => {
    if (!currentProvider.value) return null;

    switch (currentProvider.value.provider) {
        case 'gaode':
            return gaodeProviderRef.value;
        case 'tencent':
            return tencentProviderRef.value;
        case 'baidu':
            return baiduProviderRef.value;
        case 'tianditu':
            return tiandituProviderRef.value;
        default:
            return null;
    }
};

// 编辑
const handleEdit = async (row: any) => {
    // 先设置当前服务商
    currentProvider.value = row;

    // 使用 nextTick 确保组件已经被渲染
    await nextTick();

    // 获取当前组件的引用
    const providerRef = getCurrentProviderRef();

    if (providerRef) {
        // 设置数据并打开弹窗
        providerRef.setDialogData(row);
        providerRef.openDialog();
    } else {
        ElMessage.warning('配置组件未加载完成，请稍后再试');
    }
};



// 是否启用地图
const lbs_is_enabled = ref('0');
// 获取地图是否启用地图
const fetchLbsIsEnabled = async () => {
    try {
        let key = 'lbs_is_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            lbs_is_enabled.value = response.data.config_value;
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchLbsIsEnabled();
// 更新地图是否启用地图
const updateLbsIsEnabled = async () => {
    try {
        let key = 'lbs_is_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'lbs',
            config_key: key,
            config_value: lbs_is_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新地图配置成功');
        } else {
            ElMessage.error('更新地图配置失败');
        }
    } catch (error) {
        console.error('更新系统配置时出错:', error);
    }
}



</script>

<style scoped>
.dialog-footer {
    display: flex;
    justify-content: flex-end;
}

.el-alert {
    margin-bottom: 16px;
}
</style>