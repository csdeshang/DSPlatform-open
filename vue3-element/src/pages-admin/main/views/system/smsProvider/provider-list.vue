<template>

    <el-card shadow="never" class="mb-5">
        <el-form-item label="是否启用短信">
            <el-switch v-model="sms_is_enabled" size="large" @change="updateSmsIsEnabled" active-value="1"
                inactive-value="0" />
        </el-form-item>
    </el-card>

    <el-card shadow="never">
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="服务商" prop="name" width="250" />
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
                    <el-button type="primary" link @click="handleTest(row)">
                        测试短信发送
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>

    <!-- 当前服务商是否为特定类型 -->
    <TencentSmsEdit v-if="currentProvider?.provider === 'tencent'" ref="tencentSmsRef"
        @complete="fetchSmsProviderList" />

    <AliyunSmsEdit v-if="currentProvider?.provider === 'aliyun'" ref="aliyunSmsRef" @complete="fetchSmsProviderList" />


    <!-- 测试短信弹窗组件 -->
    <SmsTestDialog ref="smsTestDialog" />
</template>

<script lang="ts" setup>
import { reactive, ref, nextTick } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';
import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'

import { getSmsProviderList } from '@/pages-admin/main/api/system/sysSmsProvider';

// 直接引入所有配置组件
import TencentSmsEdit from './providers/tencent-edit.vue';
import AliyunSmsEdit from './providers/aliyun-edit.vue';
import SmsTestDialog from './test-dialog.vue';

// 当前选中的服务商
const currentProvider = ref<any>(null);

// 单独引用各个配置组件
const tencentSmsRef = ref();
const aliyunSmsRef = ref();
const smsTestDialog = ref();

// 数据
const tableData = reactive({
    loading: true,
    data: [],
});

// 加载列表
const fetchSmsProviderList = () => {
    tableData.loading = true;
    getSmsProviderList({}).then(res => {
        tableData.loading = false;

        tableData.data = res.data;
    }).catch(() => {
        tableData.loading = false;
        ElMessage.error('获取服务商列表失败');
    });
};

// 初始化列表
fetchSmsProviderList();

// 获取当前选中服务商的组件引用
const getCurrentProviderRef = () => {
    if (!currentProvider.value) return null;

    switch (currentProvider.value.provider) {
        case 'tencent':
            return tencentSmsRef.value;
        case 'aliyun':
            return aliyunSmsRef.value;
        default:
            return aliyunSmsRef.value;
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
    smsTestDialog.value.setDialogData(row)
    smsTestDialog.value.openDialog()
}


// 是否启用短信
const sms_is_enabled = ref('0');
// 获取短信是否启用
const fetchSmsIsEnabled = async () => {
    try {
        let key = 'sms_is_enabled'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            sms_is_enabled.value = response.data.config_value;
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}
fetchSmsIsEnabled();
// 更新短信是否启用
const updateSmsIsEnabled = async () => {
    try {
        let key = 'sms_is_enabled'
        const response = await updateSysConfigInfo({
            config_type: 'sms',
            config_key: key,
            config_value: sms_is_enabled.value
        })
        if (response.code === 10000) {
            ElMessage.success('更新短信配置成功');
        } else {
            ElMessage.error('更新短信配置失败');
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