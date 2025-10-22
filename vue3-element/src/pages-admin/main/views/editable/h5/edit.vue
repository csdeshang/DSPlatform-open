<template>
  <div class="flex flex-col h-screen bg-gray-50">
    <!-- 顶部操作栏 -->
    <div class="flex justify-between items-center px-5 py-3 bg-white border-b border-gray-200 shadow-sm">
      <h1 class="text-xl font-bold text-gray-800">可视化装修</h1>
      <div class="flex gap-3">
        <button @click="savePageData"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors flex items-center gap-1"
          :disabled="editableStore.loading">
          <i class="i-carbon-save text-sm"></i> 保存
        </button>
        <button @click="previewTemplate"
          class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors flex items-center gap-1">
          <i class="i-carbon-view text-sm"></i> 预览
        </button>
      </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
      <!-- 左侧组件面板 -->
      <div class="w-[300px] border-r border-gray-200 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-200">
          <h3 class="font-medium text-gray-700">组件库</h3>
        </div>

        <div class="flex-1 overflow-y-auto p-3">
          <div class="mb-4" v-for="(category, cIndex) in componentCategories" :key="cIndex">
            <div class="flex items-center p-2 bg-gray-100 rounded-md mb-2">
              <!-- <Icon :icon="category.icon" :size="20" class="text-xl" /> -->
              <span class="text-gray-700 font-medium">{{ category.title }}</span>
            </div>

            <!-- 改为一行显示3个组件 -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
              <div v-for="(item, index) in category.components" :key="index"
                class="p-2 bg-white border border-gray-200 rounded-md shadow-sm hover:shadow-md transition-shadow cursor-move flex flex-col items-center"
                draggable="true" @dragstart="editableStore.handleDragStart($event, item)"
                @dblclick="editableStore.addComponent(item)">
                <Icon :icon="item.icon" :size="24" class="text-blue-500" />
                <span class="text-gray-700 text-xs text-center leading-8">{{ item.title }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 中间画布区域 -->
      <div class="flex-1 p-5 bg-gray-100 overflow-hidden flex justify-center items-start relative">
        <!-- 加载状态 -->
        <div v-if="editableStore.loading"
          class="absolute inset-0 bg-white bg-opacity-70 flex items-center justify-center z-50">
          <el-loading :visible="true" text="加载中..." />
        </div>

        <!-- 拖拽遮罩层，解决iframe拖拽问题 -->
        <div class="absolute inset-0 z-10" @dragover.prevent @drop="editableStore.handleDrop($event)"
          @click="editableStore.deselectElement" :class="{ 'pointer-events-none': !isDragging }">
        </div>

        <!-- 手机预览区域 -->
        <div class="w-[375px] bg-white border border-gray-300 rounded-2xl shadow-lg flex flex-col relative">
          <div class="p-3 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center text-xs text-gray-600 mb-2">
              <div>9:41 AM</div>
              <div>100%</div>
            </div>
          </div>

          <div>
            <div
              class="text-center text-gray-600 leading-[40px] hover:bg-gray-50 transition-colors cursor-pointer flex items-center justify-center"
              @click="editableStore.deselectElement">
              {{ editableStore.pageConfig.globalSettings.title }}
            </div>
            <el-scrollbar style="height:800px;">
              <iframe ref="previewIframe" :src="previewUrl" frameborder="0"
                style="width: 100%; height: 100%; border: none;" @load="handleIframeLoad">
              </iframe>
            </el-scrollbar>

          </div>

          <div class="flex-1 p-3 hidden">

            <div v-for="(element, index) in editableStore.elementsConfig" :key="index"
              class="relative mb-4 rounded-md overflow-hidden transition-all"
              :class="{ 'ring-2 ring-blue-500': editableStore.selectedElementIndex === index }"
              @click.stop="editableStore.selectElement(index)">

            </div>
          </div>

          <!-- 右侧固定操作按钮 -->
          <div class="absolute -right-[70px] top-1/2 transform -translate-y-1/2 flex flex-col gap-2 z-30">
            <div class="bg-white shadow-lg rounded-md overflow-hidden">
              <button
                @click.stop="editableStore.selectedElementIndex !== null && editableStore.moveElement(editableStore.selectedElementIndex, -1)"
                class="w-[45px] h-[45px] flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-500 transition-colors border-b border-gray-100"
                :class="{ 'opacity-50 cursor-not-allowed': editableStore.selectedElementIndex === null || editableStore.selectedElementIndex === 0 }"
                title="上移">
                <Icon icon="element ArrowUp" :size="18" />
              </button>
              <button
                @click.stop="editableStore.selectedElementIndex !== null && editableStore.moveElement(editableStore.selectedElementIndex, 1)"
                class="w-[45px] h-[45px] flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-500 transition-colors border-b border-gray-100"
                :class="{ 'opacity-50 cursor-not-allowed': editableStore.selectedElementIndex === null || editableStore.selectedElementIndex === editableStore.elementsConfig.length - 1 }"
                title="下移">
                <Icon icon="element ArrowDown" :size="18" />
              </button>
              <button
                @click.stop="editableStore.selectedElementIndex !== null && editableStore.duplicateElement(editableStore.selectedElementIndex)"
                class="w-[45px] h-[45px] flex items-center justify-center text-gray-600 hover:bg-blue-50 hover:text-blue-500 transition-colors border-b border-gray-100"
                :class="{ 'opacity-50 cursor-not-allowed': editableStore.selectedElementIndex === null }" title="复制">
                <Icon icon="element DocumentCopy" :size="18" />
              </button>
              <button
                @click.stop="editableStore.selectedElementIndex !== null && editableStore.removeElement(editableStore.selectedElementIndex)"
                class="w-[45px] h-[45px] flex items-center justify-center text-gray-600 hover:bg-red-50 hover:text-red-500 transition-colors"
                :class="{ 'opacity-50 cursor-not-allowed': editableStore.selectedElementIndex === null }" title="删除">
                <Icon icon="element Delete" :size="18" />
              </button>
            </div>
          </div>

        </div>
      </div>

      <!-- 右侧属性面板 -->
      <div class="w-[450px] bg-white border-l border-gray-200 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">

          <div class="text-lg font-bold">
            {{ editableStore.selectedElementIndex !== null ? editableStore.selectedElement.title : '页面设置' }}
          </div>

          <div class="tab-wrap flex rounded-[50px] bg-gray-100 text-[14px]"
            v-if="editableStore.selectedElementIndex !== null">
            <span class="cursor-pointer rounded-[50px] py-[5px] px-[15px]"
              :class="{ 'bg-primary text-white': editableStore.selectedElementTab === 'content' }"
              @click="editableStore.selectedElementTab = 'content'">内容</span>
            <span class="cursor-pointer rounded-[50px] py-[5px] px-[15px]"
              :class="{ 'bg-primary text-white': editableStore.selectedElementTab === 'style' }"
              @click="editableStore.selectedElementTab = 'style'">样式</span>
          </div>

        </div>

        <div class="flex-1 overflow-y-auto p-4">
          <!-- 动态组件 -->
          <component v-if="editableStore.selectedElement && editableStore.selectedElement.diyName"
            :is="modules[editableStore.selectedElement.diyName]" />


          <!-- 未选择则显示页面设置 -->
          <div v-if="editableStore.selectedElementIndex === null" class="bg-gray-50 rounded-lg p-4 mb-4">

            <el-form-item label="标题名称">
              <el-input v-model="editableStore.pageConfig.globalSettings.title" placeholder="请输入标题" />
            </el-form-item>
            <el-form-item label="页面背景色">
              <el-color-picker v-model="editableStore.pageConfig.globalSettings.backgroundColor" />
            </el-form-item>


          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { useRoute } from 'vue-router';
import { ElMessage, ElLoading } from 'element-plus';
import useEditableStore from '@/stores/modules/editable';
import Icon from '@/components/icon/index.vue';


// 导入组件分类
import h5Categories from './h5-categories.ts';
// 将导入的类别赋值给响应式变量
const componentCategories = ref(h5Categories);

// 获取 editable store
const editableStore = useEditableStore();
// 获取路由
const route = useRoute();

// 拖拽状态
const isDragging = ref(false);

// 动态加载后台自定义组件编辑
const modulesFiles = import.meta.glob('./h5-components/*.vue', { eager: true });
const platformModulesFiles = import.meta.glob('@/pages-admin/platform/**/views/editable/h5-components/*.vue', { eager: true });
platformModulesFiles && Object.assign(modulesFiles, platformModulesFiles);

const modules = {};
for (const [key, value] of Object.entries(modulesFiles)) {
  const moduleName = key.split('/').pop();
  const name = moduleName.split('.')[0];
  modules[name] = value.default;
}

const previewIframe = ref(null);

// 获取商品分类选择限制
import { useConfigStore } from '@/stores/modules/config';
const configStore = useConfigStore();
const previewUrl = ref(''); // 默认设置为10
const h5Url = ref('');

async function fetchPreviewUrl() {
  h5Url.value = await configStore.fetchConfig('website:h5_url');
  previewUrl.value = new URL('/home/pages/editable/preview', h5Url.value).toString();
}
fetchPreviewUrl();


// 处理iframe加载完成
function handleIframeLoad() {
  console.log('iframe已加载，等待预览页面准备就绪信号');
  // 重置预览准备状态
  editableStore.setPreviewReady(false);
}

// 发送数据到预览
function sendDataToPreview() {
  if (previewIframe.value) {
    editableStore.sendDataToPreview(previewIframe.value);
  }
}

// 保存页面数据
async function savePageData() {
  const result = await editableStore.savePageData();

}

// 预览模板
function previewTemplate() {
  window.open(h5Url.value, '_blank');
}


// 监听 pageConfig 变化，自动发送到预览
watch(
  () => [
    editableStore.pageConfig,
    editableStore.previewReady
  ],
  () => {

    // 只有当预览页面准备好时才发送数据
    if (editableStore.previewReady) {

      // 添加延迟，避免频繁更新
      const debounceTimer = setTimeout(() => {
        if (previewIframe.value && previewIframe.value.contentWindow) {
          sendDataToPreview();
        }
      }, 300); // 500ms 延迟

      return () => clearTimeout(debounceTimer); // 清除上一个定时器
    }

  },
  { deep: true } // 深度监听对象变化
);

// 改用函数引用管理事件
const dragStartHandler = () => {
  isDragging.value = true;
};

const dragEndHandler = () => {
  isDragging.value = false;
};

// 组件挂载时使用函数引用添加事件
onMounted(async () => {
  window.addEventListener('message', editableStore.handleMessage);
  window.addEventListener('dragstart', dragStartHandler);
  window.addEventListener('dragend', dragEndHandler);

  // 从URL获取页面ID
  const pageId = route.query.id;
  if (pageId) {
    await editableStore.loadPageData(pageId);
  } else {
    ElMessage.error('未指定页面ID，无法加载页面');
  }
});

// 优化组件卸载时的清理逻辑
onBeforeUnmount(() => {
  // 清理事件监听器
  window.removeEventListener('message', editableStore.handleMessage);

  // 使用函数引用清理拖拽事件，避免闭包问题
  if (dragStartHandler) {
    window.removeEventListener('dragstart', dragStartHandler);
  }

  if (dragEndHandler) {
    window.removeEventListener('dragend', dragEndHandler);
  }

  // 清理预览页面状态
  editableStore.setPreviewReady(false);

  // 清理iframe引用
  previewIframe.value = null;
});
</script>

<style scoped>
.bg-primary {
  background-color: #409eff;
}

iframe::-webkit-scrollbar {
  width: 8px;
  /* 滚动条的宽度 */
}

iframe::-webkit-scrollbar-track {
  background: #f1f1f1;
  /* 滚动条轨道的背景颜色 */
  border-radius: 10px;
  /* 轨道的圆角 */
}

iframe::-webkit-scrollbar-thumb {
  background: #888;
  /* 滚动条的背景颜色 */
  border-radius: 10px;
  /* 滚动条的圆角 */
}

iframe::-webkit-scrollbar-thumb:hover {
  background: #555;
  /* 滚动条悬停时的背景颜色 */
}
</style>
