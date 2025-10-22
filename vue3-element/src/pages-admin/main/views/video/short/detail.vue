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
                                <div class="item-title">视频ID:</div>
                                <div class="item-content">
                                    {{ videoInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">标题:</div>
                                <div class="item-content">
                                    {{ videoInfo.title }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">博主ID:</div>
                                <div class="item-content">
                                    {{ videoInfo.blogger_id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">审核状态:</div>
                                <div class="item-content">
                                    <el-tag :type="getAuditStatusType(videoInfo.audit_status)">
                                        {{ getAuditStatusText(videoInfo.audit_status) }}
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
                                            <el-form-item label="标题" prop="title">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.title" placeholder="请输入视频标题" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.title || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="内容类型" prop="type">
                                                <template v-if="isEditMode">
                                                    <el-select v-model="formData.type" placeholder="请选择内容类型">
                                                        <el-option label="视频" :value="1" />
                                                        <el-option label="图片" :value="2" />
                                                    </el-select>
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.type === 1 ? 'primary' : 'success'">
                                                        {{ formData.type === 1 ? '视频' : '图片' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="博主ID">
                                                <div class="form-text">{{ videoInfo.blogger_id }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="分类ID" prop="cid">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.cid" placeholder="请输入分类ID" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.cid || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="描述" prop="description">
                                                <template v-if="isEditMode">
                                                    <el-input v-model="formData.description" type="textarea" :rows="3" placeholder="请输入视频描述" />
                                                </template>
                                                <template v-else>
                                                    <div class="form-text">{{ formData.description || '未设置' }}</div>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    媒体信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="24">
                                            <el-form-item label="封面图">
                                                <el-image 
                                                    v-if="videoInfo.cover_image" 
                                                    :src="formatFileUrl(videoInfo.cover_image)" 
                                                    :preview-src-list="[formatFileUrl(videoInfo.cover_image)]"
                                                    fit="cover"
                                                    style="width: 200px; height: 120px; border-radius: 8px;"
                                                />
                                                <span v-else>未设置</span>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24" v-if="videoInfo.type === 1">
                                            <el-form-item label="视频地址">
                                                <div class="form-text">{{ videoInfo.video_url || '未设置' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24" v-if="videoInfo.type === 2">
                                            <el-form-item label="图片地址">
                                                <div class="form-text">{{ videoInfo.images_url || '未设置' }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    数据统计
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <el-form-item label="观看次数">
                                                <div class="form-text">{{ videoInfo.view_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="点赞数">
                                                <div class="form-text">{{ videoInfo.like_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="评论数">
                                                <div class="form-text">{{ videoInfo.comment_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="分享数">
                                                <div class="form-text">{{ videoInfo.share_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="收藏数">
                                                <div class="form-text">{{ videoInfo.collect_count || 0 }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    状态设置
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <el-form-item label="是否推荐" prop="is_recommend">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_recommend" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.is_recommend ? 'warning' : 'info'">
                                                        {{ formData.is_recommend ? '推荐' : '不推荐' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="是否置顶" prop="is_top">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_top" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.is_top ? 'danger' : 'info'">
                                                        {{ formData.is_top ? '置顶' : '不置顶' }}
                                                    </el-tag>
                                                </template>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="8">
                                            <el-form-item label="是否热门" prop="is_hot">
                                                <template v-if="isEditMode">
                                                    <el-switch v-model="formData.is_hot" :active-value="1" :inactive-value="0" />
                                                </template>
                                                <template v-else>
                                                    <el-tag :type="formData.is_hot ? 'success' : 'info'">
                                                        {{ formData.is_hot ? '热门' : '不热门' }}
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
                                            <el-form-item label="审核状态">
                                                <el-tag :type="getAuditStatusType(videoInfo.audit_status)">
                                                    {{ getAuditStatusText(videoInfo.audit_status) }}
                                                </el-tag>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="审核时间">
                                                <div class="form-text">{{ videoInfo.audit_time || '未审核' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="24">
                                            <el-form-item label="审核备注">
                                                <div class="form-text">{{ videoInfo.audit_remark || '无' }}</div>
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
                                            <el-form-item label="发布时间">
                                                <div class="form-text">{{ videoInfo.publish_at || '未发布' }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="创建时间">
                                                <div class="form-text">{{ videoInfo.create_at }}</div>
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

                    <el-tab-pane label="评论记录" name="comment">
                        <DetailVideoShortComment :content_id="videoInfo.id" content_type="short" />
                    </el-tab-pane>


                </el-tabs>
            </div>
        </div>
    </el-drawer>
</template>

<script lang="ts" setup name="VideoShortDetail">
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { formatFileUrl } from '@/utils/util'

import { getVideoShortInfo, updateVideoShort } from '@/pages-admin/main/api/video/short'
import DetailVideoShortComment from '../components/detail-comment.vue'

// 定义短视频信息接口
interface VideoShortInfo {
    id: number;
    blogger_id: number;
    type: number;
    title: string;
    description: string;
    cid: number;
    cover_image: string;
    video_url: string;
    images_url: string;
    view_count: number;
    like_count: number;
    comment_count: number;
    share_count: number;
    collect_count: number;
    is_recommend: number;
    is_top: number;
    is_hot: number;
    audit_status: number;
    audit_remark: string;
    audit_time: string;
    publish_at: string;
    create_at: string;
    update_at: string;
}

const tabSelected = ref('base')
const isEditMode = ref(false)
const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

// 使用定义的接口初始化短视频信息
const videoInfo = reactive<VideoShortInfo>({
    id: 0,
    blogger_id: 0,
    type: 1,
    title: '',
    description: '',
    cid: 0,
    cover_image: '',
    video_url: '',
    images_url: '',
    view_count: 0,
    like_count: 0,
    comment_count: 0,
    share_count: 0,
    collect_count: 0,
    is_recommend: 0,
    is_top: 0,
    is_hot: 0,
    audit_status: 0,
    audit_remark: '',
    audit_time: '',
    publish_at: '',
    create_at: '',
    update_at: ''
})

// 初始化表单数据
const initialFormData = {
    id: 0,
    title: '',
    description: '',
    type: 1,
    cid: 0,
    is_recommend: 0,
    is_top: 0,
    is_hot: 0
}

const formData = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        title: [
            { required: true, message: '请输入视频标题', trigger: 'blur' },
            { min: 1, max: 32, message: '标题长度在 1 到 32 个字符', trigger: 'blur' }
        ],
        description: [
            { required: true, message: '请输入视频描述', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateVideoShort(videoInfo.id, formData).then((res: any) => {
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
    popTitle = '短视频详情'

    if (row) {
        const data = await (await getVideoShortInfo(row.id)).data
        Object.assign(videoInfo, data)
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) (formData as any)[key] = data[key]
        })
    }
    loading.value = false
}

// 审核状态相关方法
const getAuditStatusType = (status: number) => {
    const typeMap: Record<number, string> = {
        0: 'warning',
        1: 'success',
        2: 'danger'
    }
    return typeMap[status] || 'info'
}

const getAuditStatusText = (status: number) => {
    const textMap: Record<number, string> = {
        0: '审核中',
        1: '已发布',
        2: '已下架'
    }
    return textMap[status] || '未知'
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>
