<template>
  <div class="stat-system-container">
    <!-- 加载状态 -->
    <div v-if="loading" class="loading-container">
      <el-skeleton :rows="6" animated />
    </div>

    <template v-else>
      <!-- 原有的数据概览卡片 -->
      <el-card class="overview-card" shadow="hover">
        <template #header>
          <div class="card-header">
            <div class="header-title">
              <Icon icon="element DataAnalysis" class="text-blue-500 mr-2" :size="20" />
              <span class="text-lg font-bold">系统数据概览</span>
            </div>
            <div class="header-actions">
              <el-button type="primary" size="small" @click="refreshAllData">
                <Icon icon="element Refresh" class="mr-1" :size="14" />刷新数据
              </el-button>
            </div>
          </div>
        </template>

        <div class="overview-content">
          <!-- 用户数据统计 -->
          <div class="stats-section">
            <div class="section-header">
              <Icon icon="element User" class="text-blue-500 mr-2" :size="18" />
              <span class="font-medium">用户数据</span>
            </div>

            <div class="stats-grid">
              <!-- 用户总数 -->
              <div class="stat-box">
                <div class="stat-title">用户总数</div>
                <div class="stat-value">{{ userStats.new_user.total }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">今日</span>
                    <span class="value">+{{ userStats.new_user.today }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本周</span>
                    <span class="value">+{{ userStats.new_user.week }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本月</span>
                    <span class="value">+{{ userStats.new_user.month }}</span>
                  </span>
                </div>
              </div>

              <!-- 活跃用户 -->
              <div class="stat-box">
                <div class="stat-title">活跃用户</div>
                <div class="stat-value">{{ userStats.active_user.today }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">昨日</span>
                    <span class="value">{{ userStats.active_user.yesterday }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本周</span>
                    <span class="value">{{ userStats.active_user.week }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本月</span>
                    <span class="value">{{ userStats.active_user.month }}</span>
                  </span>
                </div>
              </div>

              <!-- 用户余额 -->
              <div class="stat-box">
                <div class="stat-title">用户余额</div>
                <div class="stat-value">¥ {{ userStats.user_asset.total_balance }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">总收入</span>
                    <span class="value">¥ {{ userStats.user_asset.total_balance_in }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">总支出</span>
                    <span class="value">¥ {{ userStats.user_asset.total_balance_out }}</span>
                  </span>
                </div>
              </div>

              <!-- 用户积分 -->
              <div class="stat-box">
                <div class="stat-title">用户积分</div>
                <div class="stat-value">{{ userStats.user_asset.total_points }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">获得</span>
                    <span class="value">{{ userStats.user_asset.total_points_in }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">使用</span>
                    <span class="value">{{ userStats.user_asset.total_points_out }}</span>
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- 分隔线 -->
          <div class="divider"></div>

          <!-- 商户数据统计 -->
          <div class="stats-section">
            <div class="section-header">
              <Icon icon="element Shop" class="text-indigo-500 mr-2" :size="18" />
              <span class="font-medium">商户数据</span>
            </div>

            <div class="stats-grid">
              <!-- 商户总数 -->
              <div class="stat-box">
                <div class="stat-title">商户总数</div>
                <div class="stat-value">{{ merchantStats.new_merchant.total }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">今日</span>
                    <span class="value">+{{ merchantStats.new_merchant.today }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本周</span>
                    <span class="value">+{{ merchantStats.new_merchant.week }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">本月</span>
                    <span class="value">+{{ merchantStats.new_merchant.month }}</span>
                  </span>
                </div>
              </div>

              <!-- 待审核商户 -->
              <div class="stat-box">
                <div class="stat-title">待审核商户</div>
                <div class="stat-value">{{ merchantStats.apply_status.wait }}</div>
                <div class="stat-info">
                  <el-tag :type="merchantStats.apply_status.wait > 0 ? 'warning' : 'success'" size="small">
                    {{ merchantStats.apply_status.wait > 0 ? '有待处理项' : '无待处理项' }}
                  </el-tag>
                </div>
              </div>

              <!-- 商户余额 -->
              <div class="stat-box">
                <div class="stat-title">商户余额</div>
                <div class="stat-value">¥ {{ merchantStats.merchant_asset.total_balance }}</div>
                <div class="stat-info">
                  <span class="info-item">
                    <span class="label">收入</span>
                    <span class="value">¥ {{ merchantStats.merchant_asset.total_balance_in }}</span>
                  </span>
                  <span class="info-item">
                    <span class="label">支出</span>
                    <span class="value">¥ {{ merchantStats.merchant_asset.total_balance_out }}</span>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </el-card>

      <!-- 系统配置信息卡片 - 放到最下面 -->
      <el-card class="system-config-card" shadow="hover">
        <template #header>
          <div class="card-header">
            <div class="header-title">
              <Icon icon="element Monitor" class="text-green-500 mr-2" :size="20" />
              <span class="text-lg font-bold">系统配置信息</span>
            </div>
            <div class="header-actions">
              <el-button type="success" size="small" @click="refreshSystemInfo">
                <Icon icon="element Refresh" class="mr-1" :size="14" />刷新
              </el-button>
            </div>
          </div>
        </template>

        <div class="system-config-content">
          <!-- 版本信息 -->
          <div class="config-section">
            <div class="section-header">
              <Icon icon="element InfoFilled" class="text-blue-500 mr-2" :size="18" />
              <span class="font-medium">版本信息</span>
            </div>
            
            <div class="config-grid">
              <div class="config-item">
                <span class="label">当前版本:</span>
                <el-tag type="primary" size="small">{{ systemInfo.version?.current_version || '-' }}</el-tag>
              </div>
              <div class="config-item">
                <span class="label">更新状态:</span>
                <div class="update-status">
                  <el-tag 
                    :type="systemInfo.version?.update_info?.update_available ? 'warning' : 'success'" 
                    size="small"
                    class="mr-2"
                  >
                    {{ systemInfo.version?.update_info?.update_available ? '有更新' : '已是最新' }}
                  </el-tag>
                  <span class="update-message">{{ systemInfo.version?.update_info?.message || '暂无更新信息' }}</span>
                </div>
              </div>
            </div>
            
            <!-- 升级版本列表 -->
            <div v-if="systemInfo.version?.update_info?.upgrade_versions?.length" class="upgrade-versions">
              <div class="upgrade-header">
                <Icon icon="element Download" class="text-green-500 mr-2" :size="16" />
                <span class="font-medium">可升级版本</span>
              </div>
              <div class="upgrade-list">
                <div 
                  v-for="(version, index) in systemInfo.version.update_info.upgrade_versions" 
                  :key="index"
                  class="upgrade-item"
                >
                  <div class="version-info">
                    <el-tag type="success" size="small">{{ version.version }}</el-tag>
                    <span class="version-date">{{ version.date }}</span>
                  </div>
                  <div class="version-actions">
                    <el-button 
                      type="primary" 
                      size="small" 
                      @click="downloadVersion(version)"
                      :disabled="!version.download_url"
                    >
                      下载
                    </el-button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 环境信息 -->
          <div class="config-section">
            <div class="section-header">
              <Icon icon="element Setting" class="text-orange-500 mr-2" :size="18" />
              <span class="font-medium">环境信息</span>
            </div>
            
            <div class="config-grid">
              <div class="config-item">
                <span class="label">PHP版本:</span>
                <span class="value">{{ systemInfo.environment?.php_version || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">框架版本:</span>
                <span class="value">{{ systemInfo.environment?.framework_version || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">数据库版本:</span>
                <span class="value">{{ systemInfo.environment?.database_version || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">服务器软件:</span>
                <span class="value">{{ systemInfo.environment?.server_software || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">服务器系统:</span>
                <span class="value">{{ systemInfo.environment?.server_os || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">域名:</span>
                <span class="value">{{ systemInfo.environment?.domain || '-' }}</span>
              </div>
            </div>
          </div>

          <!-- 时间配置 -->
          <div class="config-section">
            <div class="section-header">
              <Icon icon="element Clock" class="text-purple-500 mr-2" :size="18" />
              <span class="font-medium">时间配置</span>
            </div>
            
            <div class="config-grid">
              <div class="config-item">
                <span class="label">服务器时间:</span>
                <span class="value">{{ systemInfo.environment?.server_time || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">时区:</span>
                <span class="value">{{ systemInfo.environment?.timezone || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">安装时间:</span>
                <span class="value">{{ systemInfo.environment?.install_time || '未知' }}</span>
              </div>
            </div>
          </div>

          <!-- 扩展支持 -->
          <div class="config-section">
            <div class="section-header">
              <Icon icon="element Connection" class="text-cyan-500 mr-2" :size="18" />
              <span class="font-medium">扩展支持</span>
            </div>
            
            <div class="config-grid">
              <div class="config-item">
                <span class="label">Zlib:</span>
                <el-tag :type="systemInfo.environment?.zlib === 'YES' ? 'success' : 'danger'" size="small">
                  {{ systemInfo.environment?.zlib || 'NO' }}
                </el-tag>
              </div>
              <div class="config-item">
                <span class="label">GD:</span>
                <el-tag :type="systemInfo.environment?.gd === 'YES' ? 'success' : 'danger'" size="small">
                  {{ systemInfo.environment?.gd || 'NO' }}
                </el-tag>
              </div>
              <div class="config-item">
                <span class="label">CURL:</span>
                <el-tag :type="systemInfo.environment?.curl === 'YES' ? 'success' : 'danger'" size="small">
                  {{ systemInfo.environment?.curl || 'NO' }}
                </el-tag>
              </div>
              <div class="config-item">
                <span class="label">文件上传:</span>
                <el-tag :type="systemInfo.environment?.file_uploads === 'YES' ? 'success' : 'danger'" size="small">
                  {{ systemInfo.environment?.file_uploads || 'NO' }}
                </el-tag>
              </div>
            </div>
          </div>

          <!-- 性能配置 -->
          <div class="config-section">
            <div class="section-header">
              <Icon icon="element TrendCharts" class="text-indigo-500 mr-2" :size="18" />
              <span class="font-medium">性能配置</span>
            </div>
            
            <div class="config-grid">
              <div class="config-item">
                <span class="label">上传最大文件:</span>
                <span class="value">{{ systemInfo.environment?.upload_max_filesize || '-' }}</span>
              </div>
              <div class="config-item">
                <span class="label">最大执行时间:</span>
                <span class="value">{{ systemInfo.environment?.max_execution_time || '-' }}秒</span>
              </div>
              <div class="config-item">
                <span class="label">内存限制:</span>
                <span class="value">{{ systemInfo.environment?.memory_limit || '-' }}</span>
              </div>
            </div>
          </div>
        </div>
      </el-card>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { ElMessage } from 'element-plus';
import { getStatUserOverview } from '@/pages-admin/main/api/stat/statUser';
import { getStatMerchantOverview } from '@/pages-admin/main/api/stat/statMerchant';
import { getSystemInfo } from '@/pages-admin/main/api/system/sysInfo';
import router from '@/router';
import Icon from '@/components/icon/index.vue';

// 加载状态
const loading = ref(true);

// 是否强制刷新（跳过缓存）
const forceRefresh = ref(false);

// 用户统计数据
const userStats = reactive({
  new_user: {
    total: 0,
    today: 0,
    yesterday: 0,
    week: 0,
    month: 0
  },
  active_user: {
    today: 0,
    yesterday: 0,
    week: 0,
    month: 0
  },
  user_asset: {
    total_balance: "0.0000",
    total_balance_in: "0.0000",
    total_balance_out: "0.0000",
    total_points: "0",
    total_points_in: "0",
    total_points_out: "0"
  }
});

// 商户统计数据
const merchantStats = reactive({
  new_merchant: {
    total: 0,
    today: 0,
    yesterday: 0,
    week: 0,
    month: 0
  },
  merchant_asset: {
    total_balance: "0.0000",
    total_balance_in: "0.0000",
    total_balance_out: "0.0000"
  },
  apply_status: {
    wait: 0
  }
});

// 系统信息
const systemInfo = reactive({
  environment: {},
  version: {}
});




// 获取用户概览数据
const fetchStatUserOverview = async () => {
  try {
    loading.value = true;
    // 添加 forceRefresh 参数，刷新时跳过缓存
    const res = await getStatUserOverview({
      forceRefresh: forceRefresh.value
    });
    if (res.data) {
      Object.assign(userStats, res.data);
    }
  } catch (error) {
    console.error('获取用户统计数据失败:', error);
    ElMessage.error('获取用户统计数据失败');
  }
};

// 获取商户概览数据
const fetchStatMerchantOverview = async () => {
  try {
    // 添加 forceRefresh 参数，刷新时跳过缓存
    const res = await getStatMerchantOverview({
      forceRefresh: forceRefresh.value
    });
    if (res.data) {
      Object.assign(merchantStats, res.data);
    }
  } catch (error) {
    console.error('获取商户统计数据失败:', error);
    ElMessage.error('获取商户统计数据失败');
  } finally {
    loading.value = false;
    // 重置刷新标志
    forceRefresh.value = false;
  }
};

const fetchSystemInfo = async () => {
  try {
    const res = await getSystemInfo();
    if (res.code === 10000 && res.data) {
      Object.assign(systemInfo, res.data);
    } else {
      console.error('获取系统信息失败:', res.message);
      ElMessage.warning('获取系统信息失败');
    }
  } catch (error) {
    console.error('获取系统信息异常:', error);
    ElMessage.error('获取系统信息异常');
  }
};


// 刷新所有数据
const refreshAllData = async () => {
  ElMessage.info('正在刷新数据...');
  // 设置强制刷新标志
  forceRefresh.value = true;
  await Promise.all([fetchStatUserOverview(), fetchStatMerchantOverview()]);
  ElMessage.success('数据已更新');
};

// 刷新系统配置信息
const refreshSystemInfo = async () => {
  ElMessage.info('正在刷新系统配置信息...');
  forceRefresh.value = true; // 强制刷新
  await fetchSystemInfo();
  ElMessage.success('系统配置信息已更新');
};


// 页面挂载时加载数据，默认使用缓存
onMounted(async () => {
  // 初始加载使用缓存，forceRefresh 为 false
  await fetchStatUserOverview();
  await fetchStatMerchantOverview();
  await fetchSystemInfo();
});

// 在 script setup 中只保留 downloadVersion 方法
const downloadVersion = (version: any) => {
  if (version.download_url) {
    window.open(version.download_url, '_blank');
  } else {
    ElMessage.warning('下载链接不可用');
  }
};

</script>

<style lang="scss" scoped>
.stat-system-container {
  padding: 16px 0;

  .loading-container {
    padding: 16px;
    background-color: white;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  }

  // 原有的概览卡片样式保持不变
  .overview-card {
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    margin-bottom: 20px;

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .header-title {
        display: flex;
        align-items: center;
      }
    }

    .overview-content {
      padding: 8px 0;

      .stats-section {
        margin-bottom: 20px;

        &:last-child {
          margin-bottom: 0;
        }

        .section-header {
          display: flex;
          align-items: center;
          margin-bottom: 16px;
          padding-bottom: 8px;
          border-bottom: 1px solid #f0f0f0;
          color: #606266;
        }

        .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
          gap: 16px;

          .stat-box {
            padding: 16px;
            background-color: #f9fafb;
            border-radius: 4px;
            transition: all 0.3s;

            &:hover {
              transform: translateY(-2px);
              box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .stat-title {
              color: #606266;
              font-size: 14px;
              margin-bottom: 6px;
            }

            .stat-value {
              font-size: 24px;
              font-weight: 600;
              color: #303133;
              margin-bottom: 10px;
            }

            .stat-info {
              display: flex;
              flex-wrap: wrap;
              gap: 8px;
              font-size: 12px;

              .info-item {
                display: flex;
                background-color: #ffffff;
                padding: 4px 8px;
                border-radius: 4px;
                border: 1px solid #ebeef5;

                .label {
                  color: #909399;
                  margin-right: 4px;
                }

                .value {
                  color: #409eff;
                  font-weight: 500;
                }
              }
            }
          }
        }
      }

      .divider {
        height: 1px;
        background-color: #ebeef5;
        margin: 24px 0;
      }
    }
  }

  // 系统配置信息卡片样式 - 放到最下面
  .system-config-card {
    box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
    border-radius: 4px;
    margin-bottom: 20px;

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .header-title {
        display: flex;
        align-items: center;
      }
    }

    .system-config-content {
      padding: 8px 0;

      .config-section {
        margin-bottom: 24px;

        &:last-child {
          margin-bottom: 0;
        }

        .section-header {
          display: flex;
          align-items: center;
          margin-bottom: 16px;
          padding-bottom: 8px;
          border-bottom: 1px solid #f0f0f0;
          color: #606266;
        }

        .config-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr); // 固定一行三个
          gap: 16px;

          .config-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background-color: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;

            &:hover {
              background-color: #f3f4f6;
              border-color: #d1d5db;
              transform: translateY(-1px);
            }

            .label {
              color: #6b7280;
              font-size: 14px;
              font-weight: 500;
              white-space: nowrap;
              margin-right: 12px;
            }

            .value {
              color: #111827;
              font-weight: 600;
              font-size: 14px;
              text-align: right;
              flex: 1;
              overflow: hidden;
              text-overflow: ellipsis;
              white-space: nowrap;
            }

            // 更新状态样式直接写在这里
            .update-status {
              display: flex;
              align-items: center;
              flex-wrap: wrap;
              gap: 8px;

              .update-message {
                color: #64748b;
                font-size: 12px;
                line-height: 1.4;
              }
            }
          }
        }
      }

      .upgrade-versions {
        margin-top: 16px;
        padding: 16px;
        background-color: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 6px;

        .upgrade-header {
          display: flex;
          align-items: center;
          margin-bottom: 12px;
          color: #0369a1;
          font-weight: 500;
        }

        .upgrade-list {
          .upgrade-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background-color: white;
            border-radius: 4px;
            margin-bottom: 8px;
            border: 1px solid #e0f2fe;

            &:last-child {
              margin-bottom: 0;
            }

            .version-info {
              display: flex;
              align-items: center;
              gap: 12px;

              .version-date {
                color: #64748b;
                font-size: 12px;
              }
            }

            .version-actions {
              display: flex;
              gap: 8px;
            }
          }
        }
      }
    }
  }
}

@media (max-width: 768px) {
  .stat-system-container {
    .overview-card {
      .overview-content {
        .stats-section {
          .stats-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));

            .stat-box {
              .stat-value {
                font-size: 20px;
              }

              .stat-info {
                .info-item {
                  padding: 3px 6px;
                }
              }
            }
          }
        }
      }
    }

    .system-config-card {
      .system-config-content {
        .config-section {
          .config-grid {
            grid-template-columns: 1fr; // 移动端改为单列
          }
        }
      }
    }
  }
}

</style>
