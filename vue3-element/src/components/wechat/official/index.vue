<template>

    <el-card>

        <el-tabs v-model="activeTab">
            <el-tab-pane label="公众号配置" name="setting">
                <Setting />
            </el-tab-pane>
            <el-tab-pane label="公众号菜单" name="menu">
                <Menu />
            </el-tab-pane>
            <el-tab-pane label="公众号消息模板" name="template">
                <Template />
            </el-tab-pane>
        </el-tabs>
       
    </el-card>


</template>


<script setup lang="ts">

import { ref } from 'vue'
const activeTab = ref('setting')

import Setting from './setting.vue'
import Menu from './menu.vue'
import Template from './template.vue'


</script>

<style scoped lang="scss">

</style>

