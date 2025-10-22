<template>
    <div class="p-[20px] mb-[20px] rounded-lg" :style="{ backgroundColor: 'var(--el-bg-color)' }">
        <el-button type="default" @click="goBack">
            <i class="el-icon-arrow-left"></i> 返回
        </el-button>
    </div>
</template>

<script lang="ts" setup>
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();


const goBack = () => {
    router.back(); // 返回到上一页
};
</script>

<style scoped></style>