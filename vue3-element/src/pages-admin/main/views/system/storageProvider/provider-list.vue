<template>

    <el-card shadow="never">

        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="存储名称" prop="name" width="250" />
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

    <!-- 引入阿里云配置组件 -->
    <AliyunStorageEdit v-if="currentProvider?.provider === 'aliyun'" ref="aliyunProviderRef"
        @complete="fetchStorageProviderList" />

    <!-- 引入腾讯云配置组件 -->
    <TencentStorageEdit v-if="currentProvider?.provider === 'tencent'" ref="tencentProviderRef"
        @complete="fetchStorageProviderList" />

    <!-- 引入本地存储配置组件 -->
    <LocalStorageEdit v-if="currentProvider?.provider === 'local'" ref="localProviderRef"
        @complete="fetchStorageProviderList" />



</template>
<script lang="ts" setup>

import { reactive, ref, nextTick } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';

import { getStorageProviderList } from '@/pages-admin/main/api/system/sysStorageProvider'

// 直接引入所有配置组件
import AliyunStorageEdit from './providers/aliyun-edit.vue';
import TencentStorageEdit from './providers/tencent-edit.vue';
import LocalStorageEdit from './providers/local-edit.vue';

// 当前选中的服务商
const currentProvider = ref<any>(null);

// 单独引用各个配置组件
const aliyunProviderRef = ref();
const tencentProviderRef = ref();
const localProviderRef = ref();

// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

// 加载列表
const fetchStorageProviderList = () => {
    tableData.loading = true;
    getStorageProviderList({}).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
        ElMessage.error('获取服务商列表失败');
    })
}
// 初始化列表
fetchStorageProviderList();

// 获取当前选中服务商的组件引用
const getCurrentProviderRef = () => {
    if (!currentProvider.value) return null;

    switch (currentProvider.value.provider) {
        case 'aliyun':
            return aliyunProviderRef.value;
        case 'tencent':
            return tencentProviderRef.value;
        case 'local':
            return localProviderRef.value;
        default:
            return localProviderRef.value;
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