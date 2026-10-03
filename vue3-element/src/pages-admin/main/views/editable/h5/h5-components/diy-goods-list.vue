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
                    <el-radio-group v-model="store.selectedElement.settings.goodsSetting.platform"
                        @change="handlePlatformChange">
                        <el-radio label="all" border value="" class="mb-[10px]">全部</el-radio>
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
                        :props="{ label: 'name', children: 'children' }"
                        :placeholder="categoryPlaceholder"
                        :disabled="!currentPlatform"
                        clearable
                        check-strictly
                        filterable
                        class="w-[240px]"
                        @change="handleCategoryChange"
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
import { watch, ref, computed } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform';
import { getTblGoodsCategoryTree } from '@/pages-admin/main/api/tbl-goods/tblGoodsCategory';
import UniappLink from './editors/uniapp-link/index.vue'

// 获取状态管理
const store = useEditableStore();

// 平台列表
const platformList = ref([])
const categoryList = ref([])

const currentPlatform = computed(() => {
    const platform = store.selectedElement?.settings?.goodsSetting?.platform
    if (!platform || platform === 'all') {
        return ''
    }
    return platform
})

const categoryPlaceholder = computed(() =>
    currentPlatform.value ? '全部' : '请先选择平台'
)

const fetchSysPlatformList = async () => {
    const res = await getSysPlatformList({ scene: 'store' })
    platformList.value = res.data || []
}
fetchSysPlatformList()

const fetchCategoryList = async (platform = '') => {
    if (!platform) {
        categoryList.value = []
        return
    }
    try {
        const res = await getTblGoodsCategoryTree({ platform })
        categoryList.value = res.data || []
    } catch (error) {
        console.error('获取商品分类失败:', error)
        categoryList.value = []
    }
}

const handleCategoryChange = (val) => {
    if (!val && store.selectedElement?.settings?.goodsSetting) {
        store.selectedElement.settings.goodsSetting.category_id = undefined
    }
}

const handlePlatformChange = () => {
    if (store.selectedElement?.settings?.goodsSetting) {
        store.selectedElement.settings.goodsSetting.category_id = undefined
    }
    fetchCategoryList(currentPlatform.value)
}

watch(currentPlatform, (platform) => {
    fetchCategoryList(platform)
}, { immediate: true })

// 初始化数据
const initialFormData = {
    // 商品设置
    goodsSetting: {
        // 平台 all:全部 platform:平台
        platform: '',
        // 商品分类ID
        category_id: undefined,
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