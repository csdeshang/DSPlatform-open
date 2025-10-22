<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">
        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">

                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary" @click="isEditMode = !isEditMode">
                            {{ isEditMode ? '取消编辑' : '编辑' }}
                        </el-button>
                    </div>
                </div>
                <div class="section-hd-center style-1">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">骑手ID:</div>
                                <div class="item-content">
                                    {{ riderInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">骑手名称:</div>
                                <div class="item-content">
                                    {{ riderInfo.name }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">手机号:</div>
                                <div class="item-content">
                                    {{ riderInfo.mobile || '未设置' }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">状态:</div>
                                <div class="item-content">
                                    <el-tag :type="riderInfo.is_enabled ? 'success' : 'danger'">
                                        {{ riderInfo.is_enabled ? '启用' : '禁用' }}
                                    </el-tag>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
                <div class="section-hd-bottom"></div>
            </div>

            <div class="section-bd">
                <el-tabs v-model="tabSelected">
                    <el-tab-pane label="基本信息" name="base">
                        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="120px" class="mt-6">
                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    基本信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="骑手名称" prop="name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.name" placeholder="请输入骑手名称" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="手机号" prop="mobile">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.mobile" placeholder="请输入手机号" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.mobile || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="用户ID" prop="user_id">
                                                <div class="form-text">{{ riderInfo.user_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="状态" prop="status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.status" placeholder="请选择状态">
                                                        <el-option label="离线" value="0" />
                                                        <el-option label="在线" value="1" />
                                                        <el-option label="忙碌" value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.status === '0' ? 'info' : (formData.status === '1' ? 'success' : 'warning')">
                                                        {{ formData.status === '0' ? '离线' : (formData.status === '1' ? '在线' : '忙碌') }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    账户信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="账户余额">
                                                <div class="form-text">{{ riderInfo.balance }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="收入金额">
                                                <div class="form-text">{{ riderInfo.balance_in }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="支出金额">
                                                <div class="form-text">{{ riderInfo.balance_out }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="是否启用" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.is_enabled ? 'success' : 'danger'">
                                                        {{ formData.is_enabled ? '已启用' : '已禁用' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    审核信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="申请状态" prop="apply_status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.apply_status" placeholder="请选择申请状态">
                                                        <el-option label="待审核" :value="0" />
                                                        <el-option label="已通过" :value="1" />
                                                        <el-option label="已拒绝" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.apply_status === 0 ? 'warning' : (formData.apply_status === 1 ? 'success' : 'danger')">
                                                        {{ formData.apply_status === 0 ? '待审核' : (formData.apply_status === 1 ? '已通过' : '已拒绝') }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="审核时间">
                                                <div class="form-text">{{ riderInfo.audit_time }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="申请备注">
                                                <div class="form-text">{{ riderInfo.apply_remark || '无' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="审核备注" prop="audit_remark">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.audit_remark" type="textarea" :rows="3" placeholder="请输入审核备注" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.audit_remark || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                       
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    时间信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="创建时间">
                                                <div class="form-text">{{ riderInfo.create_at }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="更新时间">
                                                <div class="form-text">{{ riderInfo.update_at }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <!-- 只在编辑模式下显示按钮 -->
                            <div v-if="isEditMode" class="section-bd-block-footer">
                                <el-button type="primary" @click="handleSubmit" :loading="loading">保存</el-button>
                                <el-button @click="isEditMode = false">取消</el-button>
                            </div>
                        </el-form>
                    </el-tab-pane>

                    <el-tab-pane label="骑手关联的店铺" name="user">
                        <detail-store-list ref="detailStoreListRef" :rider_id="riderInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="骑手资金明细" name="order">
                        <detail-balance-log ref="detailBalanceLogRef" :rider_id="riderInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="轨迹记录" name="track">
                        <DetailRiderTrack :rider_id="riderInfo.id" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { getRiderInfo, updateRider } from '@/pages-admin/main/api/rider/rider'

import DetailStoreList from './detail-store-list.vue'
import DetailBalanceLog from './detail-balance-log.vue'
import DetailRiderTrack from './detail-rider-track.vue'

// 定义骑手信息接口
interface RiderInfo {
    id: number;
    user_id: number;
    name: string;
    mobile: string;
    status: string;
    balance: number;
    balance_in: number;
    balance_out: number;
    is_enabled: number;
    create_at: string;
    update_at: string;
    apply_status: number;
    apply_remark: string;
    audit_time: string;
    audit_remark: string;
}

const tabSelected = ref('base')
const isEditMode = ref(false)
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 使用定义的接口初始化骑手信息
const riderInfo = reactive<RiderInfo>({
    id: 0,
    user_id: 0,
    name: '',
    mobile: '',
    status: '0',
    balance: 0,
    balance_in: 0,
    balance_out: 0,
    is_enabled: 1,
    create_at: '',
    update_at: '',
    apply_status: 0,
    apply_remark: '',
    audit_time: '',
    audit_remark: ''
})

// 初始化表单数据
const initialFormData = {
    id: 0,
    name: '',
    mobile: '',
    status: '0',
    is_enabled: 1,
    apply_status: 0,
    audit_remark: ''
}

const formData = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入骑手名称', trigger: 'blur' }
        ],
        mobile: [
            { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateRider(formData).then(res => {
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
    popTitle = '骑手详情'

    if (row) {
        const data = await (await getRiderInfo(row.id)).data
        Object.assign(riderInfo, data)
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
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
