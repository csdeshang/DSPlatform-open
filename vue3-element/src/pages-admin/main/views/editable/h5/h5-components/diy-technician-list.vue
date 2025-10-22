<template>
    <div v-show="store.selectedElementTab === 'content'">

        <el-form :model="store.selectedElement.settings.technicianSetting" label-width="120px">

            <!-- 头部设置 -->
            <el-form-item label="显示头部标题">
                <el-switch v-model="store.selectedElement.settings.technicianSetting.is_show_header_title"></el-switch>
            </el-form-item>

            <el-form-item label="头部标题" v-if="store.selectedElement.settings.technicianSetting.is_show_header_title">
                <el-input v-model="store.selectedElement.settings.technicianSetting.header_title"></el-input>
            </el-form-item>

            <el-form-item label="头部更多链接" v-if="store.selectedElement.settings.technicianSetting.is_show_header_title">
                <UniappLink v-model="store.selectedElement.settings.technicianSetting.header_more_link" />
            </el-form-item>

            <el-form-item label="风格类型" width="240px">
                <el-radio-group v-model="store.selectedElement.settings.technicianSetting.style">
                    <el-radio label="type1" value="type1">列表</el-radio>
                    <el-radio label="type2" value="type2">一行2个</el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="显示数量">
                <el-input v-model="store.selectedElement.settings.technicianSetting.technician_nums" placeholder="请输入师傅数量" />
            </el-form-item>

            <el-form-item label="显示服务次数">
                <el-switch v-model="store.selectedElement.settings.technicianSetting.is_show_service_count" />
            </el-form-item>
        </el-form>


    </div>
    <div v-show="store.selectedElementTab === 'style'">
        <BaseStyles />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import UniappLink from './editors/uniapp-link/index.vue'

// 获取状态管理
const store = useEditableStore();




// 初始化数据
const initialFormData = {

    technicianSetting: {
        // 风格类型 type1 列表 type2 一行2个
        style: 'type1',
        // 师傅数量
        technician_nums: 10,
        // 是否显示服务次数
        is_show_service_count: false,

        // 是否显示头部标题
        is_show_header_title: false,
        // 头部标题
        header_title: '头部标题自定义',
        // 头部更多链接
        header_more_link: '',

    }

}


// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
    if (!newVal || Object.keys(newVal).length === 0) {
        store.selectedElement.settings = initialFormData;
    }
}, { immediate: true, deep: false });





</script>