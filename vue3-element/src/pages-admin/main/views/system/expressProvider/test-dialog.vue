<template>
    <el-dialog v-model="dialogVisible" :title="`测试快递查询 - ${provider?.name || ''}`" width="600px"
        :destroy-on-close="true">
        <el-form :model="formData" ref="formRef" :rules="formRules" label-width="120px">
            <el-alert type="info" :closable="false" class="mb-4">
                <p>测试将使用 <strong>{{ provider?.name }}</strong> 服务商查询快递信息</p>
                <p v-if="provider?.website" class="mt-1">官网: {{ provider.website }}</p>
            </el-alert>

            <el-form-item label="快递单号" prop="express_no">
                <el-input v-model="formData.express_no" placeholder="请输入快递单号" clearable />
            </el-form-item>

            <el-form-item label="快递公司" prop="express_code">
                <el-select v-model="formData.express_code" placeholder="请选择快递公司" clearable filterable>
                    <el-option
                        v-for="company in companyList"
                        :key="company.code"
                        :label="company.name"
                        :value="company.code"
                    />
                </el-select>
            </el-form-item>

            <el-form-item label="手机号后四位" prop="phone">
                <el-input v-model="formData.phone" placeholder="部分快递公司需要手机号后四位" maxlength="4" clearable />
                <div class="mt-1 text-gray-500">
                    <p>部分快递公司（如顺丰）需要提供手机号后四位进行验证</p>
                </div>
            </el-form-item>

            <!-- 查询结果展示 -->
            <div v-if="queryResult" class="mt-4">
                <el-divider content-position="left">查询结果</el-divider>
                
                <el-descriptions :column="2" border>
                    <el-descriptions-item label="快递单号">{{ queryResult.express_no }}</el-descriptions-item>
                    <el-descriptions-item label="快递公司">{{ queryResult.express_name }}</el-descriptions-item>
                    <el-descriptions-item label="物流状态">
                        <el-tag :type="getStateTagType(queryResult.state)">{{ queryResult.state_desc }}</el-tag>
                    </el-descriptions-item>
                    <el-descriptions-item label="服务商">{{ queryResult.provider }}</el-descriptions-item>
                </el-descriptions>

                <div v-if="queryResult.traces && queryResult.traces.length > 0" class="mt-4">
                    <h4>物流轨迹</h4>
                    <el-timeline>
                        <el-timeline-item
                            v-for="(trace, index) in queryResult.traces"
                            :key="index"
                            :timestamp="trace.time"
                        >
                            <div>
                                <p class="font-medium">{{ trace.station }}</p>
                                <p v-if="trace.remark" class="text-gray-600 text-sm mt-1">{{ trace.remark }}</p>
                                <p v-if="trace.location" class="text-gray-500 text-xs mt-1">{{ trace.location }}</p>
                            </div>
                        </el-timeline-item>
                    </el-timeline>
                </div>
            </div>
        </el-form>

        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false" :disabled="loading">取消</el-button>
                <el-button type="primary" @click="testExpressQuery" :loading="loading">
                    查询快递
                </el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import { ref, reactive, watch } from 'vue';
import { ElMessage } from 'element-plus';
import type { FormInstance } from 'element-plus';
import { getSysExpressList } from '@/pages-admin/main/api/system/sysExpress';

import { testExpressQuery as testExpressQueryApi } from '@/pages-admin/main/api/system/sysExpressProvider';

const dialogVisible = ref(false);
const loading = ref(false);
const formRef = ref<FormInstance>();
const provider = ref<any>(null);
const queryResult = ref<any>(null);
const companyList = ref<any[]>([]);

// 表单数据
const formData = reactive({
    express_no: '',
    express_code: '',
    phone: '',
    provider: ''
});

// 表单验证规则
const formRules = {
    express_no: [
        { required: true, message: '请输入快递单号', trigger: 'blur' },
        { min: 8, message: '快递单号长度不能少于8位', trigger: 'blur' }
    ],
    express_code: [
        { required: true, message: '请选择快递公司', trigger: 'change' }
    ]
};

// 获取状态标签类型
const getStateTagType = (state: number) => {
    const stateMap: Record<number, string> = {
        0: 'info',    // 无轨迹
        1: 'warning', // 已揽收
        2: 'primary', // 在途中
        3: 'success', // 已签收
        4: 'danger',  // 问题件
        5: 'warning', // 同城派送中
        6: 'danger',  // 退回
        7: 'info'     // 转单
    };
    return stateMap[state] || 'info';
};

// 加载快递公司列表
const loadCompanyList = async () => {
    if (!provider.value?.provider) return;
    
    try {
        const res = await getSysExpressList({ provider: provider.value.provider });
        if (res.code === 10000) {
            companyList.value = res.data || [];
        }
    } catch (error) {
        console.error('加载快递公司列表失败:', error);
    }
};

// 监听服务商变化，重新加载快递公司列表
watch(() => provider.value?.provider, () => {
    loadCompanyList();
});

// 设置服务商信息
const setDialogData = (providerData: any) => {
    provider.value = providerData;
    formData.provider = providerData.provider;
    queryResult.value = null; // 重置查询结果
};

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true;
    // 重置表单
    formData.express_no = '';
    formData.express_code = '';
    formData.phone = '';
    queryResult.value = null;
    
    if (formRef.value) {
        formRef.value.resetFields();
    }
    
    // 加载快递公司列表
    loadCompanyList();
};

// 测试快递查询
const testExpressQuery = async () => {
    if (!formRef.value) return;

    try {
        // 表单验证
        await formRef.value.validate();

        // 发送查询请求
        loading.value = true;
        queryResult.value = null;

        const res = await testExpressQueryApi({
            provider: formData.provider,
            express_no: formData.express_no,
            express_code: formData.express_code,
            phone: formData.phone
        });

        if (res.code === 10000) {
            queryResult.value = res.data;
            ElMessage.success('快递查询成功');
        } else {
            ElMessage.error(res.message || '快递查询失败');
        }
    } catch (error: any) {
        console.error('快递查询失败:', error);
        if (error.message) {
            ElMessage.error(error.message);
        } else {
            ElMessage.error('表单验证失败或查询过程中出现错误');
        }
    } finally {
        loading.value = false;
    }
};

// 暴露方法给父组件
defineExpose({
    openDialog,
    setDialogData
});
</script>

<style scoped>
.dialog-footer {
    display: flex;
    justify-content: flex-end;
}

.el-alert {
    margin-bottom: 16px;
}

.el-divider {
    margin: 16px 0;
}

.font-medium {
    font-weight: 500;
}

.text-gray-600 {
    color: #6b7280;
}

.text-gray-500 {
    color: #9ca3af;
}

.text-sm {
    font-size: 0.875rem;
}

.text-xs {
    font-size: 0.75rem;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mt-4 {
    margin-top: 1rem;
}
</style> 