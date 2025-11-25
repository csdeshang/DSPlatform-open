<template>
    <!-- 公共商品列表 显示 TblGoods 表中的数据 -->

    <div>
        <el-card shadow="never" class="mb-[10px]">
            <el-form :model="searchParams" inline>
                <el-form-item label="商品名">
                    <el-input v-model="searchParams.goods_name" placeholder="输入商品名" clearable />
                </el-form-item>
                <el-form-item label="店铺名">
                    <el-input v-model="searchParams.store_name" placeholder="输入店铺名" clearable />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="resetPage">查询</el-button>
                    <el-button @click="resetSearchParams">重置</el-button>
                </el-form-item>
            </el-form>
        </el-card>

        <el-card shadow="never">
            <el-tabs v-model="searchParams.tab_selected" @tab-change="handleTabChange">
                <el-tab-pane name="all" label="全部"></el-tab-pane>
                <el-tab-pane name="pending" label="店铺销售中"></el-tab-pane>
                <el-tab-pane name="un_send" label="店铺下架"></el-tab-pane>
                <el-tab-pane name="pending_review" label="待审核"></el-tab-pane>
                <el-tab-pane name="review_failed" label="审核失败"></el-tab-pane>
                <el-tab-pane name="violated" label="违规下架"></el-tab-pane>
                <el-tab-pane name="sys_recommend" label="系统推荐"></el-tab-pane>
                <el-tab-pane name="deleted" label="已删除"></el-tab-pane>
            </el-tabs>
            <el-table :data="tableData.data" size="large" v-loading="tableData.loading">
                <el-table-column label="ID" prop="id" width="120" />
                <el-table-column label="商品" prop="goods_name">
                    <template #default="{ row }">
                        <div class="flex flex-row">
                            <div class="mr-[10px]">
                                <el-image :src="formatImageUrl(row.cover_image, ThumbnailPresets.small, 'goods')" style="width: 50px; height: 50px;"
                                    fit="cover" />
                            </div>
                            <div>
                                <div class="text-[14px]">{{ row.goods_name }}</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column label="价格" prop="goodsSku.sku_price" width="100" />
                <el-table-column label="销量" prop="sales_num" width="100" />
                <el-table-column label="总库存" prop="stock_num" width="100" />
                <el-table-column label="排序" prop="sort" width="100" />
                <el-table-column label="状态" prop="goods_status_desc" width="100" />
                <el-table-column label="操作" align="right" fixed="right" width="130">
                    <template #default="{ row }">
                        <el-button type="primary" link @click="handleDtail(row)">详情</el-button>
                        <el-button type="primary" link @click="handleEditSysStatus(row)">修改状态</el-button>
                        <el-button type="primary" link @click="handleEditSysRecommend(row)">{{ row.sys_recommend_status ? '取消推荐' : '推荐' }}</el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div class="flex justify-end mt-[20px]">
                <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                    layout="total, sizes, prev, pager, next, jumper" :total="tableData.total"
                    @size-change="getTableList()" @current-change="getTableList" />
            </div>

        </el-card>


        <TblGoodsDetail ref="detailTblGoodsDialog" />
        <!-- 修改状态 sys_status -->
        <EditSysStatus ref="editSysStatusDialog" @complete="getTableList" />

    </div>

</template>

<script lang="ts" setup>

import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router'
import { ElMessageBox, ElMessage } from 'element-plus'

import { formatImageUrl, ThumbnailPresets } from '@/utils/image'
const router = useRouter()

import { usePagination } from '@/hooks/usePagination'
import { getTblGoodsPages, updateTblGoodsSysRecommend } from '@/pages-admin/main/api/tbl-goods/tblGoods'
import TblGoodsDetail from './detail.vue'
import EditSysStatus from './editSysStatus.vue'


const props = defineProps({
    //公共店铺关联的店铺类型
    platform: {
        type: String,
        default: ''
    },
})




const searchParams = reactive({
    goods_name: '',
    store_name: '',
    platform: props.platform,
    tab_selected: 'all'
})

// 切换订单状态
const handleTabChange = (name: any) => {
  searchParams.tab_selected = name
  getTableList()
}

const {
    tableData,
    getTableList,
    resetSearchParams,
    resetPage
} = usePagination({
    page_current: 1,
    page_size: 10,
    requestFun: getTblGoodsPages,
    searchParams: searchParams
})
getTableList()


//详情
const detailTblGoodsDialog = ref()
const handleDtail = (row: any) => {
    detailTblGoodsDialog.value.setDialogData(row)
    detailTblGoodsDialog.value?.openDialog()
}

// 修改状态
const editSysStatusDialog = ref()
const handleEditSysStatus = (row: any) => {
    editSysStatusDialog.value.setDialogData(row)
    editSysStatusDialog.value?.openDialog()
}

// 处理推荐/取消推荐商品
const handleEditSysRecommend = async (row: any) => {
  try {
    const newStatus = !row.sys_recommend_status;
    const actionText = newStatus ? '推荐' : '取消推荐';
    
    // 可以在此添加确认对话框，防止误操作
    await ElMessageBox.confirm(
      `确定要${actionText}商品"${row.goods_name}"吗？`, 
      '提示', 
      {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }
    );
    
    // 调用API更新推荐状态
    const res = await updateTblGoodsSysRecommend({
      id: row.id,
      sys_recommend_status: newStatus ? 1 : 0
    });
    
    if (res.code === 10000) {
      ElMessage.success(`商品${actionText}成功`);
      // 刷新列表
      getTableList();
    } else {
      ElMessage.error(res.message || `商品${actionText}失败`);
    }
  } catch (error) {
    // 用户取消操作或发生错误
    if (error !== 'cancel') {
      console.error('修改推荐状态失败:', error);
      ElMessage.error('操作失败，请重试');
    }
  }
};





</script>
