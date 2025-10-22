<template>
    <div>
        <!-- 内容设置 -->
        <div v-show="store.selectedElementTab === 'content'">

            <el-form label-width="120px" class="px-[5px]">

                <!-- 头部设置 -->
                <el-form-item label="显示头部标题">
                    <el-switch v-model="store.selectedElement.settings.goodsSetting.is_show_header_title"></el-switch>
                </el-form-item>

                <el-form-item label="头部标题" v-if="store.selectedElement.settings.goodsSetting.is_show_header_title">
                    <el-input v-model="store.selectedElement.settings.goodsSetting.header_title"></el-input>
                </el-form-item>

                <el-form-item label="头部更多链接" v-if="store.selectedElement.settings.goodsSetting.is_show_header_title">
                    <UniappLink v-model="store.selectedElement.settings.goodsSetting.header_more_link" />
                </el-form-item>


                <el-form-item label="排序">
                    <el-radio-group v-model="store.selectedElement.settings.goodsSetting.sort">
                        <el-radio value="default">默认</el-radio>
                        <el-radio value="price">价格</el-radio>
                        <el-radio value="sales">销量</el-radio>
                        <el-radio value="new">新品</el-radio>
                        <el-radio value="hot">热销</el-radio>
                        <el-radio value="recommend">推荐</el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="显示数量">
                    <el-slider v-model="store.selectedElement.settings.goodsSetting.nums" show-input size="small"
                        class="ml-[10px]" :max="50" />
                </el-form-item>

                <el-form-item label="平台商品">
                    <el-radio-group v-model="store.selectedElement.settings.goodsSetting.platform">
                        <el-radio label="all" border value="" class="mb-[10px]">全部</el-radio>
                        <el-radio v-for="item in platformList" :key="item.id" :label="item.platform"
                            :value="item.platform" border class="mb-[10px]">
                            {{ item.name }}
                        </el-radio>
                    </el-radio-group>
                </el-form-item>

                <!--
                <el-form-item label="商品来源">
                    <el-radio-group v-model="store.selectedElement.settings.goodsSetting.source">
                        <el-radio label="all" value="all">全部</el-radio>
                        <el-radio label="select" value="select">自选</el-radio>
                        <el-radio label="category" value="category">分类</el-radio>
                        <el-radio label="brand" value="brand">品牌</el-radio>
                    </el-radio-group>
                </el-form-item>
                -->



            </el-form>



        </div>

        <!-- 样式设置 -->
        <div v-show="store.selectedElementTab === 'style'">
            <BaseStyles />
        </div>
    </div>
</template>

<script setup>
import { watch, ref } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform';
import UniappLink from './editors/uniapp-link/index.vue'

// 获取状态管理
const store = useEditableStore();

// 平台列表
const platformList = ref([])
// 获取平台列表 store 类型
const fetchSysPlatformList = async () => {
    const res = await getSysPlatformList({ scene: 'store' })
    platformList.value = res.data
}
fetchSysPlatformList()

// 初始化数据
const initialFormData = {
    // 商品设置
    goodsSetting: {
        // 平台 all:全部 platform:平台
        platform: 'all',
        // 排序  default 默认  price 价格  sales 销量  new 新品  hot 热销  recommend 推荐
        sort: 'default',
        // 显示数量
        nums: 10,
        // 商品来源  all 全部  select 自选  category 分类  brand 品牌
        source: 'all',
        // 自选
        goods_ids: [],
        // 分类
        category_ids: [],
        // 品牌
        brand_ids: [],

        // 是否显示头部标题
        is_show_header_title: false,
        // 头部标题
        header_title: '头部标题自定义',
        // 头部更多链接
        header_more_link: '',
    },
    // 样式设置
    styleSetting: {
        // 布局  grid 网格  list 列表  row2 一行两个  row3-scroll 一行三个(可滑动)
        layout: 'grid',
    }


}


// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
    if (!newVal || Object.keys(newVal).length === 0) {
        store.selectedElement.settings = initialFormData;
    }
}, { immediate: true, deep: false });




</script>

<style scoped>
/* 样式可以根据需要进行调整 */
</style>