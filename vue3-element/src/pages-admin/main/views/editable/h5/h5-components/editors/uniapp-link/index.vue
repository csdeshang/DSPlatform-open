<template>
    <!-- Uniapp链接选择器 -->
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
        title="Uniapp链接选择器"
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
  import { ref, computed, watch } from 'vue'
  import DiyList from './diy-list.vue'
  import ArticleList from './article-list.vue'
  import GoodsList from './goods-list.vue'
  import GoodsCategory from './goods-category.vue'
  import CouponList from './coupon-list.vue'
  
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
  const showType = ref('')
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
        { label: '优惠券', value: 'mall_coupon_list' }
      ]
    },
    {
      label: '外卖系统',
      value: 'food',
      children: [
        {
          label: '外卖链接', value: 'food_index',
        },
        { label: '选择商品', value: 'food_goods_list' },
        { label: '商品分类', value: 'food_goods_category' },
        { label: '优惠券', value: 'food_coupon_list' }
      ]
    },
    {
      label: '视频教育系统',
      value: 'kms',
      children: [
        {
          label: '视频教育链接', value: 'kms_index',
        },
        { label: '选择商品', value: 'kms_goods_list' },
        { label: '商品分类', value: 'kms_goods_category' },
        { label: '优惠券', value: 'kms_coupon_list' }
      ]
    },
    {
      label: '家政系统',
      value: 'house',
      children: [
        {
          label: '家政链接', value: 'house_index',
        },
        { label: '选择商品', value: 'house_goods_list' },
        { label: '商品分类', value: 'house_goods_category' },
        { label: '优惠券', value: 'house_coupon_list' }
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
      case 'kms_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'house'
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
      case 'house_coupon_list':
        showType.value = 'coupon_list_content'
        currentPlatform.value = 'house'
        break
    }
  }
  
  // 加载系统链接
  const loadSystemLinks = () => {
    linkList.value = [
      { label: '首页', value: '/home/pages/index' },
      { label: '商城首页', value: '/home/platform/mall/pages/index' },
      { label: '外卖首页', value: '/home/platform/food/pages/index' },
      { label: '家政首页', value: '/home/platform/house/pages/index' },
      { label: '教育首页', value: '/home/platform/kms/pages/index' },
      { label: '视频首页', value: '/video/pages/index' },
    ]
  }
  
  // 加载用户中心链接
  const loadUserLinks = () => {
    linkList.value = [
      { label: '个人中心', value: '/home/pages/user/index/index' },
      { label: '收货地址', value: '/home/pages/user/address/index' },
      { label: '我的订单', value: '/home/pages/user/order/index' },
      { label: '我的积分', value: '/home/pages/user/points/index' },
      { label: '我的佣金', value: '/home/pages/user/commission/index' },
      { label: '我的余额', value: '/home/pages/user/balance/index' },
      { label: '我的收藏', value: '/home/pages/user/collect/index' },
      { label: '我的消息', value: '/home/pages/user/message/index' },
      { label: '我的设置', value: '/home/pages/user/setting/index' },
    ]
  }
  
  // 加载商城链接
  const loadMallLinks = () => {
    linkList.value = [
      { label: '商城首页', value: '/home/platform/mall/pages/index' },
      { label: '商品分类', value: '/home/platform/mall/pages/goods/category/index' },
      { label: '店铺分类', value: '/home/platform/mall/pages/search/storecategory/index' },
      { label: '商城购物车', value: '/home/platform/mall/pages/cart/index' },
      { label: '商品列表', value: '/home/platform/mall/pages/search/goodslist/index' },
      { label: '店铺列表', value: '/home/platform/mall/pages/search/storelist/index' },
    ]
  }
  
  // 加载外卖链接
  const loadFoodLinks = () => {
    linkList.value = [
      { label: '外卖首页', value: '/home/platform/food/pages/index' },
      { label: '商品分类', value: '/home/platform/food/pages/goods/category/index' },
      { label: '店铺分类', value: '/home/platform/food/pages/search/storecategory/index' },
      { label: '外卖购物车', value: '/home/platform/food/pages/cart/index' },
      { label: '商品列表', value: '/home/platform/food/pages/search/goodslist/index' },
      { label: '店铺列表', value: '/home/platform/food/pages/search/storelist/index' },
    ]
  }

    // 加载视频教育链接
    const loadKmsLinks = () => {
    linkList.value = [
      { label: '视频教育首页', value: '/home/platform/kms/pages/index' },
      { label: '视频教育分类', value: '/home/platform/kms/pages/goods/category/index' },
      { label: '店铺分类', value: '/home/platform/kms/pages/search/storecategory/index' },
      { label: '视频教育购物车', value: '/home/platform/kms/pages/cart/index' },
      { label: '商品列表', value: '/home/platform/kms/pages/search/goodslist/index' },
      { label: '店铺列表', value: '/home/platform/kms/pages/search/storelist/index' },
    ]
  }

    // 加载家政链接
    const loadHouseLinks = () => {
    linkList.value = [
      { label: '家政首页', value: '/home/platform/house/pages/index' },
      { label: '家政分类', value: '/home/platform/house/pages/goods/category/index' },
      { label: '店铺分类', value: '/home/platform/house/pages/search/storecategory/index' },
      { label: '家政购物车', value: '/home/platform/house/pages/cart/index' },
      { label: '商品列表', value: '/home/platform/house/pages/search/goodslist/index' },
      { label: '店铺列表', value: '/home/platform/house/pages/search/storelist/index' },
      { label: '师傅列表', value: '/home/pages/technician/list' },
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
    showType.value = ''
    selectedLink.value = ''
    currentPlatform.value = ''
  }
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
  