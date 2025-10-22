<template>
    <div class="merchant-store-switch">
        <el-dropdown @command="handleStoreSwitch">
            <span class="el-dropdown-link">
                {{ currentStore.store_name || '选择店铺' }}
                <el-icon class="el-icon--right">
                    <arrow-down />
                </el-icon>
            </span>
            <template #dropdown>
                <el-dropdown-menu>
                    <el-dropdown-item v-for="store in storeList" :key="store.id" :command="store">
                        
                        {{ store.store_name }}
                        <el-tag type="success" size="small" class="ml-2">{{ getPlatformName(store.platform) }}</el-tag>

                    </el-dropdown-item>
                </el-dropdown-menu>
            </template>
        </el-dropdown>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { ArrowDown } from '@element-plus/icons-vue'
import useUserInfoStore from '@/stores/modules/userInfo'
import { ElMessage } from 'element-plus'

import storage from '@/utils/storage'
const manage_store_id = storage.get('manage_store_id')


const userInfoStore = useUserInfoStore()
// 当前用户下管理的店铺列表，包含商户以及授权管理的店铺
const storeList = userInfoStore.userInfo.manage_store_list

// 计算当前选中的店铺
const currentStore = computed(() => {
    if (!manage_store_id || storeList.length === 0) {
        return { store_id: 0, store_name: '选择店铺' }
    }

    const found = storeList.find(store => store.id === manage_store_id)
    return found || { store_id: 0, store_name: '选择店铺' }
})


// 获取平台名称的函数
const getPlatformName = (platform: string): string => {
    switch (platform) {
        case 'mall':
            return '商城'
        case 'kms':
            return '视频教育'
        case 'house':
            return '上门服务'
        case 'food':
            return '外卖'
        default:
            return '未知'
    }
}



// 处理店铺切换
const handleStoreSwitch = (store: any) => {
    if (manage_store_id !== store.id) {
        // 设置当前店铺id
        storage.set('manage_store_id', store.id , 7 * 24 * 60 * 60)

        // 通知用户已切换店铺
        ElMessage.success(`已切换到店铺: ${store.store_name}`)

        // 刷新页面
        window.location.reload()
    }
}

onMounted(async () => {
    await userInfoStore.fetchUserInfo()
})
</script>

<style scoped>
.merchant-store-switch {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.el-dropdown-link {
    display: flex;
    align-items: center;
    color: var(--el-color-primary);
    font-size: 14px;
}

.el-icon--right {
    margin-left: 5px;
}
</style>
