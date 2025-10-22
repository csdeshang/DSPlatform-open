<template>
    <el-dialog v-model="dialogVisible" :title="popTitle" width="80%" :destroy-on-close="true">
        <el-form :model="formData" label-width="100px" ref="formRef" :rules="formRules" v-loading="loading">
            <el-form-item label="分类" prop="cid">
               
                    <el-tree-select
                    v-model="formData.cid"
                    :data="categoryList"
                    node-key="id"
                    :props="{
                        label: 'name',
                        children: 'children'
                    }"
                    :default-expand-all="false"
                    placeholder="请选择分类"
                    clearable
                    check-strictly
                    class="w-[240px]"
                />
          
            </el-form-item>

            <el-form-item label="标题" prop="title">
                <el-input v-model="formData.title" placeholder="请输入标题"></el-input>
            </el-form-item>

            <el-form-item label="分类图片" prop="image">
                <PickerImage v-model="formData.image" :limit=1 type="image" />
            </el-form-item>

            <el-form-item label="文章内容" prop="content">
                <RichTextEditor v-model="formData.content" />
            </el-form-item>

            <el-form-item label="发布作者" prop="publish_author">
                <el-input v-model="formData.publish_author" placeholder="请输入发布作者"></el-input>
            </el-form-item>

            <el-form-item label="发布时间" prop="publish_time">
                <el-date-picker
                    v-model="publishDate"
                    type="datetime"
                    placeholder="选择发布时间"
                    format="YYYY-MM-DD HH:mm:ss"
                    value-format="YYYY-MM-DD HH:mm:ss"
                    @change="handlePublishDateChange"
                />
            </el-form-item>

            <el-form-item label="虚拟浏览数" prop="virtual_views">
                <el-input-number v-model="formData.virtual_views" :min="0" placeholder="请输入虚拟浏览数"></el-input-number>
            </el-form-item>

            <el-form-item label="排序" prop="sort">
                <el-input-number v-model="formData.sort" :min="0" placeholder="请输入排序值"></el-input-number>
            </el-form-item>

            <el-form-item label="是否显示" prop="is_show">
                <el-switch v-model="formData.is_show" :active-value="1" :inactive-value="0" />
            </el-form-item>
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

import { createSysArticle, updateSysArticle, getSysArticleInfo, getSysArticleCategoryTree } from '@/pages-admin/main/api/system/sysArticle';
import { ElMessage, FormInstance } from 'element-plus';
import PickerImage from '@/components/attachment/picker-image.vue'

import RichTextEditor from '@/components/editor/index.vue'

const dialogVisible = ref(false);
const loading = ref(false);
let popTitle: string = '';

/**
 * 表单数据
 */
const initialFormData = {
    id: '',
    cid: 0,
    title: '',
    image: '',
    content: '',
    publish_author: '',
    publish_time: Math.floor(Date.now() / 1000),
    virtual_views: 0,
    actual_views: 0,
    sort: 0,
    is_show: 1,
};

const formData = reactive({ ...initialFormData });
const formRef = ref<FormInstance>();
const categoryList = ref<any[]>([]);

// 用于日期选择器的值
const publishDate = ref('');

// 日期格式化函数
const formatDateTime = (date: Date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

// 监听 formData.publish_time 变化，更新日期选择器显示
watch(() => formData.publish_time, (newVal) => {
    if (newVal) {
        const date = new Date(newVal * 1000);
        publishDate.value = formatDateTime(date);
    }
}, { immediate: true });



// 处理发布日期变更
const handlePublishDateChange = (val: string) => {
    if (val) {
        // 将日期字符串转换为时间戳（秒）
        formData.publish_time = Math.floor(new Date(val).getTime() / 1000);
    } else {
        // 如果清空日期，使用当前时间
        formData.publish_time = Math.floor(Date.now() / 1000);
    }
};

// 获取文章分类
const fetchCategoryList = async () => {
    try {
        const res = await getSysArticleCategoryTree({});
        categoryList.value = res.data;
    } catch (error) {
        console.error('获取文章分类失败', error);
    }
};

// 表单验证规则
const formRules = computed(() => {
    return {
        cid: [
            { required: true, message: '请选择文章分类', trigger: 'change' }
        ],
        title: [
            { required: true, message: '请输入文章标题', trigger: 'blur' }
        ],
        content: [
            { required: true, message: '请输入文章内容', trigger: 'blur' }
        ],
    };
});

// 提交表单
const handleSubmit = async () => {
    if (loading.value || !formRef.value) return;

    await formRef.value.validate();
    const requestFun = formData.id ? updateSysArticle : createSysArticle;
    loading.value = true;
    
    requestFun(formData).then(res => {
        loading.value = false;
        dialogVisible.value = false;
        // 触发更新列表或其他操作
        emit('complete');
    }).catch(() => {
        loading.value = false;
    });
};

// 设置对话框数据
const setDialogData = async (row: any = null) => {
    loading.value = true;
    await fetchCategoryList();
    Object.assign(formData, initialFormData);
    popTitle = '添加文章';

    if (row?.id) {
        popTitle = '更新文章';
        try {
            const { data } = await getSysArticleInfo(row.id);
            Object.keys(formData).forEach((key: string) => {
                if (data[key] !== undefined) {
                    formData[key] = data[key];
                }
            });
            
            // 更新日期选择器的值
            if (formData.publish_time) {
                const date = new Date(formData.publish_time * 1000);
                publishDate.value = formatDateTime(date);
            }
        } catch (error) {
            console.error('获取文章详情失败', error);
            ElMessage.error('获取文章详情失败');
        }
    } else {
        // 新增文章时，默认使用当前时间
        formData.publish_time = Math.floor(Date.now() / 1000);
        const date = new Date(formData.publish_time * 1000);
        publishDate.value = formatDateTime(date);
    }

    loading.value = false;
};

const emit = defineEmits(['complete']);

// 暴露方法给父组件
defineExpose({
    openDialog: () => {
        dialogVisible.value = true;
    },
    setDialogData
});
</script>

<style scoped>
.w-\[240px\] {
    width: 240px;
}
</style>
