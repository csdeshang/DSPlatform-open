<template>
    <!-- NoticeTemplate component -->
    <div>

    <el-card shadow="never">
        <el-tabs v-model="searchParams.receiver_type" @tab-change="handleTabChange">
            <el-tab-pane name="1" label="用户消息模板"></el-tab-pane>
            <el-tab-pane name="2" label="店铺消息模板"></el-tab-pane>
        </el-tabs>
        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
            <el-table-column label="标识" prop="key" width="250" />
            <el-table-column label="标题" prop="title" width="180" />
            <el-table-column label="描述" prop="description" width="500" />
            <el-table-column label="模板类型" prop="template_type" width="120">
                <template #default="{ row }">
                    {{ row.template_type === 1 ? '业务通知' : '验证码' }}
                </template>
            </el-table-column>
                <el-table-column label="通知渠道" prop="supported_channels" width="280">
                <template #default="{ row }">
                        <div class="flex flex-row gap-2 flex-wrap">
                            <el-tag v-if="row.supported_channels && row.supported_channels.includes('interna')"
                                size="small">站内通知</el-tag>
                            <el-tag v-if="row.supported_channels && row.supported_channels.includes('email')"
                                size="small" type="success">邮件</el-tag>
                            <el-tag v-if="row.supported_channels && row.supported_channels.includes('sms')" size="small"
                                type="warning">短信</el-tag>
                            <el-tag v-if="row.supported_channels && row.supported_channels.includes('wechat_official')"
                                size="small" type="info">微信公众号</el-tag>
                            <el-tag v-if="row.supported_channels && row.supported_channels.includes('wechat_mini')"
                                size="small" type="primary">微信小程序</el-tag>
                    </div>
                </template>
            </el-table-column>
            <el-table-column label="渠道状态" width="200">
                <template #default="{ row }">
                    <div class="flex flex-col gap-1">
                        <div v-if="row.email_switch" class="text-xs">
                            <i class="el-icon-check text-green-500"></i> 邮件已开启
                        </div>
                        <div v-if="row.sms_switch" class="text-xs">
                            <i class="el-icon-check text-green-500"></i> 短信已开启
                        </div>
                            <div v-if="row.wechat_official_switch" class="text-xs">
                                <i class="el-icon-check text-green-500"></i> 公众号已开启
                            </div>
                            <div v-if="row.wechat_mini_switch" class="text-xs">
                                <i class="el-icon-check text-green-500"></i> 小程序已开启
                        </div>
                    </div>
                </template>
            </el-table-column>

                <el-table-column label="操作" align="right" fixed="right" width="180">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleEdit(row)">编辑</el-button>
                        <el-button type="success" link @click="handleTest(row)">测试</el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-card>

    <SysNoticeTplEdit ref="sysNoticeTplEdit" @complete="fetchSysNoticeTplList" />
        <SysNoticeTplTest ref="sysNoticeTplTest" @complete="fetchSysNoticeTplList" />

    </div>

</template>
<script lang="ts" setup>

import { reactive, ref } from 'vue';

import { getSysNoticeTplList } from '@/pages-admin/main/api/system/sysNotice'

import SysNoticeTplEdit from './tpl-edit.vue'
import SysNoticeTplTest from './tpl-test.vue'



// 数据
const tableData = reactive({
    loading: true,
    data: [],
})

const searchParams = reactive({
    receiver_type: "1"
})


// 切换订单状态
const handleTabChange = (type: any) => {
    searchParams.receiver_type = type
    fetchSysNoticeTplList()
}


// 加载列表
const fetchSysNoticeTplList = () => {
    tableData.loading = true;
    getSysNoticeTplList(searchParams).then(res => {
        tableData.loading = false
        tableData.data = res.data
    }).catch(() => {
        tableData.loading = false
    })
}
// 初始化列表
fetchSysNoticeTplList();


// 编辑
const sysNoticeTplEdit = ref()
const handleEdit = (row: any) => {
    sysNoticeTplEdit.value.setDialogData(row)
    sysNoticeTplEdit.value?.openDialog()
}

// 测试
const sysNoticeTplTest = ref()
const handleTest = (row: any) => {
    sysNoticeTplTest.value.setDialogData(row)
    sysNoticeTplTest.value?.openDialog()
}





</script>