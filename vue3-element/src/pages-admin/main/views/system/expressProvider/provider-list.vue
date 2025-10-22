<template>
    <el-card shadow="never" class="mb-5">
        <el-form-item label="是否启用快递查询">
            <el-switch v-model="express_is_enabled" size="large" @change="updateExpressIsEnabled" active-value="1"
                inactive-value="0" />
        </el-form-item>
    </el-card>

    <el-card shadow="never">
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="服务商" prop="name" width="200" />
            <el-table-column label="服务商标识" prop="provider" width="200" />
            <el-table-column label="描述" prop="description" min-width="300" />
            <el-table-column label="默认" prop="is_default" width="100" align="center">
                <template #default="{ row }">
                    <el-tag v-if="row.is_default" type="warning" effect="dark">
                        默认
                    </el-tag>
                    <span v-else class="text-gray-400">-</span>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">配置</el-button>
                    <el-button type="primary" link @click="handleTest(row)">
                        测试查询
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>

    <!-- 当前服务商是否为特定类型 -->
    <KuaidiniaoExpressEdit v-if="currentProvider?.provider === 'kuaidiniao'" ref="kuaidiniaoExpressRef"
        @complete="fetchExpressProviderList" />

    <Kuaidi100ExpressEdit v-if="currentProvider?.provider === 'kuaidi100'" ref="kuaidi100ExpressRef" 
        @complete="fetchExpressProviderList" />

    <!-- 测试快递查询弹窗组件 -->
    <ExpressTestDialog ref="expressTestDialog" />
</template>

<script lang="ts" setup>
import { reactive, ref, nextTick } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { getExpressProviderList } from '@/pages-admin/main/api/system/sysExpressProvider';

// 直接引入所有配置组件
import KuaidiniaoExpressEdit from './providers/kuaidiniao-edit.vue';
import Kuaidi100ExpressEdit from './providers/kuaidi100-edit.vue';
import ExpressTestDialog from './test-dialog.vue';

// 当前选中的服务商
const currentProvider = ref<any>(null);

// 单独引用各个配置组件
const kuaidiniaoExpressRef = ref();
const kuaidi100ExpressRef = ref();
const expressTestDialog = ref();

// 数据
const tableData = reactive({
    loading: true,
    data: [],
});

// 加载列表
const fetchExpressProviderList = () => {
    tableData.loading = true;
    getExpressProviderList({}).then(res => {
        tableData.loading = false;
        tableData.data = res.data;
    }).catch(() => {
        tableData.loading = false;
        ElMessage.error('获取快递查询服务商列表失败');
    });
};

// 初始化列表
fetchExpressProviderList();

// 获取当前选中服务商的组件引用
const getCurrentProviderRef = () => {
    if (!currentProvider.value) return null;

    switch (currentProvider.value.provider) {
        case 'kuaidiniao':
            return kuaidiniaoExpressRef.value;
        case 'kuaidi100':
            return kuaidi100ExpressRef.value;
        default:
            return kuaidiniaoExpressRef.value;
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

// 处理测试
const handleTest = async (row: any) => {
    // 设置服务商并打开弹窗
    expressTestDialog.value.setDialogData(row)
    expressTestDialog.value.openDialog()
}

// 是否启用快递查询
const express_is_enabled = ref('0');
// 获取快递查询是否启用
const fetchExpressIsEnabled = async () => {
    try {
        let key = 'express_is_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            express_is_enabled.value = response.data.config_value;
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchExpressIsEnabled();

// 更新快递查询是否启用
const updateExpressIsEnabled = async () => {
    try {
        let key = 'express_is_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'express',
            config_key: key,
            config_value: express_is_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新快递查询配置成功');
        } else {
            ElMessage.error('更新快递查询配置失败');
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