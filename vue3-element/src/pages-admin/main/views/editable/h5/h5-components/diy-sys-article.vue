<template>
  <div>
    <!-- 内容设置 -->
    <div v-show="store.selectedElementTab === 'content'">

      <el-form :model="store.selectedElement.settings.articleSetting" label-width="120px">

        <!-- 头部设置 -->
        <el-form-item label="显示头部标题">
          <el-switch v-model="store.selectedElement.settings.articleSetting.is_show_header_title"></el-switch>
        </el-form-item>

        <el-form-item label="头部标题">
          <el-input v-model="store.selectedElement.settings.articleSetting.header_title"></el-input>
        </el-form-item>

        <el-form-item label="头部更多链接">
          <UniappLink v-model="store.selectedElement.settings.articleSetting.header_more_link" />
        </el-form-item>


        <el-form-item label="风格类型" width="240px">
          <el-radio-group v-model="store.selectedElement.settings.articleSetting.style">
            <el-radio value="type1">列表</el-radio>
            <el-radio value="type2">列表(带图片)</el-radio>
            <el-radio value="type3">一行2个</el-radio>
            <el-radio value="type4">大图</el-radio>
          </el-radio-group>
        </el-form-item>



        <el-form-item label="分类">
          <el-tree-select v-model="store.selectedElement.settings.articleSetting.article_cid" :data="categoryList"
            node-key="id" :props="{
              label: 'name',
              children: 'children'
            }" :default-expand-all="false" placeholder="请选择分类" clearable check-strictly class="w-[240px]" />
        </el-form-item>


        <el-form-item label="显示数量">
          <el-input v-model="store.selectedElement.settings.articleSetting.article_nums"
            placeholder="请输入显示数量"></el-input>
        </el-form-item>

        <el-form-item label="是否显示阅读量">
          <el-switch v-model="store.selectedElement.settings.articleSetting.is_show_views"></el-switch>
        </el-form-item>

        <el-form-item label="是否显示日期">
          <el-switch v-model="store.selectedElement.settings.articleSetting.is_show_date"></el-switch>
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
import UniappLink from './editors/uniapp-link/index.vue'

import { getSysArticleCategoryTree } from '@/pages-admin/main/api/system/sysArticle';



// 获取状态管理
const store = useEditableStore();





// 初始化数据
const initialFormData = {
  articleSetting: {
    // 风格类型 type1:列表 type2:列表(带图片) type3:一行2个 type4:大图
    style: 'type1',
    // 文章分类
    article_cid: '',
    // 显示数量
    article_nums: '',
    // 是否显示阅读量
    is_show_views: false,
    // 是否显示日期
    is_show_date: false,

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


const categoryList = ref([]);
// 获取分类列表
const fetchSysArticleCategoryTree = async () => {
  try {
    const res = await getSysArticleCategoryTree({});
    categoryList.value = res.data || [];
  } catch (error) {
    console.error('获取文章分类失败', error);
  }
};

onMounted(() => {
  fetchSysArticleCategoryTree();
});


</script>

<style scoped>
/* 样式可以根据需要进行调整 */
</style>