<template>
  <div class="not-found-container">
    <div class="not-found-content">
      <el-icon class="not-found-icon"><WarningFilled /></el-icon>
      <h1 class="not-found-title">404</h1>
      <div class="not-found-text">抱歉，您访问的页面不存在</div>
      <el-button type="primary" @click="goHome" class="return-button">返回首页</el-button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { WarningFilled } from '@element-plus/icons-vue'

const router = useRouter()

const goHome = () => {
  router.push('/')
}
</script>

<style scoped>
.not-found-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f5f7fa;
}

.not-found-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  max-width: 480px;
  width: 100%;
}

.not-found-icon {
  font-size: 64px;
  color: #f56c6c;
  margin-bottom: 20px;
}

.not-found-title {
  font-size: 72px;
  font-weight: 700;
  color: #303133;
  margin: 0 0 20px 0;
  line-height: 1;
}

.not-found-text {
  font-size: 18px;
  color: #606266;
  margin-bottom: 30px;
}

.return-button {
  padding: 12px 24px;
  font-size: 16px;
}
</style>