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
                                <div class="item-title">博主ID:</div>
                                <div class="item-content">
                                    {{ bloggerInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">博主昵称:</div>
                                <div class="item-content">
                                    {{ bloggerInfo.blogger_name }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">用户ID:</div>
                                <div class="item-content">
                           
                                        {{ bloggerInfo.user_id }}
                         
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">状态:</div>
                                <div class="item-content">
                                    <el-tag :type="bloggerInfo.is_enabled ? 'success' : 'danger'">
                                        {{ bloggerInfo.is_enabled ? '可用' : '禁用' }}
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
                                            <el-form-item label="博主昵称" prop="blogger_name">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.blogger_name" placeholder="请输入博主昵称" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.blogger_name || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="用户ID" prop="user_id">
                                                <div class="form-text">
                                           
                                                        {{ bloggerInfo.user_id }}
                                              
                                                </div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="头像">
                                                <el-avatar v-if="bloggerInfo.avatar" :src="bloggerInfo.avatar" :size="50" />
                                                <span v-else>未设置</span>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="账户状态" prop="is_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="bloggerInfo.is_enabled ? 'success' : 'danger'">
                                                        {{ bloggerInfo.is_enabled ? '可用' : '禁用' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="描述" prop="description">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.description" type="textarea" :rows="3" placeholder="请输入博主描述" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ bloggerInfo.description || '未设置' }}</div>
                                                </template>
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
                                            <el-form-item label="粉丝数">
                                                <div class="form-text">{{ bloggerInfo.follower_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="关注数">
                                                <div class="form-text">{{ bloggerInfo.following_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="视频数量">
                                                <div class="form-text">{{ bloggerInfo.video_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="短剧数量">
                                                <div class="form-text">{{ bloggerInfo.drama_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="直播次数">
                                                <div class="form-text">{{ bloggerInfo.live_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="总获赞数">
                                                <div class="form-text">{{ bloggerInfo.total_likes || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="总播放量">
                                                <div class="form-text">{{ bloggerInfo.total_views || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="总收藏数">
                                                <div class="form-text">{{ bloggerInfo.total_collect || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    认证信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="认证状态" prop="verification_status">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.verification_status" placeholder="请选择认证状态">
                                                        <el-option label="未认证" :value="0" />
                                                        <el-option label="认证通过" :value="1" />
                                                        <el-option label="认证失败" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="getVerificationStatusType(formData.verification_status)">
                                                        {{ bloggerInfo.verification_status_desc }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="认证类型" prop="verification_type">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.verification_type" placeholder="请选择认证类型">
                                                        <el-option label="个人认证" value="1" />
                                                        <el-option label="企业认证" value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">
                                                        {{ bloggerInfo.verification_type_desc || '未设置' }}
                                                    </div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="认证说明" prop="verification_desc">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.verification_desc" type="textarea" :rows="3" placeholder="请输入认证说明" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ bloggerInfo.verification_desc || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    权限设置
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="直播权限" prop="is_live_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_live_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="bloggerInfo.is_live_enabled ? 'success' : 'danger'">
                                                        {{ bloggerInfo.is_live_enabled ? '已开通' : '未开通' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="短剧权限" prop="is_drama_enabled">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_drama_enabled" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="bloggerInfo.is_drama_enabled ? 'success' : 'danger'">
                                                        {{ bloggerInfo.is_drama_enabled ? '已开通' : '未开通' }}
                                                    </el-tag>
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
                                                <div class="form-text">{{ bloggerInfo.create_at }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="更新时间">
                                                <div class="form-text">{{ bloggerInfo.update_at || '未更新' }}</div>
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
                </el-tabs>
            </div>
        </div>

        <!-- 用户详情弹窗 -->
        <!-- <user-detail ref="detailUserDialog" /> -->
    </el-drawer>
</template>

<script lang="ts" setup name="BloggerDetail">
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { getBloggerInfo, updateBlogger } from '@/pages-admin/main/api/video/blogger'


// 定义博主信息接口
interface BloggerInfo {
    id: number;
    user_id: number;
    blogger_name: string;
    avatar: string | null;
    description: string | null;
    follower_count: number;
    following_count: number;
    video_count: number;
    drama_count: number;
    live_count: number;
    total_likes: number;
    total_views: number;
    total_collect: number;
    verification_status: number;
    verification_status_desc: string; // 添加这个字段
    verification_type: string | null;
    verification_type_desc: string; // 添加这个字段
    verification_desc: string | null;
    is_live_enabled: number;
    is_drama_enabled: number;
    is_enabled: number;
    create_at: string;
    update_at: string;
    is_deleted: number;
    deleted_at: string | null;
}

const tabSelected = ref('base')
const isEditMode = ref(false)
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 使用定义的接口初始化博主信息
const bloggerInfo = reactive<BloggerInfo>({
    id: 0,
    user_id: 0,
    blogger_name: '',
    avatar: null,
    description: null,
    follower_count: 0,
    following_count: 0,
    video_count: 0,
    drama_count: 0,
    live_count: 0,
    total_likes: 0,
    total_views: 0,
    total_collect: 0,
    verification_status: 0,
    verification_status_desc: '',
    verification_type: null,
    verification_type_desc: '',
    verification_desc: null,
    is_live_enabled: 0,
    is_drama_enabled: 0,
    is_enabled: 1,
    create_at: '',
    update_at: '',
    is_deleted: 0,
    deleted_at: null
})

// 初始化表单数据
const initialFormData = {
    id: 0,
    blogger_name: '',
    description: '',
    verification_status: 0,
    verification_type: '',
    verification_desc: '',
    is_live_enabled: 0,
    is_drama_enabled: 0,
    is_enabled: 1
}

const formData = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        blogger_name: [
            { required: true, message: '请输入博主昵称', trigger: 'blur' },
            { min: 2, max: 32, message: '博主昵称长度在 2 到 32 个字符', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateBlogger(bloggerInfo.id, formData).then((res: any) => {
        loading.value = false
        isEditMode.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}

const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '博主详情'

    if (row) {
        const data = await (await getBloggerInfo(row.id)).data
        Object.assign(bloggerInfo, data)
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) (formData as any)[key] = data[key]
        })
    }
    loading.value = false
}


// 认证状态相关方法
const getVerificationStatusType = (status: number) => {
    const typeMap: Record<number, string> = {
        0: 'info',
        1: 'success',
        2: 'danger'
    }
    return typeMap[status] || 'info'
}


defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script> 