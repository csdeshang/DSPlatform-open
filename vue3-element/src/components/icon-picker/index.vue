<template>
  <div class="icon-picker">
    <!-- 触发按钮 - 改为正方形设计 -->
    <div class="icon-picker__trigger" @click="showPicker = true">
      <div v-if="modelValue" class="icon-picker__preview">
        <Icon :icon="modelValue" :size="24" />
      </div>
      <div v-else class="icon-picker__empty-preview">
        <Icon icon="element Plus" :size="16" />
      </div>
    </div>

    <!-- 图标选择器弹窗 -->
    <el-dialog
      v-model="showPicker"
      title="选择图标"
      width="800px"
      destroy-on-close
      :close-on-click-modal="false"
    >
      <div class="icon-picker__header">
        <el-tabs v-model="activeTab">
          <el-tab-pane label="Element Plus 图标" name="element"></el-tab-pane>
          <el-tab-pane label="Iconfont 图标" name="iconfont"></el-tab-pane>
          <el-tab-pane label="SVG 图标" name="svg"></el-tab-pane>
          <el-tab-pane label="本地图标" name="local"></el-tab-pane>
        </el-tabs>
        <div class="search-container">
          <el-input
            v-model="searchText"
            placeholder="搜索图标"
            :prefix-icon="Search"
            clearable
            @input="debounceSearch"
          />
          <el-select v-model="pageSize" class="page-size-select">
            <el-option label="30/页" :value="30" />
            <el-option label="60/页" :value="60" />
            <el-option label="90/页" :value="90" />
            <el-option label="全部" :value="0" />
          </el-select>
        </div>
      </div>

      <div class="icon-picker__content">
        <!-- Element Plus 图标 -->
        <template v-if="activeTab === 'element'">
          <div v-if="filteredElementIcons.length === 0" class="empty-result">
            <el-empty description="未找到匹配的图标" />
          </div>
          <div v-else>
            <div class="icon-picker__grid">
              <div
                v-for="icon in paginatedElementIcons"
                :key="icon"
                class="icon-picker__item"
                :class="{ 'is-active': selectedIcon === `element ${icon}` }"
                @click="selectIcon(`element ${icon}`)"
              >
                <Icon :icon="`element ${icon}`" :size="24" />
                <span class="icon-picker__item-name">{{ icon }}</span>
              </div>
            </div>
            <el-pagination
              v-if="pageSize > 0 && filteredElementIcons.length > pageSize"
              v-model:current-page="currentPage"
              :page-size="pageSize"
              layout="prev, pager, next"
              :total="filteredElementIcons.length"
              class="pagination"
            />
          </div>
        </template>

        <!-- Iconfont 图标 -->
        <template v-else-if="activeTab === 'iconfont'">
          <div v-if="filteredIconfontIcons.length === 0" class="empty-result">
            <el-empty description="未找到匹配的图标" />
          </div>
          <div v-else>
            <div class="icon-picker__grid">
              <div
                v-for="icon in paginatedIconfontIcons"
                :key="icon"
                class="icon-picker__item"
                :class="{ 'is-active': selectedIcon === `iconfont ${icon}` }"
                @click="selectIcon(`iconfont ${icon}`)"
              >
                <Icon :icon="`iconfont ${icon}`" :size="24" />
                <span class="icon-picker__item-name">{{ icon }}</span>
              </div>
            </div>
            <el-pagination
              v-if="pageSize > 0 && filteredIconfontIcons.length > pageSize"
              v-model:current-page="currentPage"
              :page-size="pageSize"
              layout="prev, pager, next"
              :total="filteredIconfontIcons.length"
              class="pagination"
            />
          </div>
        </template>

        <!-- SVG 图标 -->
        <template v-else-if="activeTab === 'svg'">
          <div v-if="isLoadingSvgIcons" class="loading-container">
            <el-skeleton :rows="5" animated />
          </div>
          <div v-else-if="filteredSvgIcons.length === 0" class="empty-result">
            <el-empty description="未找到匹配的图标">
              <template #description>
                <div>
                  <p>未找到匹配的SVG图标</p>
                  <div class="icon-tip">添加SVG图标: /src/assets/icons/svg/</div>
                </div>
              </template>
            </el-empty>
          </div>
          <div v-else>
            <div class="icon-tip mb-2">添加SVG图标: /src/assets/icons/svg/</div>
            <div class="icon-picker__grid">
              <div
                v-for="icon in paginatedSvgIcons"
                :key="icon"
                class="icon-picker__item"
                :class="{ 'is-active': selectedIcon === `svg ${icon}` }"
                @click="selectIcon(`svg ${icon}`)"
              >
                <Icon :icon="`svg ${icon}`" :size="24" />
                <span class="icon-picker__item-name">{{ icon }}</span>
              </div>
            </div>
            <el-pagination
              v-if="pageSize > 0 && filteredSvgIcons.length > pageSize"
              v-model:current-page="currentPage"
              :page-size="pageSize"
              layout="prev, pager, next"
              :total="filteredSvgIcons.length"
              class="pagination"
            />
          </div>
        </template>

        <!-- 本地图标 -->
        <template v-else-if="activeTab === 'local'">
          <div v-if="isLoadingLocalIcons" class="loading-container">
            <el-skeleton :rows="5" animated />
          </div>
          <div v-else-if="filteredLocalIcons.length === 0" class="empty-result">
            <el-empty description="未找到匹配的图标">
              <template #description>
                <div>
                  <p>未找到匹配的本地图标</p>
                  <div class="icon-tip">添加本地图标: /src/assets/icons/local/</div>
                </div>
              </template>
            </el-empty>
          </div>
          <div v-else>
            <div class="icon-tip mb-2">添加本地图标: /src/assets/icons/local/</div>
            <div class="icon-picker__grid">
              <div
                v-for="icon in paginatedLocalIcons"
                :key="icon"
                class="icon-picker__item"
                :class="{ 'is-active': selectedIcon === `local ${icon}` }"
                @click="selectIcon(`local ${icon}`)"
              >
                <Icon :icon="`local ${icon}`" :size="24" />
                <span class="icon-picker__item-name">{{ icon }}</span>
              </div>
            </div>
            <el-pagination
              v-if="pageSize > 0 && filteredLocalIcons.length > pageSize"
              v-model:current-page="currentPage"
              :page-size="pageSize"
              layout="prev, pager, next"
              :total="filteredLocalIcons.length"
              class="pagination"
            />
          </div>
        </template>
      </div>

      <div class="icon-picker__footer">
        <div class="icon-picker__selected">
          <template v-if="selectedIcon">
            已选择：
            <Icon :icon="selectedIcon" :size="20" />
            <span class="ml-1">{{ selectedIcon }}</span>
          </template>
        </div>
        <div>
          <el-button @click="showPicker = false">取消</el-button>
          <el-button type="primary" @click="confirmSelection">确定</el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch } from 'vue';
import * as ElementPlusIcons from '@element-plus/icons-vue';
import { Search } from '@element-plus/icons-vue';
import Icon from '@/components/icon/index.vue';
import { useDebounceFn } from '@vueuse/core';

// 定义属性
const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  iconfontIcons: {
    type: Array,
    default: () => []
  },
  svgIcons: {
    type: Array,
    default: () => []
  },
  localIcons: {
    type: Array,
    default: () => []
  },
  // 是否显示预览（如果为false，则点击图标直接选择）
  showPreview: {
    type: Boolean,
    default: true
  },
  // SVG图标目录
  svgIconsDir: {
    type: String,
    default: '/src/assets/icons/svg/'
  },
  // 本地图标目录
  localIconsDir: {
    type: String,
    default: '/src/assets/icons/local/'
  }
});

// 定义事件
const emit = defineEmits(['update:modelValue', 'change']);

// 状态
const showPicker = ref(false);
const activeTab = ref('element');
const searchText = ref('');
const currentPage = ref(1);
const pageSize = ref(30);
const selectedIcon = ref(props.modelValue || '');
const isLoadingSvgIcons = ref(false);
const isLoadingLocalIcons = ref(false);
const isLoadingIconfontIcons = ref(false);

// 图标列表
const elementIcons = computed(() => {
  return Object.keys(ElementPlusIcons)
    .filter(name => name !== 'default' && !name.startsWith('_'))
    .sort();
});

const iconfontIcons = ref(props.iconfontIcons);

// 简化的iconfont图标加载函数 - 移除DOM提取部分
const loadIconfontIcons = async () => {
  if (iconfontIcons.value.length > 0) return;
  
  isLoadingIconfontIcons.value = true;
  
  try {
    // 从 iconfont.css 文件中提取图标名称
    const response = await fetch('/src/styles/iconfont/iconfont.css');
    const cssText = await response.text();
    
    // 使用正则表达式提取图标名称
    const iconRegex = /\.icon-([^:]+):before/g;
    const matches = cssText.matchAll(iconRegex);
    const iconNames = Array.from(matches).map(match => `icon-${match[1]}`);
    
    // 更新图标列表
    iconfontIcons.value = iconNames.sort();
    console.log(`Loaded ${iconfontIcons.value.length} iconfont icons`);
  } catch (error) {
    console.error('Failed to load iconfont icons:', error);
    // 使用默认图标
    iconfontIcons.value = [
      'icon-user', 'icon-setting', 'icon-home', 'icon-dashboard',
      'icon-menu', 'icon-chart', 'icon-message', 'icon-notification'
    ];
    console.log('Using default iconfont icons');
  } finally {
    isLoadingIconfontIcons.value = false;
  }
};

const svgIcons = ref(props.svgIcons);
const localIcons = ref(props.localIcons);

// 监听标签页变化
watch(activeTab, (newTab) => {
  // 重置分页
  currentPage.value = 1;
  
  // 加载对应标签页的图标
  if (newTab === 'svg' && svgIcons.value.length === 0) {
    loadSvgIcons();
  } else if (newTab === 'local' && localIcons.value.length === 0) {
    loadLocalIcons();
  } else if (newTab === 'iconfont' && iconfontIcons.value.length === 0) {
    loadIconfontIcons();
  }
});

// 搜索过滤
const debounceSearch = useDebounceFn(() => {
  currentPage.value = 1;
}, 300);

// 过滤后的图标列表
const filteredElementIcons = computed(() => {
  if (!searchText.value) return elementIcons.value;
  return elementIcons.value.filter(icon => 
    icon.toLowerCase().includes(searchText.value.toLowerCase())
  );
});

const filteredIconfontIcons = computed(() => {
  if (!searchText.value) return iconfontIcons.value;
  return iconfontIcons.value.filter(icon => 
    icon.toLowerCase().includes(searchText.value.toLowerCase())
  );
});

const filteredSvgIcons = computed(() => {
  if (!searchText.value) return svgIcons.value;
  return svgIcons.value.filter(icon => 
    icon.toLowerCase().includes(searchText.value.toLowerCase())
  );
});

const filteredLocalIcons = computed(() => {
  if (!searchText.value) return localIcons.value;
  return localIcons.value.filter(icon => 
    icon.toLowerCase().includes(searchText.value.toLowerCase())
  );
});

// 分页后的图标列表
const paginatedElementIcons = computed(() => {
  if (pageSize.value === 0) return filteredElementIcons.value;
  const start = (currentPage.value - 1) * pageSize.value;
  return filteredElementIcons.value.slice(start, start + pageSize.value);
});

const paginatedIconfontIcons = computed(() => {
  if (pageSize.value === 0) return filteredIconfontIcons.value;
  const start = (currentPage.value - 1) * pageSize.value;
  return filteredIconfontIcons.value.slice(start, start + pageSize.value);
});

const paginatedSvgIcons = computed(() => {
  if (pageSize.value === 0) return filteredSvgIcons.value;
  const start = (currentPage.value - 1) * pageSize.value;
  return filteredSvgIcons.value.slice(start, start + pageSize.value);
});

const paginatedLocalIcons = computed(() => {
  if (pageSize.value === 0) return filteredLocalIcons.value;
  const start = (currentPage.value - 1) * pageSize.value;
  return filteredLocalIcons.value.slice(start, start + pageSize.value);
});

// 获取图标名称
const getIconName = computed(() => {
  if (!props.modelValue) return '';
  const parts = props.modelValue.split(' ');
  return parts.length > 1 ? parts.slice(1).join(' ') : '';
});

// 选择图标
const selectIcon = (icon) => {
  selectedIcon.value = icon;
  
  // 如果不需要预览，直接选择
  if (!props.showPreview) {
    confirmSelection();
  }
};

// 确认选择
const confirmSelection = () => {
  emit('update:modelValue', selectedIcon.value);
  emit('change', selectedIcon.value);
  showPicker.value = false;
};

// 监听对话框打开
watch(showPicker, (isOpen) => {
  if (isOpen) {
    // 打开对话框时，设置选中图标为当前值
    selectedIcon.value = props.modelValue || '';
    
    // 如果有值，自动切换到对应标签页
    if (props.modelValue) {
      const type = props.modelValue.split(' ')[0];
      if (['element', 'iconfont', 'svg', 'local'].includes(type)) {
        activeTab.value = type;
      }
    }
  }
});

// 预加载的SVG图标
const svgIconModules = import.meta.glob('/src/assets/icons/svg/*.svg', { eager: true });
const preloadedSvgIcons = computed(() => {
  return Object.keys(svgIconModules).map(path => {
    return path.split('/').pop()?.replace('.svg', '') || '';
  });
});

// 预加载的本地图标
const localIconModules = import.meta.glob('/src/assets/icons/local/*.(png|jpg|jpeg|gif|svg)', { eager: true });
const preloadedLocalIcons = computed(() => {
  return Object.keys(localIconModules).map(path => {
    return path.split('/').pop() || '';
  });
});

// 修改加载SVG图标的方法
const loadSvgIcons = async () => {
  if (svgIcons.value.length > 0) return;
  
  isLoadingSvgIcons.value = true;
  
  try {
    // 使用预加载的图标
    if (preloadedSvgIcons.value.length > 0) {
      svgIcons.value = preloadedSvgIcons.value;
    } else {
      console.warn('No SVG icons found in the specified directory');
      svgIcons.value = [];
    }
  } catch (error) {
    console.error('Failed to load SVG icons:', error);
    svgIcons.value = [];
  } finally {
    isLoadingSvgIcons.value = false;
  }
};

// 修改加载本地图标的方法
const loadLocalIcons = async () => {
  if (localIcons.value.length > 0) return;
  
  isLoadingLocalIcons.value = true;
  
  try {
    // 使用预加载的图标
    if (preloadedLocalIcons.value.length > 0) {
      localIcons.value = preloadedLocalIcons.value;
    } else {
      console.warn('No local icons found in the specified directory');
      localIcons.value = [];
    }
  } catch (error) {
    console.error('Failed to load local icons:', error);
    localIcons.value = [];
  } finally {
    isLoadingLocalIcons.value = false;
  }
};

// 初始化
onMounted(() => {
  // 如果有默认值，自动切换到对应的标签页
  if (props.modelValue) {
    const type = props.modelValue.split(' ')[0];
    if (['element', 'iconfont', 'svg', 'local'].includes(type)) {
      activeTab.value = type;
    }
    
    // 设置初始选中图标
    selectedIcon.value = props.modelValue;
  }
  
  // 预加载图标
  if (activeTab.value === 'svg') {
    loadSvgIcons();
  } else if (activeTab.value === 'local') {
    loadLocalIcons();
  } else if (activeTab.value === 'iconfont') {
    loadIconfontIcons();
  }
});
</script>

<style scoped>
.icon-picker {
  display: inline-block;
}

.icon-picker__trigger {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* 正方形预览区域 */
.icon-picker__preview {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  background-color: #f5f7fa;
  transition: all 0.3s;
}

.icon-picker__preview:hover {
  border-color: #409eff;
  background-color: #ecf5ff;
}

/* 空预览区域 */
.icon-picker__empty-preview {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  border: 1px dashed #dcdfe6;
  border-radius: 4px;
  background-color: #f5f7fa;
  color: #909399;
  transition: all 0.3s;
}

.icon-picker__empty-preview:hover {
  border-color: #409eff;
  color: #409eff;
  background-color: #ecf5ff;
}

.icon-picker__header {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 16px;
}

.search-container {
  display: flex;
  gap: 10px;
}

.page-size-select {
  width: 100px;
}

.icon-picker__content {
  height: 400px;
  overflow-y: auto;
  border: 1px solid #ebeef5;
  border-radius: 4px;
  padding: 16px;
}

.icon-picker__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 16px;
  margin-bottom: 16px;
}

.icon-picker__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 12px 8px;
  border: 1px solid #ebeef5;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s;
}

.icon-picker__item:hover {
  background-color: #f5f7fa;
  border-color: #dcdfe6;
}

.icon-picker__item.is-active {
  background-color: #ecf5ff;
  border-color: #409eff;
  color: #409eff;
}

.icon-picker__item-name {
  margin-top: 8px;
  font-size: 12px;
  text-align: center;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}

.icon-picker__footer {
  margin-top: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.icon-picker__selected {
  display: flex;
  align-items: center;
  color: #606266;
  font-size: 14px;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 16px;
}

.empty-result {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
}

.loading-container {
  padding: 20px;
}

.icon-tip {
  font-size: 12px;
  color: #909399;
  padding: 4px 0;
}

.mb-2 {
  margin-bottom: 8px;
}
</style> 