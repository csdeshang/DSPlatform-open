<template>
  <el-footer class="mb-[100px] mt-[100px]">
    <div class="container mx-auto px-4">
      <div class="flex flex-col items-center">
        <!-- 链接区域 -->
        <div class="flex space-x-8 mb-4">
          <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-300">
            官网
          </a>
          <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-300">
            社区
          </a>
          <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors duration-300">
            文档
          </a>
        </div>
        
        <!-- 版权信息 -->
        <div class="text-gray-500 text-sm">
          Copyright © {{ currentYear }} 版权所有
        </div>
      </div>
    </div>
  </el-footer>
</template>

<script setup lang="ts">
import { computed } from 'vue';


// 获取当前年份
const currentYear = computed(() => new Date().getFullYear());
</script>


