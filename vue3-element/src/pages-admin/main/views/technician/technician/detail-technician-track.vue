<template>
    <el-card shadow="never">
        <el-table :data="tableData.data" style="width: 100%" v-loading="tableData.loading">
            <el-table-column prop="id" label="ID" width="80" />
            <el-table-column prop="technician_id" label="师傅ID" width="100" />
            <el-table-column label="位置信息" min-width="200">
                <template #default="{ row }">
                    <div class="text-sm">
                        <div>经度: {{ row.latitude }}</div>
                        <div>纬度: {{ row.longitude }}</div>
                    </div>
                </template>
            </el-table-column>
            <el-table-column prop="address" label="地址" min-width="300" show-overflow-tooltip />
            <el-table-column prop="create_at" label="创建时间" width="180" />
            <el-table-column label="操作" min-width="200" fixed="right">
                <template #default="{ row }">
                    <el-button type="primary" link @click="handleViewMap(row)">
                        查看地图
                    </el-button>
                    <el-button type="danger" link @click="handleDeleteTrack(row)">
                        删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex justify-between items-center mt-[20px]">
            <div>
                <el-button type="danger" @click="handleClearAllTracks" :loading="clearLoading">
                    清空全部轨迹
                </el-button>
            </div>
            <div>
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />
            </div>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import { reactive, watch, ref } from 'vue'
import { ElMessage, ElMessageBox } from 'element-plus'
import { getTechnicianTrackPages, deleteTechnicianTrack, clearTechnicianTrack } from '@/pages-admin/main/api/technician/technicianTrack'
import { usePagination } from '@/hooks/usePagination'

// 组件名称
defineOptions({
    name: 'TechnicianDetailTrack'
})

// Props定义
const props = defineProps({
    // 师傅ID
    technician_id: {
        type: Number,
        default: 0
    },
})

// 搜索参数
const searchParams = reactive({
    technician_id: props.technician_id,
})

// 使用分页Hook
const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTechnicianTrackPages,
    searchParams: searchParams
})

// 清空加载状态
const clearLoading = ref(false)

// 监听 technician_id 的变化并更新 searchParams
watch(
    () => props.technician_id,
    (newTechnicianId) => {
        searchParams.technician_id = newTechnicianId
        getTableList() // 重新获取数据
    }
)

// 删除单条轨迹
const handleDeleteTrack = async (row: any) => {
    try {
        await ElMessageBox.confirm(`确定要删除这条轨迹记录吗？`, '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        })

        await deleteTechnicianTrack(row.id)
        ElMessage.success('删除成功')
        getTableList()
    } catch (error) {
        if (error !== 'cancel') {
            console.error('删除失败', error)
            ElMessage.error('删除失败')
        }
    }
}

// 清空全部轨迹
const handleClearAllTracks = async () => {
    try {
        await ElMessageBox.confirm(
            `确定要清空师傅ID为 ${props.technician_id} 的所有轨迹记录吗？此操作不可恢复！`,
            '警告',
            {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }
        )

        clearLoading.value = true
        await clearTechnicianTrack({
            technician_id: props.technician_id,
            days: 0 // 0表示清空所有记录
        })

        ElMessage.success('清空成功')
        getTableList()
    } catch (error) {
        if (error !== 'cancel') {
            console.error('清空失败', error)
            ElMessage.error('清空失败')
        }
    } finally {
        clearLoading.value = false
    }
}

// 查看地图
const handleViewMap = (row: any) => {
    const mapUrl = `https://maps.google.com/maps?q=${row.latitude},${row.longitude}`
    window.open(mapUrl, '_blank')
}




</script>

<style scoped></style>