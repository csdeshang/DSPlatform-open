<template>

    <!--Mall 店铺订单列表-->
    <el-card shadow="never" class="mb-[10px]">
        <el-form :model="searchParams" inline>
            <el-form-item label="商品名称">
                <el-input v-model="searchParams.goods_name" placeholder="商品名称" clearable />
            </el-form-item>
            <el-form-item label="店铺名">
                <el-input v-model="searchParams.store_name" placeholder="请输入店铺名" clearable />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="resetPage">查询</el-button>
                <el-button @click="resetSearchParams">重置</el-button>
            </el-form-item>
        </el-form>
    </el-card>



    <el-card shadow="never">
        <el-tabs v-model="searchParams.platform" @tab-change="handleTabChange">
            <el-tab-pane v-for="item in platformList" :key="item.id" :label="item.name" :name="item.platform">
            </el-tab-pane>
        </el-tabs>



        <el-table :data="tableData.data" size="large" v-loading="tableData.loading">


            <el-table-column label="商品ID" prop="id" width="100" />
            <el-table-column label="商品图片" prop="goods_name" width="100">
                <template #default="{ row }">
                    <el-image :src="formatFileUrl(row.cover_image)" style="width: 50px; height: 50px;"
                                fit="cover" />
                </template>
            </el-table-column>

            <el-table-column label="商品名称" prop="goods_name" />

            <el-table-column label="是否参与分销" prop="is_distributor_goods">
                <template #default="{ row }">
                    <el-tag v-if="row.is_distributor_goods == 1" type="success">参与分销</el-tag>
                    <el-tag v-else type="danger">未参与分销</el-tag>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="right" fixed="right" width="200">
                <template #default="{ row }">
                    <div class="flex flex-row">
                        <el-button type="primary" link @click="handleTblGoodsDetail(row)">查看商品</el-button>
                    </div>
                </template>
            </el-table-column>

        </el-table>

        <div class="flex justify-end mt-[20px]">

            <el-pagination v-model:current-page="tableData.page_current" v-model:page-size="tableData.page_size"
                layout="total, sizes, prev, pager, next, jumper" :total="tableData.total" @size-change="getTableList()"
                @current-change="getTableList" />

        </div>


    </el-card>


    <!-- 订单详情弹窗 -->
    <TblGoodsDetail ref="handleGoodsDetailRef" />


</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';

import { usePagination } from '@/hooks/usePagination'
// 使用TblGoods  接口查询
import { getTblGoodsPages } from '@/pages-admin/main/api/tbl-goods/tblGoods'

import TblGoodsDetail from '@/pages-admin/components/tbl-goods/goods/detail.vue'

import { getSysPlatformList } from '@/pages-admin/main/api/system/SysPlatform'

import { formatFileUrl } from '@/utils/util'



const searchParams = reactive({
    goods_name: '',
    store_name: '',
    is_distributor_goods: 1,
    platform: 'mall',
})

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


// 切换平台
const handleTabChange = (name: any) => {
    searchParams.platform = name
    getTableList()
}

// 获取平台列表
const platformList = ref<any[]>([])
const fetchPlatformList = async () => {
    try {
        const response = await getSysPlatformList({scene: 'store'});
        platformList.value = response.data
    } catch (error) {
        console.error('请求系统配置时出错:', error);
    }
};
fetchPlatformList()

// 商品详情
const handleGoodsDetailRef = ref<any>()
const handleTblGoodsDetail = (row: any) => {
    handleGoodsDetailRef.value.setDialogData({ id: row.id })
    handleGoodsDetailRef.value?.openDialog()
}





</script>
