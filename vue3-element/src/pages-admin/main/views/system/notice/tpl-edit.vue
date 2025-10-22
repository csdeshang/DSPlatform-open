<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="800px" :destroy-on-close="true">
        <el-form :model="formData" label-width="120px" ref="formRef" :rules="formRules" v-loading="loading">
            <!-- 基本信息 (只读) -->
            <el-divider content-position="left">基本信息</el-divider>
            
            <el-form-item label="模板标题">
                <el-input v-model="formData.title" disabled />
            </el-form-item>

            <el-form-item label="模板描述">
                <el-input v-model="formData.description" type="textarea" rows="2" disabled />
            </el-form-item>

            <el-form-item label="通知渠道">
                <div class="flex flex-row gap-2">
                    <el-tag v-if="channelsList.includes('interna')" size="small">站内通知</el-tag>
                    <el-tag v-if="channelsList.includes('email')" size="small" type="success">邮件</el-tag>
                    <el-tag v-if="channelsList.includes('sms')" size="small" type="warning">短信</el-tag>
                    <el-tag v-if="channelsList.includes('wechat_official')" size="small" type="info">微信公众号</el-tag>
                    <el-tag v-if="channelsList.includes('wechat_mini')" size="small" type="primary">微信小程序</el-tag>
                </div>
            </el-form-item>

            <!-- 站内通知配置 (可编辑) -->
            <template v-if="channelsList.includes('interna')">
                <el-divider content-position="left">站内通知配置</el-divider>
                <el-form-item label="站内通知模板" prop="interna_template">
                    <el-input v-model="formData.interna_template" type="textarea" rows="3" />
                </el-form-item>
            </template>

            <!-- 邮件配置 (可编辑) -->
            <template v-if="channelsList.includes('email')">
                <el-divider content-position="left">邮件配置</el-divider>
                <el-form-item label="是否开启" prop="email_switch">
                    <el-switch v-model="formData.email_switch" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <el-form-item label="邮件模板" prop="email_template" v-if="formData.email_switch">
                    <el-input v-model="formData.email_template" type="textarea" rows="3" />
                </el-form-item>
            </template>

            <!-- 短信配置 (可编辑) -->
            <template v-if="channelsList.includes('sms')">
                <el-divider content-position="left">短信配置</el-divider>
                <el-form-item label="是否开启" prop="sms_switch">
                    <el-switch v-model="formData.sms_switch" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <template v-if="formData.sms_switch">
                    <el-form-item label="短信模板ID" prop="sms_template_id">
                        <el-input v-model="formData.sms_template_id" placeholder="请输入短信服务商提供的模板ID" />
                    </el-form-item>
                    <el-form-item label="短信模板" prop="sms_template">
                        <el-input v-model="formData.sms_template" type="textarea" rows="3" />
                    </el-form-item>
                </template>
            </template>

            <!-- 微信公众号配置 (可编辑) -->
            <template v-if="channelsList.includes('wechat_official')">
                <el-divider content-position="left">微信公众号配置</el-divider>
                <el-form-item label="是否开启" prop="wechat_official_switch">
                    <el-switch v-model="formData.wechat_official_switch" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <template v-if="formData.wechat_official_switch">
                    <el-form-item label="公众号模板ID" prop="wechat_official_template_id">
                        <el-input v-model="formData.wechat_official_template_id" placeholder="请输入微信公众平台的模板消息ID" />
                    </el-form-item>
                </template>
            </template>

            <!-- 微信小程序配置 (可编辑) -->
            <template v-if="channelsList.includes('wechat_mini')">
                <el-divider content-position="left">微信小程序配置</el-divider>
                <el-form-item label="是否开启" prop="wechat_mini_switch">
                    <el-switch v-model="formData.wechat_mini_switch" :active-value="1" :inactive-value="0" />
                </el-form-item>
                <template v-if="formData.wechat_mini_switch">
                    <el-form-item label="小程序模板ID" prop="wechat_mini_template_id">
                        <el-input v-model="formData.wechat_mini_template_id" placeholder="请输入微信小程序的订阅消息模板ID" />
                    </el-form-item>
                </template>
            </template>
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
import { ref, reactive, computed, watch } from 'vue';
import type { FormInstance } from 'element-plus';
import { ElMessage } from 'element-plus';
import { updateSysNoticeTpl, getSysNoticeTplInfo } from '@/pages-admin/main/api/system/sysNotice';

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle = ref('编辑消息模板');

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    platform: '',
    key: '',
    title: '',
    description: '',
    template_type: 1,
    receiver_type: 1,
    interna_template: '',
    email_switch: 0,
    email_template: '',
    sms_switch: 0,
    sms_template_id: '',
    sms_template: '',
    wechat_official_switch: 0,
    wechat_official_template_id: '',
    wechat_mini_switch: 0,
    wechat_mini_template_id: '',
    supported_channels: '',
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();

// 渠道列表，用于显示支持的渠道
const channelsList = ref<string[]>([]);

// 表单验证规则 - 只验证可编辑字段
const formRules = computed(() => {
    return {
        // 只验证可编辑的字段
        interna_template: [
            {
                required: channelsList.value.includes('interna'),
                message: '请输入站内通知模板内容',
                trigger: 'blur'
            }
        ],
        email_template: [
            {
                required: formData.email_switch === 1 && channelsList.value.includes('email'),
                message: '请输入邮件模板内容',
                trigger: 'blur'
            }
        ],
        sms_template_id: [
            {
                required: formData.sms_switch === 1 && channelsList.value.includes('sms'),
                message: '请输入短信模板ID',
                trigger: 'blur'
            }
        ],
        sms_template: [
            {
                required: formData.sms_switch === 1 && channelsList.value.includes('sms'),
                message: '请输入短信模板内容',
                trigger: 'blur'
            }
        ],
        wechat_official_template_id: [
            {
                required: formData.wechat_official_switch === 1 && channelsList.value.includes('wechat_official'),
                message: '请输入公众号模板ID',
                trigger: 'blur'
            }
        ],
        wechat_mini_template_id: [
            {
                required: formData.wechat_mini_switch === 1 && channelsList.value.includes('wechat_mini'),
                message: '请输入小程序模板ID',
                trigger: 'blur'
            }
        ],
    };
});

const emit = defineEmits(['complete']);

/**
 * 提交表单 - 只提交可编辑字段
 */
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return;

    try {
        // 表单验证
        await formRef.value.validate();

        // 提交数据
        loading.value = true;

        // 只提交需要更新的字段
        const updateData = {
            id: formData.id,
            interna_template: formData.interna_template,
            email_switch: formData.email_switch,
            email_template: formData.email_template,
            sms_switch: formData.sms_switch,
            sms_template_id: formData.sms_template_id,
            sms_template: formData.sms_template,
            wechat_official_switch: formData.wechat_official_switch,
            wechat_official_template_id: formData.wechat_official_template_id,
            wechat_mini_switch: formData.wechat_mini_switch,
            wechat_mini_template_id: formData.wechat_mini_template_id,
        };

        const res = await updateSysNoticeTpl(updateData);

        ElMessage.success('更新消息模板成功');
        dialogVisible.value = false;
        emit('complete');
    } catch (error) {
        console.error('提交失败:', error);
        ElMessage.error('操作失败，请检查表单数据');
    } finally {
        loading.value = false;
    }
};

/**
 * 设置对话框数据
 */
const setDialogData = async (row: any = null) => {
    if (!row || !row.id) {
        ElMessage.warning('请选择要编辑的模板');
        return;
    }

    loading.value = true;
    // 重置表单数据
    Object.assign(formData, initialFormData);
    channelsList.value = [];

    try {
        popTitle.value = '编辑消息模板';

        // 获取模板详细信息
        const { data } = await getSysNoticeTplInfo(row.id);

        // 更新表单数据
        if (data) {
            // 使用类型断言解决索引签名问题
            const dataObj = data as Record<string, any>;
            Object.keys(formData).forEach((key) => {
                if (dataObj[key] !== undefined) {
                    (formData as Record<string, any>)[key] = dataObj[key];
                }
            });

            // 将渠道字符串转换为数组
            if (formData.supported_channels) {
                channelsList.value = formData.supported_channels.split('|');
            }
        }
    } catch (error) {
        console.error('加载数据失败:', error);
        ElMessage.error('加载数据失败');
    } finally {
        loading.value = false;
    }
};

// 暴露方法给父组件
defineExpose({
    openDialog: () => {
        dialogVisible.value = true;
    },
    setDialogData
});
</script>

<style scoped>
.dialog-footer {
    display: flex;
    justify-content: flex-end;
}

.el-divider {
    margin: 16px 0;
}
</style>
