<template>
    <div class="stat-house-container">
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
                            <Icon icon="element ShoppingBag" class="text-green-500 mr-2" :size="20" />
                            <span class="text-lg font-bold">概览</span>
                        </div>
                        <div class="header-actions">
                            <el-button type="primary" size="small" @click="refreshAllData">
                                <Icon icon="element Refresh" class="mr-1" :size="14" />刷新数据
                            </el-button>
                        </div>
                    </div>
                </template>

                <div class="overview-content">
                    <!-- 商品数据统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Goods" class="text-blue-500 mr-2" :size="18" />
                            <span class="font-medium">商品数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 商品总数 -->
                            <div class="stat-box">
                                <div class="stat-title">商品总数</div>
                                <div class="stat-value">{{ goodsStats.new_goods.total }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">+{{ goodsStats.new_goods.today }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">+{{ goodsStats.new_goods.week }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">+{{ goodsStats.new_goods.month }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 商品状态 -->
                            <div class="stat-box">
                                <div class="stat-title">商品状态</div>
                                <div class="stat-value-group">
                                    <div class="stat-value-item">
                                        <span class="value-label">待审核</span>
                                        <span class="value-number">{{ goodsStats.sys_status.wait }}</span>
                                    </div>
                                    <div class="stat-value-item">
                                        <span class="value-label">审核失败</span>
                                        <span class="value-number">{{ goodsStats.sys_status.failed }}</span>
                                    </div>
                                </div>
                                <div class="stat-info">
                                    <el-tag v-if="goodsStats.sys_status.wait > 0" type="warning" size="small">
                                        有待审核商品
                                    </el-tag>
                                    <el-tag v-else type="success" size="small">
                                        无待审核商品
                                    </el-tag>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 分隔线 -->
                    <div class="divider"></div>

                    <!-- 订单数据统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Document" class="text-orange-500 mr-2" :size="18" />
                            <span class="font-medium">订单数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 订单总数 -->
                            <div class="stat-box">
                                <div class="stat-title">订单总数</div>
                                <div class="stat-value">{{ orderStats.new_order.total }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">+{{ orderStats.new_order.today }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">昨日</span>
                                        <span class="value">+{{ orderStats.new_order.yesterday }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">+{{ orderStats.new_order.month }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 订单金额 -->
                            <div class="stat-box">
                                <div class="stat-title">订单金额</div>
                                <div class="stat-value">¥ {{ orderStats.order_amount.total }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">¥ {{ orderStats.order_amount.today }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">昨日</span>
                                        <span class="value">¥ {{ orderStats.order_amount.yesterday }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">¥ {{ orderStats.order_amount.month }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 订单状态 -->
                            <div class="stat-box">
                                <div class="stat-title">订单状态</div>
                                <div class="stat-value-group">
                                    <div class="stat-value-item">
                                        <span class="value-label">待付款</span>
                                        <span class="value-number">{{ orderStats.order_status.pending }}</span>
                                    </div>
                                    <div class="stat-value-item">
                                        <span class="value-label">已支付</span>
                                        <span class="value-number">{{ orderStats.order_status.paid }}</span>
                                    </div>
                                    <div class="stat-value-item">
                                        <span class="value-label">已接单</span>
                                        <span class="value-number">{{ orderStats.order_status.accepted }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 分隔线 -->
                    <div class="divider"></div>

                    <!-- 店铺数据统计 -->
                    <div class="stats-section">
                        <div class="section-header">
                            <Icon icon="element Shop" class="text-indigo-500 mr-2" :size="18" />
                            <span class="font-medium">店铺数据</span>
                        </div>

                        <div class="stats-grid">
                            <!-- 店铺总数 -->
                            <div class="stat-box">
                                <div class="stat-title">店铺总数</div>
                                <div class="stat-value">{{ storeStats.new_store.total }}</div>
                                <div class="stat-info">
                                    <span class="info-item">
                                        <span class="label">今日</span>
                                        <span class="value">+{{ storeStats.new_store.today }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本周</span>
                                        <span class="value">+{{ storeStats.new_store.week }}</span>
                                    </span>
                                    <span class="info-item">
                                        <span class="label">本月</span>
                                        <span class="value">+{{ storeStats.new_store.month }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- 店铺审核 -->
                            <div class="stat-box">
                                <div class="stat-title">店铺审核</div>
                                <div class="stat-value">{{ storeStats.apply_status.wait }}</div>
                                <div class="stat-info">
                                    <el-tag :type="storeStats.apply_status.wait > 0 ? 'warning' : 'success'" size="small">
                                        {{ storeStats.apply_status.wait > 0 ? '有待审核店铺' : '无待审核店铺' }}
                                    </el-tag>
                                </div>
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
import { getStatGoodsOverview } from '@/pages-admin/main/api/stat/statGoods';
import { getStatOrderOverview } from '@/pages-admin/main/api/stat/statOrder';
import { getStatStoreOverview } from '@/pages-admin/main/api/stat/statStore';
import Icon from '@/components/icon/index.vue';

// 平台类型
const platform = ref('house');

// 加载状态
const loading = ref(true);

// 是否强制刷新（跳过缓存）
const forceRefresh = ref(false);

// 商品统计数据
const goodsStats = reactive({
    new_goods: {
        total: 0,
        today: 0,
        yesterday: 0,
        week: 0,
        month: 0
    },
    sys_status: {
        wait: 0,
        failed: 0
    }
});

// 订单统计数据
const orderStats = reactive({
    new_order: {
        total: 0,
        today: 0,
        yesterday: 0,
        week: 0,
        month: 0
    },
    order_amount: {
        total: 0,
        today: 0,
        yesterday: 0,
        week: 0,
        month: 0
    },
    order_status: {
        pending: 0,
        paid: 0,
        accepted: 0
    }
});

// 店铺统计数据
const storeStats = reactive({
    new_store: {
        total: 0,
        today: 0,
        yesterday: 0,
        week: 0,
        month: 0
    },
    apply_status: {
        wait: 0
    }
});



// 获取商品概览数据
const fetchStatGoodsOverview = async () => {
    try {
        loading.value = true;
        const res = await getStatGoodsOverview({
            platform: platform.value,
            forceRefresh: forceRefresh.value
        });
        if (res.data) {
            Object.assign(goodsStats, res.data);
        }
    } catch (error) {
        console.error('获取商品统计数据失败:', error);
        ElMessage.error('获取商品统计数据失败');
    }
};

// 获取订单概览数据
const fetchStatOrderOverview = async () => {
    try {
        const res = await getStatOrderOverview({
            platform: platform.value,
            forceRefresh: forceRefresh.value
        });
        if (res.data) {
            Object.assign(orderStats, res.data);
        }
    } catch (error) {
        console.error('获取订单统计数据失败:', error);
        ElMessage.error('获取订单统计数据失败');
    }
};

// 获取店铺概览数据
const fetchStatStoreOverview = async () => {
    try {
        const res = await getStatStoreOverview({
            platform: platform.value,
            forceRefresh: forceRefresh.value
        });
        if (res.data) {
            Object.assign(storeStats, res.data);
        }
    } catch (error) {
        console.error('获取店铺统计数据失败:', error);
        ElMessage.error('获取店铺统计数据失败');
    } finally {
        loading.value = false;
        // 重置刷新标志
        forceRefresh.value = false;
    }
};

// 刷新所有数据
const refreshAllData = async () => {
    ElMessage.info('正在刷新数据...');
    loading.value = true;
    // 设置强制刷新标志
    forceRefresh.value = true;
    try {
        await Promise.all([
            fetchStatGoodsOverview(),
            fetchStatOrderOverview(),
            fetchStatStoreOverview()
        ]);
        ElMessage.success('数据已更新');
    } catch (error) {
        console.error('刷新数据失败:', error);
        ElMessage.error('刷新数据失败');
    } finally {
        loading.value = false;
    }
};

// 页面挂载时加载数据，默认使用缓存
onMounted(async () => {
    // 初始加载使用缓存，forceRefresh 为 false
    await fetchStatGoodsOverview();
    await fetchStatOrderOverview();
    await fetchStatStoreOverview();
});
</script>

<style lang="scss" scoped>
.stat-house-container {
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