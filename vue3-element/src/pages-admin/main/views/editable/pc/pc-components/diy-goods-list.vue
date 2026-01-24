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

                <el-form-item label="平台">
                    <el-radio-group v-model="selectedPlatform" @change="handlePlatformChange">
                        <el-radio v-for="item in platformList" :key="item.id" :label="item.platform"
                            :value="item.platform" border class="mb-[10px]">
                            {{ item.name }}
                        </el-radio>
                    </el-radio-group>
                </el-form-item>

                <el-form-item label="商品分类">
                    <el-tree-select 
                        v-model="store.selectedElement.settings.goodsSetting.category_id" 
                        :data="categoryList"
                        node-key="id" 
                        :props="{
                            label: 'name',
                            children: 'children'
                        }" 
                        :default-expand-all="false" 
                        placeholder="请选择分类（不选则显示全部）" 
                        clearable 
                        check-strictly 
                        class="w-[240px]" 
                    />
                </el-form-item>



            </el-form>



        </div>

        <!-- 样式设置 -->
        <div v-show="store.selectedElementTab === 'style'">
            <BaseStyles />
        </div>
    </div>
</template>

<script setup>
import { watch, ref, onMounted } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import { getTblGoodsCategoryTree } from '@/pages-admin/main/api/tbl-goods/tblGoodsCategory';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform';
import UniappLink from './editors/uniapp-link/index.vue'

// 获取状态管理
const store = useEditableStore();

// 平台列表
const platformList = ref([]);
// 当前选中的平台（用于联动，不保存到配置中）
const selectedPlatform = ref('');

// 分类列表
const categoryList = ref([]);

// 获取平台列表
const fetchSysPlatformList = async () => {
    try {
        const res = await getSysPlatformList({ scene: 'store' });
        platformList.value = res.data || [];
        // 设置默认平台
        if (platformList.value.length > 0 && !selectedPlatform.value) {
            selectedPlatform.value = platformList.value[0].platform;
        }
    } catch (error) {
        console.error('获取平台列表失败:', error);
        platformList.value = [];
    }
}

// 获取分类列表（根据平台）
const fetchCategoryList = async (platform = '') => {
    try {
        const params = {};
        if (platform) {
            params.platform = platform;
        }
        const res = await getTblGoodsCategoryTree(params);
        categoryList.value = res.data || [];
        // 如果切换平台，清空已选分类
        if (platform && store.selectedElement?.settings?.goodsSetting?.category_id) {
            store.selectedElement.settings.goodsSetting.category_id = '';
        }
    } catch (error) {
        console.error('获取分类列表失败:', error);
        categoryList.value = [];
    }
}

// 平台变化处理
const handlePlatformChange = (platform) => {
    fetchCategoryList(platform);
}

onMounted(() => {
    fetchSysPlatformList().then(() => {
        // 平台列表加载完成后，加载分类列表
        if (selectedPlatform.value) {
            fetchCategoryList(selectedPlatform.value);
        } else {
            fetchCategoryList();
        }
    });
});

// 初始化数据
const initialFormData = {
    // 商品设置
    goodsSetting: {
        // 分类ID（单个选择）
        category_id: '',
        // 排序  default 默认  price 价格  sales 销量  new 新品  hot 热销  recommend 推荐
        sort: 'default',
        // 显示数量
        nums: 10,
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

