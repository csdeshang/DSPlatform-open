<template>

    <el-drawer v-model="dialogVisible" :title="popTitle" size="75%">



        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-avatar :size="80" :src="formatImageUrl(formData.avatar, ThumbnailPresets.small, 'avatar')" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ merchantInfo.name }}
                            </div>
                        </div>
                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary" @click="isEditMode = !isEditMode">
                            {{ isEditMode ? '取消编辑' : '编辑' }}
                        </el-button>
                        <el-button type="primary" @click="handleAddStore">添加店铺</el-button>
                    </div>
                </div>
                <div class="section-hd-center">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">商户ID：</div>
                                <div class="item-content">
                                    {{ merchantInfo.id }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">商户名：</div>
                                <div class="item-content">
                                    {{ merchantInfo.name }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">可用金额：</div>
                                <div class="item-content">
                                    {{ merchantInfo.balance }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">收入总额：</div>
                                <div class="item-content">
                                    {{ merchantInfo.balance_in }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">支出总额：</div>
                                <div class="item-content">
                                    {{ merchantInfo.balance_out }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">创建时间：</div>
                                <div class="item-content">
                                    {{ merchantInfo.create_at }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">更新时间：</div>
                                <div class="item-content">
                                    {{ merchantInfo.update_at }}
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
                    <el-tab-pane label="基本信息" name="merchant">
                        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="120px" class="mt-6">
                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    基本信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="商户名" prop="name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.name" placeholder="请输入商户名" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.name }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="联系人" prop="contact_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.contact_name" placeholder="请输入联系人" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.contact_name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="联系电话" prop="contact_phone">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.contact_phone" placeholder="请输入联系电话" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.contact_phone || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="联系地址" prop="contact_address">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.contact_address"
                                                        placeholder="请输入联系地址" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.contact_address || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="是否启用" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1"
                                                        :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="merchantInfo.is_enabled ? 'success' : 'danger'">
                                                        {{ merchantInfo.is_enabled ? '已启用' : '未启用' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="商户直接收款" prop="is_allow_payment">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_allow_payment" :active-value="1"
                                                        :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="merchantInfo.is_allow_payment ? 'success' : 'info'">
                                                        {{ merchantInfo.is_allow_payment ? '允许' : '不允许' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="允许店铺数量" prop="allowed_store_count">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.allowed_store_count" :min="1"
                                                        :max="100" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.allowed_store_count }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="排序值" prop="sort">
                                                <template v-if="isEditMode">
                                                    <el-input-number v-model="formData.sort" :min="0" :max="999" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.sort }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="可用金额" prop="balance">
                                                <div class="form-text">{{ merchantInfo.balance }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="收入总额" prop="balance_in">
                                                <div class="form-text">{{ merchantInfo.balance_in }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="支出总额" prop="balance_out">
                                                <div class="form-text">{{ merchantInfo.balance_out }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="创建时间" prop="create_at">
                                                <div class="form-text">{{ merchantInfo.create_at }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="更新时间" prop="update_at">
                                                <div class="form-text">{{ merchantInfo.update_at }}</div>
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
                                            <el-form-item label="审核状态" prop="apply_status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.apply_status" placeholder="请选择审核状态">
                                                        <el-option label="待审核" :value="0"></el-option>
                                                        <el-option label="已通过" :value="1"></el-option>
                                                        <el-option label="已拒绝" :value="2"></el-option>
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.apply_status_desc }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        
                                        <el-col :span="12">
                                            <el-form-item label="申请时间" prop="apply_time">
                                                <div class="form-text">{{ merchantInfo.apply_time }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24">
                                            <el-form-item label="申请备注" prop="apply_remark">
                                                <div class="form-text">{{ merchantInfo.apply_remark || '无' }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="24" v-if="formData.apply_status == 2">
                                            <el-form-item label="审核备注" prop="audit_remark">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.audit_remark" placeholder="请输入审核备注" type="textarea" :rows="4" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ merchantInfo.audit_remark || '无' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12" v-if="formData.apply_status == 2">
                                            <el-form-item label="审核时间" prop="audit_time">
                                                <div class="form-text">{{ merchantInfo.audit_time || '无' }}</div>
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

                    <el-tab-pane label="拥有店铺" name="store">
                        <!-- 商户拥有的店铺列表  TblStore 表中的数据 -->
                        <detail-store-list ref="storeDetailListRef" :merchant_id="merchantInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="商户流水" name="balance">
                        <!-- 余额变动记录列表  TblMerchantBalanceLog 表中的数据 -->
                        <detail-balance-log ref="balanceDetailListRef" :merchant_id="merchantInfo.id" />
                    </el-tab-pane>

                </el-tabs>
            </div>

        </div>

        <tbl-store-add ref="createStoreDialog" @complete="refreshStoreDetailList()" />


    </el-drawer>



</template>



<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { getMerchantInfo, updateMerchant } from '@/pages-admin/main/api/merchant/merchant'
import { formatImageUrl, ThumbnailPresets } from '@/utils/image'
import DetailStoreList from './detail-store-list.vue'
import TblStoreAdd from '@/pages-admin/components/tbl-store/store/add.vue'
import DetailBalanceLog from './detail-balance-log.vue'
import { ElMessage } from 'element-plus';

const tabSelected = ref('merchant')



const dialogVisible = ref(false)
const loading = ref(false)
const isEditMode = ref(false) // 控制编辑模式
let popTitle: string = ''

const merchantInfo = reactive({})

// 初始化表单数据 - 更新包含所有可编辑字段
const initialFormData = {
    id: '',
    name: '',
    contact_name: '',
    contact_phone: '',
    contact_address: '',
    is_enabled: 0,
    is_allow_payment: 0,
    allowed_store_count: 1,
    sort: 255,
    apply_status: 0,
    audit_remark: ''
}
const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()


// 表单验证规则
const formRules = computed(() => {
    return {
        name: [
            { required: true, message: '请输入商户名称', trigger: 'blur' },
            { min: 2, max: 50, message: '长度在2到50个字符之间', trigger: 'blur' }
        ],
        contact_phone: [
            { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码', trigger: 'blur' }
        ],
        allowed_store_count: [
            { required: true, message: '请设置允许的店铺数量', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])


const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateMerchant(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

// 添加店铺
const createStoreDialog = ref()
const handleAddStore = () => {
    createStoreDialog.value.setDialogData(merchantInfo)
    createStoreDialog.value?.openDialog()
}
// 刷新店铺列表
const storeDetailListRef = ref()
const refreshStoreDetailList = () => {
    storeDetailListRef.value.fetchTblStoreList(); // 调用 tbl-store-detail-list 的刷新方法
};

const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '商户详情'

    if (row) {
        const data = await (await getMerchantInfo(row.id)).data
        Object.assign(merchantInfo, data)
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

<style scoped lang="scss"></style>
