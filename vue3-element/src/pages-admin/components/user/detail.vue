<template>
    <el-drawer v-model="dialogVisible" :title="popTitle" size="75%">
        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-avatar :size="80" :src="formatFileUrl(userInfo.avatar)" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ userInfo.username }}
                            </div>
                        </div>
                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary" @click="isEditMode = !isEditMode">
                            {{ isEditMode ? '取消编辑' : '编辑' }}
                        </el-button>
                    </div>
                </div>
                <div class="section-hd-center">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">用户名：</div>
                                <div class="item-content">
                                    {{ userInfo.username }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">余额：</div>
                                <div class="item-content">
                                    ￥{{ userInfo.balance }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">总收入：</div>
                                <div class="item-content">
                                    ￥{{ userInfo.balance_in }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">总支出：</div>
                                <div class="item-content">
                                    ￥{{ userInfo.balance_out }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">推荐人ID：</div>
                                <div class="item-content">
                                    {{ userInfo.inviter_id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">登录次数：</div>
                                <div class="item-content">
                                    {{ userInfo.login_num }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">最后登录：</div>
                                <div class="item-content">
                                    {{ userInfo.login_time }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">登录IP：</div>
                                <div class="item-content">
                                    {{ userInfo.login_ip }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">积分：</div>
                                <div class="item-content">
                                    {{ userInfo.points }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">注册时间：</div>
                                <div class="item-content">
                                    {{ userInfo.create_at }}
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
                    <el-tab-pane label="基本信息" name="user">
                        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="120px" class="mt-6">
                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    基本信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="用户名" prop="username">
                                                <div class="form-text">{{ userInfo.username }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="昵称" prop="nickname">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.nickname" placeholder="请输入昵称" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.nickname || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="性别" prop="sex">
                                                <template v-if="isEditMode">
                                                    <el-radio-group v-model="formData.sex">
                                                        <el-radio :value="0">未知</el-radio>
                                                        <el-radio :value="1">男</el-radio>
                                                        <el-radio :value="2">女</el-radio>
                                                    </el-radio-group>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.sex == 0 ? '未知' : userInfo.sex == 1 ? '男' : userInfo.sex == 2 ? '女' : '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="QQ" prop="qq">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.qq" placeholder="请输入QQ号" type="number" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.qq || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="生日" prop="birthday">
                                                <template v-if="isEditMode">
                                                    <el-date-picker v-model="formData.birthday" type="date"
                                                        placeholder="选择日期" value-format="YYYY-MM-DD" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.birthday || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    账户安全
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="登录密码" prop="password">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.password" type="password" placeholder="请输入登录密码" show-password />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">******</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="支付密码" prop="pay_password">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.pay_password" type="password" placeholder="请输入支付密码" show-password />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">******</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="手机绑定" prop="mobile_bind">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.mobile_bind" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.mobile_bind ? '已绑定' : '未绑定' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="手机号" prop="mobile">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.mobile" placeholder="请输入手机号" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.mobile || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="邮箱绑定" prop="email_bind">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.email_bind" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.email_bind ? '已绑定' : '未绑定' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="邮箱" prop="email">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.email" placeholder="请输入邮箱" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.email || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="是否启用" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.is_enabled ? '已启用' : '未启用' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>
                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    联系方式
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="真实姓名" prop="idcard_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.idcard_name" placeholder="请输入真实姓名" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ userInfo.idcard_name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block" v-if="userInfo.is_distributor">
                                <div class="section-bd-block-title">
                                    分销商
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="分销商状态" prop="distributor_status">
                                                <div class="form-text">{{ userInfo.distributor_status == 1 ? '正常' : '禁用' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="分销商等级" prop="distributor_level_id">
                                                <div class="form-text">{{ userInfo.distributor_level_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="佣金余额" prop="distributor_balance">
                                                <div class="form-text">{{ userInfo.distributor_balance }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="佣金总收入" prop="distributor_balance_in">
                                                <div class="form-text">{{ userInfo.distributor_balance_in }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="佣金总支出" prop="distributor_balance_out">
                                                <div class="form-text">{{ userInfo.distributor_balance_out }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="加入时间" prop="distributor_addtime">
                                                <div class="form-text">{{ userInfo.distributor_addtime }}</div>
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
                    
                    <el-tab-pane label="授权管理店铺" name="store">
                        <detail-store-list :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="充值记录" name="recharge">
                        <detail-recharge-log :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="余额记录" name="balance">
                        <detail-balance-log :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="购买订单" name="pay">
                        <detail-order-list :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="支付记录" name="orderlist">
                        <detail-pay-log :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="积分明细" name="point">
                        <detail-points-log :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="成长值记录" name="growth">
                        <detail-growth-log :user_id="userInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="第三方账号" name="identity">
                        <detail-identity :user_id="userInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="分销订单" name="distributor_order">
                        <detail-distributor-order-list :distributor_user_id="userInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="分销佣金记录" name="distributor_balance">
                        <detail-distributor-balance-log :distributor_user_id="userInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="推广下级" name="relation">
                        <detail-relation :user_id="userInfo.id" />
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { formatFileUrl } from '@/utils/util'

import { getUserInfo, updateUser } from '@/pages-admin/main/api/user/user'
import DetailStoreList from './detail-store-list.vue'
import DetailPayLog from './detail-pay-log.vue'
import DetailBalanceLog from './detail-balance-log.vue'
import DetailOrderList from './detail-order-list.vue'
import DetailRechargeLog from './detail-recharge-log.vue'
import DetailPointsLog from './detail-points-log.vue'
import DetailGrowthLog from './detail-growth-log.vue'
import DetailIdentity from './detail-identity.vue'
import DetailDistributorOrderList from './detail-distributor-order.vue'
import DetailDistributorBalanceLog from './detail-distributor-balance.vue'
import DetailRelation from './detail-relation.vue'

const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const isEditMode = ref(false)
const tabSelected = ref('user')

const userInfo = reactive({
    id: 0,
    username: '',
    avatar: '',
    balance: 0,
    balance_in: 0,
    balance_out: 0,
    inviter_id: 0,
    login_num: 0,
    login_time: '',
    login_ip: '',
    points: 0,
    create_at: '',
    nickname: '',
    sex: 0,
    birthday: '',
    password: '',
    pay_password: '',
    email: '',
    email_bind: 0,
    mobile: '',
    mobile_bind: 0,
    qq: '',
    idcard_name: '',
    is_enabled: 1,
    is_distributor: 0,
    distributor_status: 0,
    distributor_level_id: 0,
    distributor_balance: 0,
    distributor_balance_in: 0,
    distributor_balance_out: 0,
    distributor_addtime: ''
})

const initialFormData = {
    id: '',
    nickname: '',
    sex: '',
    birthday: '',
    password: '',
    pay_password: '',
    email: '',
    email_bind: '',
    mobile: '',
    mobile_bind: '',
    qq: '',
    idcard_name: '',
    is_enabled: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        nickname: [
            {
                required: true,
                message: '请输入昵称',
                trigger: 'blur'
            }
        ],
        birthday: [
            {
                required: true,
                message: '请选择生日',
                trigger: 'change'
            }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateUser(formData).then(res => {
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
    popTitle = '会员详情'
    if (row.id) {
        const data = await (await getUserInfo(row.id)).data
        Object.assign(userInfo, data)
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
