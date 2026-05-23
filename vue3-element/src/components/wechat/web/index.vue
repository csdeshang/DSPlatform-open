<template>

    <el-card>

        <el-tabs v-model="activeTab">
            <el-tab-pane label="网站配置" name="setting">
                <Setting />
            </el-tab-pane>
        </el-tabs>
       
    </el-card>


</template>


<script setup lang="ts">

import { ref } from 'vue'
const activeTab = ref('setting')

import Setting from './setting.vue'



</script>

<style scoped lang="scss">

</style>

