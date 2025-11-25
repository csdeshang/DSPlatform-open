<template>
  <div>
    <!-- 内容设置 -->
    <div v-show="store.selectedElementTab === 'content'" class="w-full">

      <el-form label-width="120px" class="px-[5px]">
        <el-form-item label="固定高度">
          <el-switch v-model="store.selectedElement.settings.cubeSetting.is_auto_height" />
        </el-form-item>
        <el-form-item label="高度" v-if="store.selectedElement.settings.cubeSetting.is_auto_height">
          <el-slider v-model="store.selectedElement.settings.cubeSetting.height" :min="50" :max="1000" :step="10"
            show-input size="small" />
        </el-form-item>
      </el-form>

      <!-- 布局选择卡片 -->
      <el-card shadow="never" class="!border-none w-full mt-2">
        <el-form-item label="布局风格">
          <el-radio-group v-model="store.selectedElement.settings.cubeSetting.layout" class="flex flex-wrap">
            <el-radio value="style1" border class="mb-2 w-[100px]">单个</el-radio>
            <el-radio value="style2" border class="mb-2 w-[100px]">1行2个</el-radio>
            <el-radio value="style3" border class="mb-2 w-[100px]">1行3个</el-radio>
            <el-radio value="style4" border class="mb-2 w-[100px]">1行4个</el-radio>
            <el-radio value="style5" border class="mb-2 w-[100px]">1左2右</el-radio>
            <el-radio value="style6" border class="mb-2 w-[100px]">1左3右</el-radio>
            <el-radio value="style7" border class="mb-2 w-[100px]">2左1右</el-radio>
            <el-radio value="style8" border class="mb-2 w-[100px]">3左1右</el-radio>
            <el-radio value="style9" border class="mb-2 w-[100px]">1上2下</el-radio>
            <el-radio value="style10" border class="mb-2 w-[100px]">1上3下</el-radio>
            <el-radio value="style11" border class="mb-2 w-[100px]">2上1下</el-radio>
            <el-radio value="style12" border class="mb-2 w-[100px]">3上1下</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-card>

      <!-- 图片预览卡片 -->
      <el-card shadow="never" class="!border-none w-full mt-2">
        <!-- 单个图片布局 -->
        <div class="w-full cursor-pointer" v-if="currentLayout === 'style1'">
          <div @click="activeIndex = 0" class="w-full bg-[#f7f7f7] box text-[#999] text-center"
            :class="{ active: activeIndex === 0 }">
            <div v-if="!getImageContent(0).image" class="h-[260px] leading-[260px]">
              宽度750
            </div>
            <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(0).image)"
              v-if="getImageContent(0).image"></el-image>
          </div>
        </div>

        <!-- 1行2个布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style2'">
          <div @click="activeIndex = 0" class="w-[50%]">
            <div :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image"
                class="h-[260px] leading-[260px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度375
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
          </div>
          <div @click="activeIndex = 1" class="w-[50%]">
            <div :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image"
                class="h-[260px] leading-[260px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度375
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 1行3个布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style3'">
          <div class="w-1/3" @click="activeIndex = 0">
            <div :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度250
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
          </div>
          <div class="w-1/3" @click="activeIndex = 1">
            <div :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度250
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
          </div>
          <div class="w-1/3" @click="activeIndex = 2">
            <div :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度250
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 1行4个布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style4'">
          <div class="w-1/4" @click="activeIndex = 0">
            <div :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度187
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
          </div>
          <div class="w-1/4" @click="activeIndex = 1">
            <div :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度187
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
          </div>
          <div class="w-1/4" @click="activeIndex = 2">
            <div :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度187
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
          <div class="w-1/4" @click="activeIndex = 3">
            <div :class="{ active: activeIndex === 3 }">
              <div v-if="!getImageContent(3).image"
                class="h-[158px] leading-[158px] box text-[#999] bg-[#f7f7f7] text-center">
                宽度187
              </div>
              <el-image fit="cover" class="w-full" :src="formatImageUrl(getImageContent(3).image)"
                v-if="getImageContent(3).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 1左2右布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style5'">
          <div @click="activeIndex = 0" class="w-1/2 bg-[#f7f7f7] box-1 h-[260px] text-[#999] text-center"
            :class="{ active: activeIndex === 0 }">
            <div v-if="!getImageContent(0).image">宽度375</div>
            <el-image class="w-full h-[260px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
              v-if="getImageContent(0).image"></el-image>
          </div>
          <div class="w-1/2">
            <div @click="activeIndex = 1" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 1左3右布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style6'">
          <div @click="activeIndex = 0" class="w-1/2 bg-[#f7f7f7] box-1 h-[260px] text-[#999] text-center"
            :class="{ active: activeIndex === 0 }">
            <div v-if="!getImageContent(0).image">宽度375</div>
            <el-image class="w-full h-[260px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
              v-if="getImageContent(0).image"></el-image>
          </div>
          <div class="w-1/2">
            <div @click="activeIndex = 1" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
            <div @click="activeIndex = 3" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 3 }">
              <div v-if="!getImageContent(3).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(3).image)"
                v-if="getImageContent(3).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 2左1右布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style7'">
          <div class="w-1/2">
            <div @click="activeIndex = 0" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
            <div @click="activeIndex = 1" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
          </div>
          <div @click="activeIndex = 2" class="w-1/2 bg-[#f7f7f7] box-1 h-[260px] text-[#999] text-center"
            :class="{ active: activeIndex === 2 }">
            <div v-if="!getImageContent(2).image">宽度375</div>
            <el-image class="w-full h-[260px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
              v-if="getImageContent(2).image"></el-image>
          </div>
        </div>

        <!-- 3左1右布局 -->
        <div class="flex w-full cursor-pointer" v-if="currentLayout === 'style8'">
          <div class="w-1/2">
            <div @click="activeIndex = 0" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
            <div @click="activeIndex = 1" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-full bg-[#f7f7f7] box-2 h-[86px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度375</div>
              <el-image class="w-full h-[86px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
          <div @click="activeIndex = 3" class="w-1/2 bg-[#f7f7f7] box-1 h-[260px] text-[#999] text-center"
            :class="{ active: activeIndex === 3 }">
            <div v-if="!getImageContent(3).image">宽度375</div>
            <el-image class="w-full h-[260px]" fit="cover" :src="formatImageUrl(getImageContent(3).image)"
              v-if="getImageContent(3).image"></el-image>
          </div>
        </div>

        <!-- 1上2下布局 -->
        <div class="w-full cursor-pointer" v-if="currentLayout === 'style9'">
          <div class="w-full">
            <div @click="activeIndex = 0" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度750</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
          </div>
          <div class="flex w-full">
            <div @click="activeIndex = 1" class="w-1/2 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-1/2 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 1上3下布局 -->
        <div class="w-full cursor-pointer" v-if="currentLayout === 'style10'">
          <div class="w-full">
            <div @click="activeIndex = 0" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度750</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
          </div>
          <div class="flex w-full">
            <div @click="activeIndex = 1" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
            <div @click="activeIndex = 3" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 3 }">
              <div v-if="!getImageContent(3).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(3).image)"
                v-if="getImageContent(3).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 2上1下布局 -->
        <div class="w-full cursor-pointer" v-if="currentLayout === 'style11'">
          <div class="flex w-full">
            <div @click="activeIndex = 0" class="w-1/2 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
            <div @click="activeIndex = 1" class="w-1/2 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度375</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
          </div>
          <div class="w-full">
            <div @click="activeIndex = 2" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度750</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
        </div>

        <!-- 3上1下布局 -->
        <div class="w-full cursor-pointer" v-if="currentLayout === 'style12'">
          <div class="flex w-full">
            <div @click="activeIndex = 0" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 0 }">
              <div v-if="!getImageContent(0).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(0).image)"
                v-if="getImageContent(0).image"></el-image>
            </div>
            <div @click="activeIndex = 1" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 1 }">
              <div v-if="!getImageContent(1).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(1).image)"
                v-if="getImageContent(1).image"></el-image>
            </div>
            <div @click="activeIndex = 2" class="w-1/3 bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 2 }">
              <div v-if="!getImageContent(2).image">宽度250</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(2).image)"
                v-if="getImageContent(2).image"></el-image>
            </div>
          </div>
          <div class="w-full">
            <div @click="activeIndex = 3" class="w-full bg-[#f7f7f7] box-2 h-[130px] text-[#999] text-center"
              :class="{ active: activeIndex === 3 }">
              <div v-if="!getImageContent(3).image">宽度750</div>
              <el-image class="w-full h-[130px]" fit="cover" :src="formatImageUrl(getImageContent(3).image)"
                v-if="getImageContent(3).image"></el-image>
            </div>
          </div>
        </div>
      </el-card>

      <!-- 图片设置卡片 -->
      <el-card shadow="never" class="!border-none w-full mt-2">
        <div class="bg-[#f9f9f9] p-4 w-full">
          <el-form-item label="图片" class="mt-[18px]">
            <PickerImage v-model="store.selectedElement.settings.imageList[activeIndex].image" :limit="1"
              type="image" />
          </el-form-item>
          <el-form-item class="mt-[18px]" label="图片链接">
            <UniappLink v-model="store.selectedElement.settings.imageList[activeIndex].link" />
          </el-form-item>
        </div>
      </el-card>
    </div>

    <!-- 样式设置 -->
    <div v-show="store.selectedElementTab === 'style'">
      <BaseStyles />
    </div>
  </div>
</template>

<script setup>
import { watch, computed, ref } from 'vue';
import BaseStyles from './base-styles.vue';
import useEditableStore from '@/stores/modules/editable';
import PickerImage from '@/components/attachment/picker-image.vue'
import UniappLink from './editors/uniapp-link/index.vue'
import { formatImageUrl } from '@/utils/image'

// 获取状态管理
const store = useEditableStore();

// 当前选中的图片索引
const activeIndex = ref(0);

// 获取当前布局
const currentLayout = computed(() =>
  store.selectedElement?.settings?.cubeSetting?.layout || 'style1'
);

// 每种布局需要的图片数量
const imageCountMap = {
  style1: 1,  // 单个
  style2: 2,  // 1行2个
  style3: 3,  // 1行3个
  style4: 4,  // 1行4个
  style5: 3,  // 1左2右
  style6: 4,  // 1左3右
  style7: 3,  // 2左1右
  style8: 4,  // 3左1右
  style9: 3,  // 1上2下
  style10: 4, // 1上3下
  style11: 3, // 2上1下
  style12: 4  // 3上1下
};

// 获取图片，安全地处理不存在的索引
const getImageContent = (index) => {
  const list = store.selectedElement?.settings?.imageList || [];
  return list[index] || { image: '', link: '' };
};

// 初始化图片列表
const initImageList = () => {
  const count = imageCountMap[currentLayout.value] || 1;
  const currentList = store.selectedElement.settings.imageList || [];

  // 如果当前数量与需要的不一致，调整列表
  if (currentList.length !== count) {
    const newList = [];

    // 保留现有图片
    for (let i = 0; i < Math.min(count, currentList.length); i++) {
      newList.push(currentList[i]);
    }

    // 添加缺少的图片
    for (let i = currentList.length; i < count; i++) {
      newList.push({ image: '', link: '' });
    }

    store.selectedElement.settings.imageList = newList;

    // 确保activeIndex在有效范围内
    if (activeIndex.value >= newList.length) {
      activeIndex.value = 0;
    }
  }
};

// 初始化数据
const initialFormData = {
  cubeSetting: {
    // style1 单个 style2 1行2个 style3 1行3个 style4 1行4个 style5 1左2右 style6 1左3右 style7 2左1右 style8 3左1右 style9 1上2下 style10 1上3下 style11 2上1下 style12 3上1下
    layout: 'style1',
    // 是否固定高度
    is_auto_height: false,
    // 高度
    height: 300,
  },
  imageList: [{ image: '', link: '' }],
};

// 监听布局变化，调整图片列表
watch(() => currentLayout.value, () => {
  initImageList();
}, { immediate: true, deep: false });

// 监听及初始化
watch(() => store.selectedElement?.settings, (newVal) => {
  if (!newVal || Object.keys(newVal).length === 0) {
    store.selectedElement.settings = initialFormData;
  } else {
    // 确保imageList存在并且是数组
    if (!Array.isArray(newVal.imageList)) {
      store.selectedElement.settings.imageList = [{ image: '', link: '' }];
    }
    // 确保cubeSetting存在
    if (!newVal.cubeSetting) {
      store.selectedElement.settings.cubeSetting = { layout: 'style1' };
    }

    // 初始化图片列表
    initImageList();
  }
}, { immediate: true, deep: false });
</script>

<style scoped>
.box {
  text-align: center;
  border: 1px solid #ececec;
}

.box-1 {
  text-align: center;
  border: 1px solid #ececec;
  display: flex;
  align-items: center;
  justify-content: center;
}

.box-2 {
  text-align: center;
  border: 1px solid #ececec;
  display: flex;
  align-items: center;
  justify-content: center;
}

.active {
  border: 2px solid #409EFF;
  box-shadow: 0 0 5px rgba(64, 158, 255, 0.3);
}
</style>