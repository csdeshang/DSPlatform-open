<template>
    <div class="wechat-official-template">
        <el-card shadow="never">
            <template #header>
                <div class="flex justify-between items-center">
                    <h3>微信公众号模板管理</h3>
                    <div class="flex gap-2">
                        <el-button type="primary" @click="handleBatchSync" :loading="syncing">
                            <el-icon>
                                <Refresh />
                            </el-icon>
                            批量同步模板
                        </el-button>
                        <el-button @click="fetchTemplateList">
                            <el-icon>
                                <RefreshRight />
                            </el-icon>
                            刷新
                        </el-button>
                    </div>
                </div>
            </template>

            <!-- 表格 -->
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading" stripe>
                <el-table-column label="ID" prop="id" width="80" />
                <el-table-column label="标识" prop="key" width="200" show-overflow-tooltip />
                <el-table-column label="标题" prop="title" width="180" show-overflow-tooltip />

                <el-table-column label="模板类型" prop="template_type_desc" width="100" align="center" />

                <el-table-column label="接收者" prop="receiver_type_desc" width="100" align="center" />

                <el-table-column label="模板ID" prop="wechat_official_template_id" width="300" show-overflow-tooltip>
                    <template #default="{ row }">
                        <span v-if="row.wechat_official_template_id" class="text-green-600">
                            {{ row.wechat_official_template_id }}
                        </span>
                        <span v-else class="text-gray-400">未配置</span>
                    </template>
                </el-table-column>

                <el-table-column label="模板分类名称(公众号)" prop="preset.category_name" width="200" align="center" />

                <el-table-column label="公众号开关" width="300" align="center">
                    <template #default="{ row }">
                        <el-tag :type="row.wechat_official_switch === 1 ? 'success' : 'danger'" size="small">
                            {{ row.wechat_official_switch === 1 ? '已启用' : '已关闭' }}
                        </el-tag>
                    </template>
                </el-table-column>



                <el-table-column label="操作" width="300" align="center" fixed="right">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleSync([row.key], '同步模板')" :loading="syncing">
                            同步模板
                        </el-button>
                        <el-button type="danger" link @click="handleDeleteTemplate(row)" :loading="syncing">
                            删除模板
                        </el-button>
                    </template>
                </el-table-column>

            </el-table>

        </el-card>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import { Refresh, RefreshRight } from '@element-plus/icons-vue'
import {
    getWechatOfficialTemplateList,
    syncWechatOfficialTemplate,
    deleteWechatOfficialTemplate,
} from '@/api/wechat/wechatOfficialTemplate'

// 搜索参数
const searchParams = reactive({

})

// 表格数据
const tableData = reactive({
    loading: false,
    data: [],
})

// 同步状态
const syncing = ref(false)

// 获取模板列表
const fetchTemplateList = async () => {
    tableData.loading = true
    try {
        const res = await getWechatOfficialTemplateList()
        if (res.code === 10000) {
            tableData.loading = false
            tableData.data = res.data
        } else {
            ElMessage.error(res.message || '获取模板列表失败')
        }
    } catch (error) {
        console.error('获取模板列表失败:', error)
        ElMessage.error('获取模板列表失败')
    } finally {
        tableData.loading = false
    }
}

// 通用同步方法 - 支持单个或多个keys
const handleSync = async (keys: string[], actionName = '同步') => {
    if (!keys || keys.length === 0) {
        ElMessage.warning('请选择要同步的模板')
        return
    }

    try {
        const isMultiple = keys.length > 1
        const confirmMessage = isMultiple
            ? `确认${actionName} ${keys.length} 个模板？此操作将从微信公众平台获取最新的模板信息。`
            : `确认${actionName}模板 "${keys[0]}"？此操作将从微信公众平台获取最新的模板信息。`

        await ElMessageBox.confirm(
            confirmMessage,
            `确认${actionName}`,
            {
                confirmButtonText: '确认',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )

        syncing.value = true

        // 执行同步，传递keys数组给服务端处理
        const syncRes = await syncWechatOfficialTemplate(keys)

        if (syncRes.code === 10000) {
            ElMessage.success(`${actionName}成功`)
            fetchTemplateList() // 刷新列表
        } 

    } catch (error) {
        if (error !== 'cancel') {
            console.error(`${actionName}失败:`, error)
            ElMessage.error(`${actionName}失败`)
        }
    } finally {
        syncing.value = false
    }
}

// 批量同步所有模板
const handleBatchSync = async () => {
    const keys = tableData.data.map((item: any) => item.key)
    await handleSync(keys, '批量同步')
}

// 删除模板
const handleDeleteTemplate = async (row: any) => {
    try {
        await ElMessageBox.confirm(
            `确认删除模板 "${row.key}"？删除后将清空该模板的微信模板ID配置。`,
            '确认删除',
            {
                confirmButtonText: '确认删除',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )

        syncing.value = true

        const deleteRes = await deleteWechatOfficialTemplate(row.key)

        if (deleteRes.code === 10000) {
            ElMessage.success('删除成功')
            fetchTemplateList() // 刷新列表
        }

    } catch (error) {
        if (error !== 'cancel') {
            console.error('删除失败:', error)
            ElMessage.error('删除失败')
        }
    } finally {
        syncing.value = false
    }
}

// 初始化
onMounted(() => {
    fetchTemplateList()
})
</script>

<style scoped lang="scss">
.wechat-official-template {
    .el-tag {
        border-radius: 4px;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .text-gray-400 {
        color: #9ca3af;
    }
}
</style>