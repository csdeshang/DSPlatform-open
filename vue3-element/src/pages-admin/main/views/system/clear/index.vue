<template>
  <div class="system-clear-container">
    <div class="clear-grid">
      <!-- 清除缓存 -->
      <el-card class="clear-card" shadow="hover">
        <div class="card-content">
          <div class="card-icon cache-icon">
            <el-icon :size="48"><Delete /></el-icon>
          </div>
          <div class="card-info">
            <h3 class="card-title">清除缓存</h3>
            <p class="card-desc">清除系统的所有缓存</p>
          </div>
          <div class="card-action">
            <el-button 
              type="primary" 
              :loading="loadingStates.cache"
              @click="handleClearCache"
            >
              立即清除
            </el-button>
          </div>
        </div>
      </el-card>

      <!-- 清除日志 -->
      <el-card class="clear-card" shadow="hover">
        <div class="card-content">
          <div class="card-icon logs-icon">
            <el-icon :size="48"><DocumentDelete /></el-icon>
          </div>
          <div class="card-info">
            <h3 class="card-title">清除日志</h3>
            <p class="card-desc">清除系统的所有日志文件</p>
          </div>
          <div class="card-action">
            <el-button 
              type="success" 
              :loading="loadingStates.logs"
              @click="handleClearLogs"
            >
              立即清除
            </el-button>
          </div>
        </div>
      </el-card>

      <!-- 清除访问日志 -->
      <el-card class="clear-card" shadow="hover">
        <div class="card-content">
          <div class="card-icon access-icon">
            <el-icon :size="48"><View /></el-icon>
          </div>
          <div class="card-info">
            <h3 class="card-title">清除访问日志</h3>
            <p class="card-desc">清除系统的访问日志记录</p>
          </div>
          <div class="card-action">
            <el-button 
              type="warning" 
              :loading="loadingStates.accessLogs"
              @click="handleClearAccessLogs"
            >
              立即清除
            </el-button>
          </div>
        </div>
      </el-card>

      <!-- 清除错误日志 -->
      <el-card class="clear-card" shadow="hover">
        <div class="card-content">
          <div class="card-icon error-icon">
            <el-icon :size="48"><Warning /></el-icon>
          </div>
          <div class="card-info">
            <h3 class="card-title">清除错误日志</h3>
            <p class="card-desc">清除系统的错误日志记录</p>
          </div>
          <div class="card-action">
            <el-button 
              type="danger" 
              :loading="loadingStates.errorLogs"
              @click="handleClearErrorLogs"
            >
              立即清除
            </el-button>
          </div>
        </div>
      </el-card>

      <!-- 清除管理员日志 -->
      <el-card class="clear-card" shadow="hover">
        <div class="card-content">
          <div class="card-icon admin-icon">
            <el-icon :size="48"><User /></el-icon>
          </div>
          <div class="card-info">
            <h3 class="card-title">清除管理员日志</h3>
            <p class="card-desc">清除管理员操作日志记录</p>
          </div>
          <div class="card-action">
            <el-button 
              type="info" 
              :loading="loadingStates.adminLogs"
              @click="handleClearAdminLogs"
            >
              立即清除
            </el-button>
          </div>
        </div>
      </el-card>
    </div>
  </div>
</template>

<script setup lang="ts" name="SystemClearPage">
import { reactive } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Delete, DocumentDelete, View, Warning, User } from '@element-plus/icons-vue'
import { clearCache, clearLogs, clearSysAccessLogs, clearSysErrorLogs, clearAdminLogs } from '../../../api/system/sysClear'

// 加载状态
const loadingStates = reactive({
  cache: false,
  logs: false,
  accessLogs: false,
  errorLogs: false,
  adminLogs: false
})

// 清除缓存
const handleClearCache = async () => {
  try {
    await ElMessageBox.confirm(
      '确定要清除系统缓存吗？此操作不可恢复。',
      '确认清除',
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    )
    
    loadingStates.cache = true
    await clearCache()
    ElMessage.success('缓存清除成功')
  } catch (error: any) {
    if (error !== 'cancel') {
      ElMessage.error(error.message || '清除缓存失败')
    }
  } finally {
    loadingStates.cache = false
  }
}

// 清除日志
const handleClearLogs = async () => {
  try {
    await ElMessageBox.confirm(
      '确定要清除系统日志吗？此操作不可恢复。',
      '确认清除',
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    )
    
    loadingStates.logs = true
    await clearLogs()
    ElMessage.success('日志清除成功')
  } catch (error: any) {
    if (error !== 'cancel') {
      ElMessage.error(error.message || '清除日志失败')
    }
  } finally {
    loadingStates.logs = false
  }
}

// 清除访问日志
const handleClearAccessLogs = async () => {
  try {
    await ElMessageBox.confirm(
      '确定要清除访问日志吗？此操作不可恢复。',
      '确认清除',
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    )
    
    loadingStates.accessLogs = true
    await clearSysAccessLogs()
    ElMessage.success('访问日志清除成功')
  } catch (error: any) {
    if (error !== 'cancel') {
      ElMessage.error(error.message || '清除访问日志失败')
    }
  } finally {
    loadingStates.accessLogs = false
  }
}

// 清除错误日志
const handleClearErrorLogs = async () => {
  try {
    await ElMessageBox.confirm(
      '确定要清除错误日志吗？此操作不可恢复。',
      '确认清除',
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    )
    
    loadingStates.errorLogs = true
    await clearSysErrorLogs()
    ElMessage.success('错误日志清除成功')
  } catch (error: any) {
    if (error !== 'cancel') {
      ElMessage.error(error.message || '清除错误日志失败')
    }
  } finally {
    loadingStates.errorLogs = false
  }
}

// 清除管理员日志
const handleClearAdminLogs = async () => {
  try {
    await ElMessageBox.confirm(
      '确定要清除管理员日志吗？此操作不可恢复。',
      '确认清除',
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    )
    
    loadingStates.adminLogs = true
    await clearAdminLogs()
    ElMessage.success('管理员日志清除成功')
  } catch (error: any) {
    if (error !== 'cancel') {
      ElMessage.error(error.message || '清除管理员日志失败')
    }
  } finally {
    loadingStates.adminLogs = false
  }
}
</script>

<style scoped lang="scss">
.system-clear-container {
  padding: 20px;
}

.clear-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  max-width: 1200px;
}

.clear-card {
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }
  
  :deep(.el-card__body) {
    padding: 0;
  }
}

.card-content {
  padding: 24px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  min-height: 200px;
}

.card-icon {
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  
  &.cache-icon {
    background: rgba(64, 158, 255, 0.1);
    color: #409eff;
  }
  
  &.logs-icon {
    background: rgba(103, 194, 58, 0.1);
    color: #67c23a;
  }
  
  &.access-icon {
    background: rgba(230, 162, 60, 0.1);
    color: #e6a23c;
  }
  
  &.error-icon {
    background: rgba(245, 108, 108, 0.1);
    color: #f56c6c;
  }
  
  &.admin-icon {
    background: rgba(144, 147, 153, 0.1);
    color: #909399;
  }
}

.card-info {
  flex: 1;
  margin-bottom: 20px;
}

.card-title {
  font-size: 18px;
  font-weight: 600;
  color: #303133;
  margin: 0 0 8px 0;
}

.card-desc {
  font-size: 14px;
  color: #909399;
  margin: 0;
  line-height: 1.5;
}

.card-action {
  width: 100%;
  
  .el-button {
    width: 120px;
    height: 36px;
    border-radius: 18px;
    font-weight: 500;
  }
}

// 响应式设计
@media (max-width: 768px) {
  .clear-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  
  .card-content {
    padding: 20px;
    min-height: 180px;
  }
  
  .card-icon {
    width: 60px;
    height: 60px;
    margin-bottom: 12px;
  }
  
  .card-title {
    font-size: 16px;
  }
  
  .card-desc {
    font-size: 13px;
  }
}
</style>