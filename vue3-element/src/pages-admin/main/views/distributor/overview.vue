<template>
    <div class="distributor-overview-container">
        <!-- 加载状态 -->
        <div v-if="loading" class="loading-container">
            <el-skeleton :rows="6" animated />
        </div>

        <template v-else>
            <!-- 数据概览卡片 -->
            <el-card class="overview-card" shadow="hover">
                <template #header>
                    <div class="card-header">
                        <div class="header-title">
                            <Icon icon="element UserFilled" class="text-green-500 mr-2" :size="20" />
                            <span class="text-lg font-bold">分销商概览</span>
                        </div>
                        <div class="header-actions">
                            <el-button type="primary" size="small" @click="refreshData">
                                <Icon icon="element Refresh" class="mr-1" :size="14" />刷新数据
                            </el-button>
                        </div>
                    </div>
                </template>

                <div class="overview-content">
                    <!-- 分销商数据统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element User" class="text-blue-500 mr-2" :size="18" />
                            <span class="font-medium">分销商数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 分销商总数 -->
                            <div class="stat-box">
                                <div class="stat-title">分销商总数</div>
                                <div class="stat-value">{{ distributorStats.new_distributor.total }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">+{{ distributorStats.new_distributor.today }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">昨日</span>
                                        <span class="value">+{{ distributorStats.new_distributor.yesterday }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">+{{ distributorStats.new_distributor.week }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">+{{ distributorStats.new_distributor.month }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 分销商审核 -->
                            <div class="stat-box">
                                <div class="stat-title">分销商审核</div>
                                <div class="stat-value">{{ distributorStats.apply_status.wait }}</div>
                                <div class="stat-info">
                                    <el-tag :type="distributorStats.apply_status.wait > 0 ? 'warning' : 'success'" size="small">
                                        {{ distributorStats.apply_status.wait > 0 ? '有待审核分销商' : '无待审核分销商' }}
                                    </el-tag>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 分隔线 -->
                    <div class="divider"></div>

                    <!-- 分销商资产统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Money" class="text-orange-500 mr-2" :size="18" />
                            <span class="font-medium">分销商资产</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 分销商余额 -->
                            <div class="stat-box">
                                <div class="stat-title">总余额</div>
                                <div class="stat-value">¥ {{ distributorStats.distributor_asset.total_distributor_balance }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">累计收入</span>
                                        <span class="value">¥ {{ distributorStats.distributor_asset.total_distributor_balance_in }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">累计支出</span>
                                        <span class="value">¥ {{ distributorStats.distributor_asset.total_distributor_balance_out }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 分隔线 -->
                    <div class="divider"></div>

                    <!-- 分销订单统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Document" class="text-indigo-500 mr-2" :size="18" />
                            <span class="font-medium">分销订单数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 分销订单总数 -->
                            <div class="stat-box">
                                <div class="stat-title">订单总数</div>
                                <div class="stat-value">{{ distributorStats.distributor_order.total_count }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">{{ distributorStats.distributor_order.today_count }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">{{ distributorStats.distributor_order.week_count }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">{{ distributorStats.distributor_order.month_count }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 已结算金额 -->
                            <div class="stat-box">
                                <div class="stat-title">已结算金额</div>
                                <div class="stat-value">¥ {{ distributorStats.distributor_order.total_settled_amount }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.today_settled_amount }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.week_settled_amount }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.month_settled_amount }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 待结算金额 -->
                            <div class="stat-box">
                                <div class="stat-title">待结算金额</div>
                                <div class="stat-value">¥ {{ distributorStats.distributor_order.total_wait_settled_amount }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.today_wait_settled_amount }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.week_wait_settled_amount }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">¥ {{ distributorStats.distributor_order.month_wait_settled_amount }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 分隔线 -->
                    <div class="divider"></div>

                    <!-- 分销商品统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Goods" class="text-green-500 mr-2" :size="18" />
                            <span class="font-medium">分销商品数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 分销商品总数 -->
                            <div class="stat-box">
                                <div class="stat-title">分销商品总数</div>
                                <div class="stat-value">{{ distributorStats.distributor_goods.total_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-card>
        </template>
    </div>
</template>

<script lang="ts" setup>
import { getStatDistributorOverview } from '@/pages-admin/main/api/stat/statDistributor'
import { ElMessage } from 'element-plus';
import { onMounted, reactive, ref } from 'vue';
import Icon from '@/components/icon/index.vue';

const loading = ref(true)
// 是否强制刷新（跳过缓存）
const forceRefresh = ref(false);

// 分销商统计数据
const distributorStats = reactive({
    new_distributor: {
        total: 0,
        today: 0,
        yesterday: 0,
        week: 0,
        month: 0
    },
    sys_status: {
        wait: 0,
        failed: 0
    },
    distributor_asset: {
        total_distributor_balance: "0.0000",
        total_distributor_balance_in: "0.0000",
        total_distributor_balance_out: "0.0000"
    },
    apply_status: {
        wait: 0
    },
    distributor_order: {
        total_count: 0,
        total_settled_amount: 0,
        total_wait_settled_amount: 0,
        today_count: 0,
        today_settled_amount: 0,
        today_wait_settled_amount: 0,
        week_count: 0,
        week_settled_amount: 0,
        week_wait_settled_amount: 0,
        month_count: 0,
        month_settled_amount: 0,
        month_wait_settled_amount: 0
    },
    distributor_goods: {
        total_count: 0
    }
});



// 获取分销商概览数据
const fetchStatDistributorOverview = async () => {
    try {
        loading.value = true;
        const res = await getStatDistributorOverview({
            forceRefresh: forceRefresh.value
        });
        if (res.data) {
            Object.assign(distributorStats, res.data);
        }
    } catch (error) {
        console.error('获取分销商统计数据失败:', error);
        ElMessage.error('获取分销商统计数据失败');
    } finally {
        loading.value = false;
        // 重置刷新标志
        forceRefresh.value = false;
    }
};

// 刷新数据
const refreshData = async () => {
    ElMessage.info('正在刷新数据...');
    loading.value = true;
    // 设置强制刷新标志
    forceRefresh.value = true;
    try {
        await fetchStatDistributorOverview();
        ElMessage.success('数据已更新');
    } catch (error) {
        console.error('刷新数据失败:', error);
        ElMessage.error('刷新数据失败');
    }
};

// 页面挂载时加载数据，默认使用缓存
onMounted(async () => {
    await fetchStatDistributorOverview();
});
</script>

<style lang="scss" scoped>
.distributor-overview-container {
    padding: 16px 0;

    .loading-container {
        padding: 16px;
        background-color: white;
        border-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

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

                        .stat-value-group {
                            display: flex;
                            gap: 16px;
                            margin-bottom: 10px;

                            .stat-value-item {
                                display: flex;
                                flex-direction: column;
                                align-items: center;

                                .value-label {
                                    font-size: 12px;
                                    color: #909399;
                                    margin-bottom: 4px;
                                }

                                .value-number {
                                    font-size: 18px;
                                    font-weight: 600;
                                    color: #303133;
                                }
                            }
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
}
</style>
