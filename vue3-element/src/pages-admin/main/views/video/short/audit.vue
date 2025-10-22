<template>
    <el-dialog v-model="dialogVisible" title="审核短视频" width="600px">
        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="100px">
            <el-form-item label="视频标题">
                <div class="form-text">{{ videoInfo.title }}</div>
            </el-form-item>
            <el-form-item label="博主ID">
                <div class="form-text">{{ videoInfo.blogger_id }}</div>
            </el-form-item>
            <el-form-item label="内容类型">
                <el-tag :type="videoInfo.type === 1 ? 'primary' : 'success'">
                    {{ videoInfo.type === 1 ? '视频' : '图片' }}
                </el-tag>
            </el-form-item>
            <el-form-item label="封面图" v-if="videoInfo.cover_image">
                <el-image 
                    :src="videoInfo.cover_image" 
                    :preview-src-list="[videoInfo.cover_image]"
                    fit="cover"
                    style="width: 120px; height: 80px; border-radius: 4px;"
                />
            </el-form-item>
            <el-form-item label="描述">
                <div class="form-text">{{ videoInfo.description }}</div>
            </el-form-item>
            <el-form-item label="审核状态" prop="audit_status">
                <el-radio-group v-model="formData.audit_status">
                    <el-radio :label="1">通过发布</el-radio>
                    <el-radio :label="2">拒绝下架</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="审核备注" prop="audit_remark">
                <el-input 
                    v-model="formData.audit_remark" 
                    type="textarea" 
                    :rows="3" 
                    placeholder="请输入审核备注"
                />
            </el-form-item>
        </el-form>
        
        <template #footer>
            <span class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="handleSubmit" :loading="loading">确定</el-button>
            </span>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup name="VideoShortAudit">
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { auditVideoShort } from '@/pages-admin/main/api/video/short'
import { ElMessage } from 'element-plus'

// 定义短视频信息接口
interface VideoShortInfo {
    id: number;
    blogger_id: number;
    type: number;
    title: string;
    description: string;
    cover_image: string;
    audit_status: number;
}

const dialogVisible = ref(false)
const loading = ref(false)

// 短视频信息
const videoInfo = reactive<VideoShortInfo>({
    id: 0,
    blogger_id: 0,
    type: 1,
    title: '',
    description: '',
    cover_image: '',
    audit_status: 0
})

// 表单数据
const formData = reactive({
    id: 0,
    audit_status: 1,
    audit_remark: ''
})

const formRef = ref<FormInstance>()

// 表单验证规则
const formRules = computed(() => {
    return {
        audit_status: [
            { required: true, message: '请选择审核状态', trigger: 'change' }
        ],
        audit_remark: [
            { required: true, message: '请输入审核备注', trigger: 'blur' },
            { min: 1, max: 255, message: '备注长度在 1 到 255 个字符', trigger: 'blur' }
        ]
    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    
    try {
        await formRef.value.validate()
        loading.value = true
        
        await auditVideoShort(formData)
        
        ElMessage.success('审核成功')
        dialogVisible.value = false
        emit('complete')
    } catch (error) {
        console.error('审核失败', error)
        ElMessage.error('审核失败')
    } finally {
        loading.value = false
    }
}

const setDialogData = (row: any = null) => {
    if (row) {
        Object.assign(videoInfo, row)
        formData.id = row.id
        formData.audit_status = 1
        formData.audit_remark = ''
    }
}

defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})
</script>

<style scoped>
.form-text {
    color: #606266;
    font-size: 14px;
    line-height: 1.5;
    word-break: break-all;
}
</style> 