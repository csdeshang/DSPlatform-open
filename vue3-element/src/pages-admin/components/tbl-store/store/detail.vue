<template>
    <!-- 店铺详情 显示和修改 TblStore 表中的数据  如果没有拓展表则使用默认的-->



    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">



        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-avatar :size="80" :src="formatFileUrl(storeInfo.store_logo)" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ storeInfo.store_name }}
                            </div>
                        </div>
                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary" @click="isEditMode = !isEditMode">
                            {{ isEditMode ? '取消编辑' : '编辑' }}
                        </el-button>
                        <el-button type="primary" @click="handleAddAuthUser">添加授权管理用户</el-button>
                    </div>
                </div>
                <div class="section-hd-center">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">店铺ID</div>
                                <div class="item-content">
                                    {{ storeInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">店铺名称：</div>
                                <div class="item-content">
                                    {{ storeInfo.store_name }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">创建时间：</div>
                                <div class="item-content">
                                    {{ storeInfo.create_at }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">更新时间：</div>
                                <div class="item-content">
                                    {{ storeInfo.update_at }}
                                </div>
                            </div>
                        </el-col>


                    </el-row>




                </div>
                <div class="section-hd-bottom">

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
                                            <el-form-item label="店铺名称" prop="store_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.store_name"
                                                        placeholder="请输入店铺名称" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.store_name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="商户ID" prop="merchant_id">
                                                <div class="form-text">{{ storeInfo.merchant_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="平台" prop="platform">
                                                <div class="form-text">{{ storeInfo.platform }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="店铺状态" prop="store_business_status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.store_business_status" placeholder="请选择店铺状态">
                                                        <el-option label="营业中" :value="1" />
                                                        <el-option label="休息中" :value="0" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="storeInfo.store_business_status ? 'success' : 'info'">
                                                        {{ storeInfo.store_business_status ? '营业中' : '休息中' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    店铺设置
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="是否启用" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="storeInfo.is_enabled ? 'success' : 'danger'">
                                                        {{ storeInfo.is_enabled ? '已启用' : '未启用' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="推荐状态" prop="is_recommend">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_recommend" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="storeInfo.is_recommend ? 'warning' : 'info'">
                                                        {{ storeInfo.is_recommend ? '推荐店铺' : '普通店铺' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="服务费率" prop="service_fee_rate">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.service_fee_rate" :min="0" :max="100" :precision="2" :step="0.1" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.service_fee_rate }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="排序值" prop="sort">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.sort" :min="0" :max="999" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.sort }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    店铺介绍
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="24">
                                            <el-form-item label="店铺介绍" prop="store_introduction">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.store_introduction" type="textarea" rows="3" placeholder="请输入店铺介绍" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.store_introduction || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    联系信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="联系人" prop="contact_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.contact_name" placeholder="请输入联系人" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.contact_name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="联系电话" prop="contact_phone">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.contact_phone" placeholder="请输入联系电话" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.contact_phone || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="24">
                                            <el-form-item label="店铺地址" prop="address">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.address" placeholder="请输入店铺地址" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.address || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    SEO信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="SEO标题" prop="seo_title">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.seo_title" placeholder="请输入SEO标题" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.seo_title || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="SEO关键词" prop="seo_keywords">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.seo_keywords" placeholder="请输入SEO关键词" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.seo_keywords || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="24">
                                            <el-form-item label="SEO描述" prop="seo_description">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.seo_description" type="textarea" rows="3" placeholder="请输入SEO描述" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.seo_description || '未设置' }}</div>
                                                </template>
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
                                    <el-row :gutter="24">
                                        <el-col :span="12">
                                            <el-form-item label="申请状态">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.apply_status" placeholder="请选择申请状态">
                                                        <el-option label="待审核" :value="0" />
                                                        <el-option label="已通过" :value="1" />
                                                        <el-option label="已拒绝" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.apply_status_desc }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="24">
                                            <el-form-item label="申请备注">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.apply_remark" placeholder="请输入申请备注" type="textarea" rows="3" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.apply_remark || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24">
                                            <el-form-item label="审核备注">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.audit_remark" placeholder="请输入审核备注" type="textarea" rows="3" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ storeInfo.audit_remark || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24">
                                            <el-form-item label="审核时间">
                                                <div class="form-text">{{ storeInfo.audit_time || '未设置' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        

                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    统计信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <el-form-item label="描述评分">
                                                <div class="form-text">{{ storeInfo.avg_describe_score }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="8">
                                            <el-form-item label="物流评分">
                                                <div class="form-text">{{ storeInfo.avg_logistics_score }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="8">
                                            <el-form-item label="服务评分">
                                                <div class="form-text">{{ storeInfo.avg_service_score }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="销售数量">
                                                <div class="form-text">{{ storeInfo.sales_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="收藏数量">
                                                <div class="form-text">{{ storeInfo.collect_num }}</div>
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

                    <el-tab-pane label="授权用户" name="user">
                        <!-- 授权管理店铺的用户  TblStore 表中的数据 -->
                        <detail-user-list ref="detailUserListRef" :store_id="storeInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="出售订单" name="order">
                        <detail-order-list ref="detailOrderListRef" :store_id="storeInfo.id" />
                    </el-tab-pane>

                </el-tabs>

            </div>

        </div>

        <!-- 添加授权管理用户 -->
        <add-auth-user ref="addAuthUserDialog" @complete="refreshAuthUserList()" />
       




    </el-drawer>




</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { getTblStoreInfo, updateTblStore } from '@/pages-admin/main/api/tbl-store/tblStore'

import AddAuthUser from './add-auth-user.vue'

import DetailUserList from './detail-user-list.vue'
import DetailOrderList from './detail-order-list.vue'

import { formatFileUrl } from '@/utils/util'

const tabSelected = ref('base')

const isEditMode = ref(false)
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const storeInfo = reactive({
    id: 0,
    store_name: '',
    store_logo: '',
    merchant_id: 0,
    platform: '',
    store_business_status: 1,
    store_introduction: '',
    service_fee_rate: '0.00',
    category_id: 0,
    area_id: 0,
    area_info: '',
    address: '',
    store_latitude: null,
    store_longitude: null,
    contact_name: '',
    contact_phone: '',
    business_license: null,
    seo_title: '',
    seo_keywords: '',
    seo_description: '',
    avg_describe_score: '0.00',
    avg_logistics_score: '0.00',
    avg_service_score: '0.00',
    sales_num: 0,
    collect_num: 0,
    is_recommend: 0,
    sort: 0,
    is_enabled: 1,
    apply_status: 0,
    apply_status_desc: '',
    apply_remark: '',
    audit_time: '',
    audit_remark: '',
    create_at: '',
    update_at: '',
    is_deleted: 0,
    deleted_at: null
})

// 初始化表单数据
const initialFormData = {
    id: '',
    store_name: '',
    store_business_status: 1,
    service_fee_rate: '0.00',
    is_enabled: 1,
    is_recommend: 0,
    sort: 0,
    store_introduction: '',
    contact_name: '',
    contact_phone: '',
    address: '',
    seo_title: '',
    seo_keywords: '',
    seo_description: '',

    // 申请状态
    apply_status: 0,
    apply_status_desc: '',
    apply_remark: '',
    audit_remark: '',
    audit_time: '',

    
}
const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()


// 表单验证规则
const formRules = computed(() => {
    return {
        store_name: [
            { required: true, message: '请输入店铺名称', trigger: 'blur' }
        ],
        contact_phone: [
            { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateTblStore(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}


// 添加授权管理用户
const addAuthUserDialog = ref()
const handleAddAuthUser = () => {
    addAuthUserDialog.value?.openDialog()
    addAuthUserDialog.value?.setDialogData(storeInfo)
}

// 刷新授权用户列表
const detailUserListRef = ref()
const refreshAuthUserList = () => {
    detailUserListRef.value?.fetchTblStoreAuthUserList()
}


const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '店铺详情'

    if (row) {
        const data = await (await getTblStoreInfo(row.id)).data
        Object.assign(storeInfo, data)
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
