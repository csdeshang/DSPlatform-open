<template>
    <!-- PC端链接选择器 -->
    <div>
      <!-- 输入框与弹窗触发器 -->
      <el-input
        v-model="inputValue"
        placeholder="请输入链接地址"
        @click="showDialog = true"
        readonly
      >
        <template #append>
          <el-button @click="showDialog = true">
            <el-icon><Select /></el-icon>
          </el-button>
        </template>
      </el-input>
  
      <!-- 链接选择对话框 -->
      <el-dialog
        v-model="showDialog"
        title="PC端链接选择器"
        width="80%"
        @close="handleClose"
      >
        <div class="link-dialog-container">
          <!-- 左侧分类导航 -->
          <div class="category-nav">
            <el-tree
              ref="treeRef"
              :data="categoryList"
              :props="treeProps"
              node-key="value"
              @node-click="handleNodeClick"
            />
          </div>
  
          <!-- 右侧内容区 -->
          <div class="content-area">
            <!-- 加载状态 -->
            <div v-if="loading" class="loading-container">
              <el-icon class="loading-icon"><Loading /></el-icon>
              <span>加载中...</span>
            </div>
  
            <!-- 静态链接列表 -->
            <div v-else-if="showType === 'link_content'" class="link-grid">
              <div
                v-for="item in linkList"
                :key="item.value"
                class="link-grid-item"
                :class="{ selected: selectedLink === item.value }"
                @click="selectLink(item)"
              >
                {{ item.label }}
              </div>
            </div>
  
            <!-- 动态内容组件 -->
            <diy-list 
              v-if="showType === 'diy_content'"
              @select="selectDynamicItem"
            />
  
            <article-list 
              v-if="showType === 'article_content'"
              @select="selectDynamicItem"
            />
  
            <goods-list 
              v-if="showType === 'goods_list_content'"
              :platform="currentPlatform"
              @select="selectDynamicItem"
            />
  
            <goods-category 
              v-if="showType === 'goods_category_content'"
              :platform="currentPlatform"
              @select="selectDynamicItem"
            />
  
            <coupon-list 
              v-if="showType === 'coupon_list_content'"
              :platform="currentPlatform"
              @select="selectDynamicItem"
            />

            <store-category 
              v-if="showType === 'store_category_content'"
              :platform="currentPlatform"
              @select="selectDynamicItem"
            />
          </div>
        </div>
  
        <template #footer>
          <div class="dialog-footer">
            <el-button @click="showDialog = false">取消</el-button>
            <el-button type="primary" @click="confirmSelection" :disabled="!selectedLink">确认</el-button>
          </div>
        </template>
      </el-dialog>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, watch } from 'vue'
  import DiyList from './diy-list.vue'
  import ArticleList from './article-list.vue'
  import GoodsList from './goods-list.vue'
  import GoodsCategory from './goods-category.vue'
  import CouponList from './coupon-list.vue'
  import StoreCategory from './store-category.vue'
  
  // 定义props和emits
  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  })
  
  const emit = defineEmits(['update:modelValue', 'change'])
  
  // 基础状态
  const showDialog = ref(false)
  const inputValue = ref(props.modelValue)
  const loading = ref(false)
  const showType = ref('link_content')  // 默认显示链接内容
  const selectedLink = ref('')
  const linkList = ref<any[]>([])
  const currentPlatform = ref('')
  
  // 同步输入值和props
  watch(() => props.modelValue, (val) => {
    inputValue.value = val
  })
  
  // 树形控件配置
  const treeProps = {
    label: 'label',
    children: 'children'
  }
  

  
  // 分类数据
  const categoryList = [
    {
      label: '系统页面',
      value: 'system',
      children: [
        {
          label: '系统链接', value: 'system_index'
        },
        {
          label: '用户中心', value: 'system_user',
        },
      ]
    },
    {
      label: '自定义页面',
      value: 'diy_list',
    },
    {
      label: '文章页面',
      value: 'article',
      children: [
        { label: '文章', value: 'article_list' },
      ]
    },
    {
      label: '商城系统',
      value: 'mall',
      children: [
        {
          label: '商城链接', value: 'mall_index',
        },
        { label: '选择商品', value: 'mall_goods_list' },
        { label: '商品分类', value: 'mall_goods_category' },
        { label: '店铺分类', value: 'mall_store_category' },
        // { label: '优惠券', value: 'mall_coupon_list' }
      ]
    },
    {
      label: '外卖系统',
      value: 'food',
      children: [
        // {
        //   label: '外卖链接', value: 'food_index',
        // },
        // { label: '选择商品', value: 'food_goods_list' },
        // { label: '商品分类', value: 'food_goods_category' },
        // { label: '店铺分类', value: 'food_store_category' },
        // { label: '优惠券', value: 'food_coupon_list' }
      ]
    },
    {
      label: '视频教育系统',
      value: 'kms',
      children: [
        // {
        //   label: '视频教育链接', value: 'kms_index',
        // },
        // { label: '选择商品', value: 'kms_goods_list' },
        // { label: '商品分类', value: 'kms_goods_category' },
        // { label: '店铺分类', value: 'kms_store_category' },
        // { label: '优惠券', value: 'kms_coupon_list' }
      ]
    },
    {
      label: '家政系统',
      value: 'house',
      children: [
        // {
        //   label: '家政链接', value: 'house_index',
        // },
        // { label: '选择商品', value: 'house_goods_list' },
        // { label: '商品分类', value: 'house_goods_category' },
        // { label: '店铺分类', value: 'house_store_category' },
        // { label: '优惠券', value: 'house_coupon_list' }
      ]
    },
  ]
  
  // 处理节点点击
  const handleNodeClick = (data: any) => {
    // 点击节点时重置选中状态
    selectedLink.value = ''
    
    switch (data.value) {
      case 'system_index':
        showType.value = 'link_content'
        loadSystemLinks()
        break
      case 'system_user':
        showType.value = 'link_content'
        loadUserLinks()
        break
      case 'diy_list':
        showType.value = 'diy_content'
        break
      case 'article_list':
        showType.value = 'article_content'
        break
        // 商城系统
      case 'mall_index':
        showType.value = 'link_content'
        loadMallLinks()
        break
      case 'mall_goods_list':
        showType.value = 'goods_list_content'
        currentPlatform.value = 'mall'
        break
      case 'mall_goods_category':
        showType.value = 'goods_category_content'
        currentPlatform.value = 'mall'
        break
      case 'mall_store_category':
        showType.value = 'store_category_content'
        currentPlatform.value = 'mall'
        break
      case 'mall_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'mall'
        break
        // 外卖系统
      case 'food_index':
        showType.value = 'link_content'
        loadFoodLinks()
        break
      case 'food_goods_list':
        showType.value = 'goods_list_content'
        currentPlatform.value = 'food'
        break
      case 'food_goods_category':
        showType.value = 'goods_category_content'
        currentPlatform.value = 'food'
        break
      case 'food_store_category':
        showType.value = 'store_category_content'
        currentPlatform.value = 'food'
        break
      case 'food_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'food'
        break
        // 视频教育系统
      case 'kms_index':
        showType.value = 'link_content'
        loadKmsLinks()
        break
      case 'kms_goods_list':
        showType.value = 'goods_list_content'
        currentPlatform.value = 'kms'
        break
      case 'kms_goods_category':
        showType.value = 'goods_category_content'
        currentPlatform.value = 'kms'
        break
      case 'kms_store_category':
        showType.value = 'store_category_content'
        currentPlatform.value = 'kms'
        break
      case 'kms_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'kms'
        break
        // 家政系统
      case 'house_index':
        showType.value = 'link_content'
        loadHouseLinks()
        break
      case 'house_goods_list':
        showType.value = 'goods_list_content'
        currentPlatform.value = 'house'
        break
      case 'house_goods_category':
        showType.value = 'goods_category_content'
        currentPlatform.value = 'house'
        break
      case 'house_store_category':
        showType.value = 'store_category_content'
        currentPlatform.value = 'house'
        break
      case 'house_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'house'
        break
    }
  }
  
  // 加载系统链接（nuxt-consumer，无 /pages 前缀）
  const loadSystemLinks = () => {
    linkList.value = [
      { label: '首页', value: '/' },
      { label: '商城首页', value: '/' },
    ]
  }

  // 加载用户中心链接（nuxt-consumer）
  const loadUserLinks = () => {
    linkList.value = [
      { label: '个人中心', value: '/user/index' },
      { label: '收货地址', value: '/user/address' },
      { label: '我的订单', value: '/user/order' },
      { label: '我的积分', value: '/user/points' },
      { label: '我的佣金', value: '/user/withdrawal/apply' },
      { label: '我的余额', value: '/user/balance' },
      { label: '我的收藏', value: '/user/favorites' },
      { label: '我的消息', value: '/system/notice' },
      { label: '我的设置', value: '/user/setting/profile' }
    ]
  }

  // 加载商城链接（nuxt-consumer）
  const loadMallLinks = () => {
    linkList.value = [
      { label: '商城首页', value: '/' },
      // { label: '商品分类', value: '/mall/search/goodslist' },
      // { label: '店铺分类', value: '/mall/search/storelist' },
      { label: '商城购物车', value: '/mall/cart' },
      { label: '商品列表', value: '/mall/search/goodslist' },
      { label: '店铺列表', value: '/mall/search/storelist' }
    ]
  }

  // 加载外卖链接（nuxt-consumer；购物车仅 mall 有落地页时用 /mall/cart）
  const loadFoodLinks = () => {
    linkList.value = [
    ]
  }

  // 加载视频教育链接（nuxt-consumer）
  const loadKmsLinks = () => {
    linkList.value = [
    ]
  }

  // 加载家政链接（nuxt-consumer；师傅列表暂无独立页时指向 /house）
  const loadHouseLinks = () => {
    linkList.value = [

    ]
  }
  
  // 选择普通链接
  const selectLink = (item: any) => {
    selectedLink.value = item.value
  }
  
  // 选择动态项目
  const selectDynamicItem = (item: any) => {
    selectedLink.value = item.link
  }
  
  // 确认选择
  const confirmSelection = () => {
    if (selectedLink.value) {
      inputValue.value = selectedLink.value
      emit('update:modelValue', selectedLink.value)
      emit('change', selectedLink.value)
      showDialog.value = false
    }
  }
  
  // 处理关闭
  const handleClose = () => {
    showType.value = 'link_content'  // 关闭时重置为默认显示
    selectedLink.value = ''
    currentPlatform.value = ''
    loadSystemLinks()  // 重置为系统链接
  }

  // 初始化时加载系统链接
  loadSystemLinks()
  </script>
  
  <style scoped lang="scss">
  .link-dialog-container {
    display: flex;
    height: 500px;
    
    .category-nav {
      width: 220px;
      border-right: 1px solid #EBEEF5;
      overflow-y: auto;
      padding: 10px;
    }
    
    .content-area {
      flex: 1;
      padding: 16px;
      overflow-y: auto;
      
      .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        
        .loading-icon {
          font-size: 24px;
          margin-bottom: 8px;
          animation: rotating 2s linear infinite;
        }
      }
      
      .link-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
        
        &-item {
          padding: 10px;
          border: 1px solid #DCDFE6;
          border-radius: 4px;
          cursor: pointer;
          text-align: center;
          transition: all 0.3s;
          
          &:hover {
            border-color: #409EFF;
            color: #409EFF;
          }
          
          &.selected {
            background-color: #ECF5FF;
            border-color: #409EFF;
            color: #409EFF;
          }
        }
      }
    }
  }
  
  @keyframes rotating {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
  </style>
  