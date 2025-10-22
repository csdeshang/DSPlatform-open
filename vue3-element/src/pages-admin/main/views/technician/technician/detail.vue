<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">
        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-avatar :size="80" :src="technicianInfo.avatar" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ technicianInfo.name }}
                            </div>
                        </div>
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
                                <div class="item-title">师傅ID：</div>
                                <div class="item-content">
                                    {{ technicianInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">师傅名称：</div>
                                <div class="item-content">
                                    {{ technicianInfo.name }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">平均评分：</div>
                                <div class="item-content">
                                    {{ technicianInfo.avg_score }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">访问次数：</div>
                                <div class="item-content">
                                    {{ technicianInfo.visit_count }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">服务次数：</div>
                                <div class="item-content">
                                    {{ technicianInfo.service_count }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">工作年限：</div>
                                <div class="item-content">
                                    {{ technicianInfo.work_years }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">账户余额：</div>
                                <div class="item-content">
                                    {{ technicianInfo.balance }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">创建时间：</div>
                                <div class="item-content">
                                    {{ technicianInfo.create_at }}
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
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
                                            <el-form-item label="师傅名称" prop="name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.name" placeholder="请输入师傅名称" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="手机号码" prop="mobile">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.mobile" placeholder="请输入手机号码" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.mobile || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="性别" prop="gender">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.gender" placeholder="请选择性别">
                                                        <el-option label="未知" :value="0" />
                                                        <el-option label="男" :value="1" />
                                                        <el-option label="女" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">
                                                        {{ technicianInfo.gender === 1 ? '男' : technicianInfo.gender === 2 ? '女' : '未知' }}
                                                    </div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="工作年限" prop="work_years">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.work_years" :min="0" :max="50" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.work_years }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="用户ID" prop="user_id">
                                                <div class="form-text">{{ technicianInfo.user_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="商户ID" prop="merchant_id">
                                                <div class="form-text">{{ technicianInfo.merchant_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="店铺ID" prop="store_id">
                                                <div class="form-text">{{ technicianInfo.store_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="师傅状态" prop="technician_status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.technician_status" placeholder="请选择师傅状态">
                                                        <el-option label="休息" :value="0" />
                                                        <el-option label="工作中" :value="1" />
                                                        <el-option label="忙碌" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.technician_status_desc }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    状态信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="是否启用" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="technicianInfo.is_enabled === 1 ? 'success' : 'danger'">
                                                        {{ technicianInfo.is_enabled === 1 ? '启用' : '禁用' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="平均评分" prop="avg_score">
                                                <div class="form-text">{{ technicianInfo.avg_score }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="访问次数" prop="visit_count">
                                                <div class="form-text">{{ technicianInfo.visit_count }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="服务次数" prop="service_count">
                                                <div class="form-text">{{ technicianInfo.service_count }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="评论次数" prop="comment_count">
                                                <div class="form-text">{{ technicianInfo.comment_count }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="服务费率" prop="technician_fee_rate">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.technician_fee_rate" :min="0" :max="100" :precision="2" :step="0.1" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.technician_fee_rate || '0.00' }}</div>
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
                                                <div class="form-text">{{ technicianInfo.balance }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="收入金额">
                                                <div class="form-text">{{ technicianInfo.balance_in }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="支出金额">
                                                <div class="form-text">{{ technicianInfo.balance_out }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    申请信息
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
                                                    <el-tag :type="getApplyStatusType(technicianInfo.apply_status)">
                                                        {{ technicianInfo.apply_status_desc }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="申请时间" prop="apply_time">
                                                <div class="form-text">{{ technicianInfo.apply_time || '未申请' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="审核时间">
                                                <div class="form-text">{{ technicianInfo.audit_time || '未审核' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="资质信息" prop="certificate_info">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.certificate_info" type="textarea" :rows="2" placeholder="请输入资质信息" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.certificate_info || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="申请备注" prop="apply_remark">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.apply_remark" type="textarea" :rows="2" placeholder="请输入申请备注" />
                                                </template>
                                                <template v-else>
                                                <div class="form-text">{{ technicianInfo.apply_remark || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="审核备注" prop="audit_remark">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.audit_remark" type="textarea" :rows="2" placeholder="请输入审核备注" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.audit_remark || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    详细描述
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="24">
                                            <el-form-item label="详细描述" prop="description">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.description" type="textarea" :rows="3" placeholder="请输入详细描述" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ technicianInfo.description || '无' }}</div>
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
                                                <div class="form-text">{{ technicianInfo.create_at }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="更新时间">
                                                <div class="form-text">{{ technicianInfo.update_at }}</div>
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

                    <el-tab-pane label="师傅资金明细" name="balance">
                        <detail-balance-log :technician_id="technicianInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="服务订单" name="order-delivery">
                        <detail-order-delivery :technician_id="technicianInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="评价记录" name="comment">
                        <DetailTechnicianComment :technician_id="technicianInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="轨迹记录" name="track">
                        <DetailTechnicianTrack :technician_id="technicianInfo.id" />
                    </el-tab-pane>
                </el-tabs>
            </div>
    </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus'
import { computed, reactive, ref } from 'vue'
import { getTechnicianInfo, updateTechnician } from '@/pages-admin/main/api/technician/technician'
import { ElMessage } from 'element-plus'

import DetailBalanceLog from './detail-balance-log.vue'
import DetailOrderDelivery from './detail-order-delivery.vue'
import DetailTechnicianComment from './detail-technician-comment.vue'
import DetailTechnicianTrack from './detail-technician-track.vue'

// 组件名称
defineOptions({
  name: 'TechnicianDetailDialog'
})

// 定义师傅信息接口
interface TechnicianInfo {
    id: number
    user_id: number
    merchant_id: number
    store_id: number
    name: string
    mobile: string
    avatar: string | null
    slide_image: string | null
    gender: number
    certificate_info: string
    work_years: number
    description: string
    visit_count: number
    comment_count: number
    avg_score: string
    service_count: number
    technician_status: number
    technician_status_desc: string
    technician_fee_rate: number
    technician_latitude: number | null
    technician_longitude: number | null
    technician_loc_time: string | null
    balance: number
    balance_in: number
    balance_out: number
    is_enabled: number
    create_at: string
    update_at: string
    apply_time: string
    apply_status: number
    apply_status_desc: string
    apply_remark: string
    audit_time: string | null
    audit_remark: string | null
    is_deleted: number
    deleted_at: string | null
    gender_desc: string
}

const tabSelected = ref('base')
const isEditMode = ref(false)
const dialogVisible = ref(false)
const loading = ref(false)
const popTitle = ref('师傅详情')
const formRef = ref<FormInstance>()

// 师傅详情信息（用于展示）
const technicianInfo = reactive<TechnicianInfo>({
    id: 0,
    user_id: 0,
    merchant_id: 0,
    store_id: 0,
    name: '',
    mobile: '',
    avatar: null,
    slide_image: null,
    gender: 0,
    certificate_info: '',
    work_years: 0,
    description: '',
    visit_count: 0,
    comment_count: 0,
    avg_score: '0.00',
    service_count: 0,
    technician_status: 0,
    technician_status_desc: '',
    technician_fee_rate: 0,
    technician_latitude: null,
    technician_longitude: null,
    technician_loc_time: null,
    balance: 0,
    balance_in: 0,
    balance_out: 0,
    is_enabled: 0,
    create_at: '',
    update_at: '',
    apply_time: '',
    apply_status: 0,
    apply_status_desc: '',
    apply_remark: '',
    audit_time: null,
    audit_remark: null,
    is_deleted: 0,
    deleted_at: null,
    gender_desc: ''
})

// 表单数据（用于编辑和提交）
const initialFormData = {
    id: 0,
    name: '',
    mobile: '',
    gender: 0,
    technician_status: 0,
    technician_fee_rate: 0,
    certificate_info: '',
    work_years: 0,
    description: '',
    is_enabled: 0,
    apply_status: 0,
    apply_remark: '',
    audit_remark: ''
}

const formData = reactive({ ...initialFormData })

// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入师傅名称', trigger: 'blur' },
            { min: 2, max: 50, message: '长度在2到50个字符之间', trigger: 'blur' }
        ],
        mobile: [
            { required: true, message: '请输入手机号码', trigger: 'blur' },
            { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码', trigger: 'blur' }
        ]
    }
})


// 获取申请状态标签类型
const getApplyStatusType = (status: number) => {
    const typeMap: Record<number, string> = {
        0: 'warning',
        1: 'success',
        2: 'danger'
    }
    return typeMap[status] || 'info'
}

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return

    try {
    await formRef.value.validate()
    loading.value = true

        const res = await updateTechnician(formData)
        if (res.code === 10000) {
            ElMessage.success('保存成功')
            isEditMode.value = false
            // 重新获取数据，更新展示信息
            getDetail(formData.id)
            emit('complete')
        }
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

// 打开弹窗
const openDialog = () => {
    dialogVisible.value = true
}

// 获取详情
const getDetail = async (id: number) => {
    loading.value = true
    try {
        const res = await getTechnicianInfo(id)
        if (res.code === 10000) {
            // 更新展示数据
            Object.assign(technicianInfo, res.data)
            // 更新表单数据
            Object.assign(formData, initialFormData)  // 先重置
            Object.keys(formData).forEach((key: string) => {
                if (res.data[key] !== undefined && key in formData) {
                    (formData as any)[key] = res.data[key]
                }
            })

            popTitle.value = `师傅详情 - ${technicianInfo.name}`
        } else {
            ElMessage.error(res.message || '获取详情失败')
        }
    } catch (error) {
        console.error(error)
        ElMessage.error('获取详情失败')
    } finally {
        loading.value = false
    }
}

const setDialogData = async (row: any = null) => {
    if (row?.id) {
        await getDetail(row.id)
    }
}

defineExpose({
    openDialog,
    setDialogData
})
</script>