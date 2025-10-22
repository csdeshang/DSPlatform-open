<template>

    <el-card>

        <el-tabs v-model="activeTab">
            <el-tab-pane label="小程序配置" name="setting">
                <Setting />
            </el-tab-pane>

            <el-tab-pane label="小程序订阅消息模板" name="subscribe">
                <Template />
            </el-tab-pane>

        </el-tabs>
       
    </el-card>


</template>


<script setup lang="ts">

import { ref } from 'vue'
const activeTab = ref('setting')

import Setting from './setting.vue'
import Template from './template.vue'


</script>

<style scoped lang="scss">

</style>

