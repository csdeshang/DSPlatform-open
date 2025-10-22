<template>
    <div class="dashboard-container">
        <!-- 欢迎信息 -->
        <div class="welcome-section p-6 bg-white rounded-lg shadow-md mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">欢迎回来，{{ userInfoStore.userInfo.username || '管理员' }}
                    </h1>
                    <p class="text-gray-500 mt-2">今天是 {{ formattedDate }}，祝您工作愉快！</p>
                </div>
            </div>
        </div>

        <el-card>
            <el-tabs v-model="activeType">
                <el-tab-pane label="系统管理" name="system">
                    <stat-system />
                </el-tab-pane>

                <el-tab-pane label="多店铺商城" name="mall">
                    <stat-mall />
                </el-tab-pane>
                <el-tab-pane label="视频教育" name="kms">
                    <stat-kms />
                </el-tab-pane>
                <el-tab-pane label="外卖" name="food">
                    <stat-food />
                </el-tab-pane>
                <el-tab-pane label="家政" name="house">
                    <stat-house />
                </el-tab-pane>
            </el-tabs>
        </el-card>









    </div>
</template>

<script setup lang="ts" name="AdminDashboard">
import { reactive, computed, ref } from 'vue';

import statSystem from './stat-system.vue'
import statMall from './stat-mall.vue'
import statKms from './stat-kms.vue'
import statFood from './stat-food.vue'
import statHouse from './stat-house.vue'


import useUserInfoStore from '@/stores/modules/userInfo'
const userInfoStore = useUserInfoStore()


// 当前选中的业务类型
const activeType = ref('system');



// 格式化日期
const formattedDate = computed(() => {
    const now = new Date();
    return now.toLocaleDateString('zh-CN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        weekday: 'long'
    });
});








</script>

<style scoped>
.dashboard-container {
    padding: 20px;
    min-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}
</style>
