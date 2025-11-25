<template>


    <el-card shadow="never">




        <el-form-item label="选择底部导航" class="mb-[50px] mt-[20px]">
            <el-select v-model="currentPlatform" placeholder="请选择平台" @change="changePlatform">
                <el-option v-for="(item, index) in platformList" :key="index" :label="item.name"
                    :value="item.platform"></el-option>
            </el-select>
        </el-form-item>


        <div class="flex flex-row">


            <!--左边-->
            <div class="flex flex-col w-[380px] mr-[30px]">
                <div class="flex flex-row justify-center mt-8">
                    <div v-for="(item, index) in navConfig.items" :key="index"
                        class="flex flex-col items-center p-4 cursor-pointer" :style="{
                            backgroundColor: navConfig.theme.backgroundColor,
                            color: navConfig.theme.color,
                            fontSize: `${navConfig.theme.fontSize}px`,
                            borderRadius: '8px',
                            width: '80px',
                            transition: 'all 0.3s'
                        }" @mouseenter="hoverIndex = index" @mouseleave="hoverIndex = -1">
                        <el-image :src="hoverIndex === index ? formatImageUrl(item.activeIcon, ThumbnailPresets.small) : formatImageUrl(item.inactiveIcon, ThumbnailPresets.small)"
                            class="w-[40px] h-[40px] mb-2"
                            v-if="navConfig.theme.displayMode === 'image' || navConfig.theme.displayMode === 'all'" />
                        <div v-if="navConfig.theme.displayMode === 'text' || navConfig.theme.displayMode === 'all'"
                            class="text-center" :style="{
                                fontSize: `${navConfig.theme.fontSize}px`
                            }">
                            {{ item.title }}
                        </div>
                    </div>
                </div>

            </div>

            <!--右边-->
            <div class="flex flex-col flex-1">
                <el-tabs v-model="activeTab">
                    <el-tab-pane name="settings" label="内容设置">

                        <VueDraggable v-model="navConfig.items" :animation="150" ghostClass="ghost-nav-item">

                            <el-form v-for="(item, index) in navConfig.items" :key="index" :model="item"
                                label-width="100px" class="mb-6 border p-4 rounded relative">
                                <el-form-item label="导航名称">
                                    <el-input v-model="item.name" />
                                </el-form-item>
                                <el-form-item label="导航链接">
                                    <UniappLink v-model="item.link" style="width: 100%;" />
                                </el-form-item>
                                <el-form-item label="默认图标">
                                    <div class="flex flex-row">
                                        <div class="flex flex-col items-center justify-center mr-[20px]">
                                            <PickerImage v-model="item.inactiveIcon" width="80px" height="80px" type="image" />
                                            <div class="text-center text-sm text-gray-500 leading-10">默认图标</div>
                                        </div>
                                        <div class="flex flex-col items-center justify-center">
                                            <PickerImage v-model="item.activeIcon" width="80px" height="80px" type="image" />
                                            <div class="text-center text-sm text-gray-500 leading-10">选中图标</div>
                                        </div>
                                    </div>
                                </el-form-item>
                                <el-form-item label="导航文字">
                                    <el-input v-model="item.title" />
                                </el-form-item>
                                <Icon icon="element CircleCloseFilled" :size="24" @click="removeNavItem(index)"
                                    class="absolute -top-1 -right-1" />
                            </el-form>
                        </VueDraggable>


                        <el-button type="primary" @click="addNavItem" class="mb-4">添加导航项</el-button>

                    </el-tab-pane>
                    <el-tab-pane name="type" label="风格设置">
                        <div class="p-4">
                            <el-form :model="navConfig.theme" label-width="100px">
                                <el-form-item label="导航类型">
                                    <el-radio-group v-model="navConfig.theme.displayMode">
                                        <el-radio value="all">图文</el-radio>
                                        <el-radio value="text">文字</el-radio>
                                        <el-radio value="image">图片</el-radio>
                                    </el-radio-group>
                                </el-form-item>

                                <el-form-item label="背景颜色">
                                    <el-color-picker v-model="navConfig.theme.backgroundColor" />
                                </el-form-item>

                                <el-form-item label="文字颜色">
                                    <el-color-picker v-model="navConfig.theme.color" />
                                </el-form-item>

                                <el-form-item label="文字大小">
                                    <el-input v-model="navConfig.theme.fontSize" placeholder="请输入文字大小" class="w-[120px]">
                                        <template #append>px</template>
                                    </el-input>
                                </el-form-item>
                            </el-form>
                        </div>
                    </el-tab-pane>
                </el-tabs>



            </div>

        </div>


    </el-card>


    <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-center"
        :style="{ backgroundColor: 'var(--el-bg-color)' }">
        <el-button type="primary" @click="handleSubmit" :loading="loading">保存</el-button>
    </div>



</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";

import { formatImageUrl, ThumbnailPresets } from '@/utils/image'

import PickerImage from '@/components/attachment/picker-image.vue'
import UniappLink from './h5-components/editors/uniapp-link/index.vue'
import { VueDraggable } from 'vue-draggable-plus';

import { getSysConfigInfoByKey, updateSysConfigInfo } from '@/pages-admin/main/api/system/sysConfig'


import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'
import { ElMessage } from "element-plus";

import Icon from '@/components/icon/index.vue'




const currentPlatform = ref('system')
const loading = ref(false)
const activeTab = ref('settings')
const hoverIndex = ref(-1) // 当前hover的导航项索引


interface NavItem {
    name: string;
    link: string;
    inactiveIcon: string;
    activeIcon: string;
    title: string;
}

interface NavTheme {
    displayMode: 'all' | 'text' | 'image';
    backgroundColor: string;
    color: string;
    fontSize: string;
}

interface NavConfig {
    items: NavItem[];
    theme: NavTheme;
}

const navConfig = ref<NavConfig>({
    items: [],
    theme: {
        displayMode: 'all',
        backgroundColor: '',
        color: '',
        fontSize: '',
    }
});


// 添加导航项
const addNavItem = () => {
    navConfig.value.items.push({
        name: '',
        link: '',
        inactiveIcon: '',
        activeIcon: '',
        title: ''
    });
};

// 删除导航项
const removeNavItem = (index: number) => {
    navConfig.value.items.splice(index, 1);
};



// 获取平台列表
const platformList = ref<any[]>([])
const fetchPlatformList = async () => {
    try {
        const response = await getSysPlatformList({});
        platformList.value = response.data
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
};

// 获取系统配置
const fetchSysConfigInfo = async (platform: string) => {
    try {
        //拼接key
        let key = 'h5_' + platform + '_bottom_nav'
        const response = await getSysConfigInfoByKey(key);
        if (response.code === 10000) {
            const config = response.data.config_value;
            if (config) {
                navConfig.value = JSON.parse(config);
            }
        }
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
}


// 切换平台
const changePlatform = (platform: string) => {
    currentPlatform.value = platform
    fetchSysConfigInfo(platform)
}


// 保存
const handleSubmit = async () => {

    try {
        loading.value = true

        const config_key = 'h5_' + currentPlatform.value + '_bottom_nav'
        const config_value = JSON.stringify(navConfig.value)

        const result = await updateSysConfigInfo({
            config_type: 'website',
            config_key: config_key,
            config_value: config_value,
        })
        if (result.code === 10000) {
            ElMessage.success('保存成功')
        } 
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}



// 组件挂载时获取系统配置
onMounted(() => {
    //获取平台列表
    fetchPlatformList();
    //获取系统配置
    fetchSysConfigInfo(currentPlatform.value);
});


</script>
