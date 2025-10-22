<template>
    <!-- 商品详情 显示和修改 TblGoods 表中的数据  如果没有拓展表则使用默认的-->



    <el-drawer v-model="dialogVisible" :title="popTitle" size="70%">



        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
                        <div class="avatar">
                            <el-avatar :size="80" :src="formatFileUrl(goodsDetail.cover_image)" />
                        </div>
                        <div class="info">
                            <div class="name">
                                {{ goodsDetail.goods_name }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="section-hd-center">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">商品ID</div>
                                <div class="item-content">
                                    {{ goodsDetail.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">商品状态</div>
                                <div class="item-content">
                                    {{ goodsDetail.goods_status_desc }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">创建时间：</div>
                                <div class="item-content">
                                    {{ goodsDetail.create_at }}
                                </div>
                            </div>
                        </el-col>

                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">更新时间：</div>
                                <div class="item-content">
                                    {{ goodsDetail.update_at }}
                                </div>
                            </div>
                        </el-col>


                    </el-row>




                </div>
                <div class="section-hd-bottom">

                </div>

            </div>

            <div class="section-bd">
                <el-tabs v-model="tabSelected">
                    <el-tab-pane label="基本信息" name="base">
                        <el-form ref="formRef" :model="formData" :rules="formRules" label-width="120px" class="mt-6">



                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    基本信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="商品名称" prop="goods_name">
                                                <div class="form-text">{{ goodsDetail.goods_name }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="商品广告词" prop="goods_advword">
                                                <div class="form-text">{{ goodsDetail.goods_advword }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="最低价格" prop="goods_minprice">
                                                <div class="form-text">{{ goodsDetail.goods_minprice }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="商品状态" prop="goods_status">
                                                <div class="form-text">{{ goodsDetail.goods_status_desc }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="库存数量" prop="stock_num">
                                                <div class="form-text">{{ goodsDetail.stock_num }}</div>
                                            </el-form-item>
                                        </el-col>


                                        <el-col :span="12">
                                            <el-form-item label="商品排序" prop="goods_sort">
                                                <div class="form-text">{{ goodsDetail.goods_sort }}</div>
                                            </el-form-item>
                                        </el-col>



                                        <el-col :span="12">
                                            <el-form-item label="是否分销商品" prop="is_distributor_goods">
                                                <div class="form-text">{{ goodsDetail.is_distributor_goods === 1 ? '是' :
                                                    '否' }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="商品平台" prop="platform">
                                                <div class="form-text">{{ goodsDetail.platform }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="店铺ID" prop="store_id">
                                                <div class="form-text">{{ goodsDetail.store_id }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="品牌ID" prop="brand_id">
                                                <div class="form-text">{{ goodsDetail.brand_id }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="店铺商品分类" prop="store_goods_cid">
                                                <div class="form-text">{{ goodsDetail.store_goods_cid }}</div>
                                            </el-form-item>
                                        </el-col>


                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    商品统计
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="点击数量" prop="click_num">
                                                <div class="form-text">{{ goodsDetail.click_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="销售数量" prop="sales_num">
                                                <div class="form-text">{{ goodsDetail.sales_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="虚拟销售数量" prop="virtual_sales_num">
                                                <div class="form-text">{{ goodsDetail.virtual_sales_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="收藏数量" prop="collect_num">
                                                <div class="form-text">{{ goodsDetail.collect_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="评价数量" prop="evaluate_num">
                                                <div class="form-text">{{ goodsDetail.evaluate_num }}</div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="平均评分" prop="avg_goods_score">
                                                <div class="form-text">{{ goodsDetail.avg_goods_score }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>

                            <div class="section-bd-block">
                                <div class="section-bd-block-title">
                                    平台信息
                                </div>
                                <div class="section-bd-block-content">
                                    <el-row :gutter="20">
                                        <el-col :span="12">
                                            <el-form-item label="系统状态" prop="sys_status">
                                                <div class="form-text">
                                                    {{ goodsDetail.sys_status_desc }}
                                                </div>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="12">
                                            <el-form-item label="状态原因" prop="sys_status_reason">
                                                <div class="form-text">{{ goodsDetail.sys_status_reason }}</div>
                                            </el-form-item>
                                        </el-col>

                                        <el-col :span="12">
                                            <el-form-item label="系统推荐状态" prop="sys_recommend_status">
                                                <div class="form-text">{{ goodsDetail.sys_recommend_status === 1 ? '推荐'
                                                    : '不推荐' }}</div>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                </div>
                            </div>



                        </el-form>
                    </el-tab-pane>

                    <el-tab-pane label="购买记录" name="order">
                        <DetailOrderGoods :goods_id="goodsDetail.id" />
                    </el-tab-pane>

                    <el-tab-pane label="分销记录" name="distributor">
                        <DetailDistributorOrder :goods_id="goodsDetail.id" />
                    </el-tab-pane>



                </el-tabs>

            </div>

        </div>






    </el-drawer>




</template>


<script lang="ts" setup>
import type { FormInstance } from 'element-plus';
import { computed, reactive, ref } from 'vue';
import { getTblGoodsInfo, updateTblGoods } from '@/pages-admin/main/api/tbl-goods/tblGoods'
import DetailOrderGoods from './detail-order-goods.vue'
import DetailDistributorOrder from './detail-distributor-order.vue'
import { formatFileUrl } from '@/utils/util'

const tabSelected = ref('base')


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const goodsDetail = reactive({})

// 初始化表单数据
const initialFormData = {
    id: '',
    name: '',
}
const formData: Record<string, any> = reactive({ ...initialFormData })
const formRef = ref<FormInstance>()


// 表单验证规则
const formRules = computed(() => {
    return {

    }
})

const emit = defineEmits(['complete'])

const handleSubmit = async () => {
    if (loading.value || !formRef.value) return
    await formRef.value.validate()
    loading.value = true
    updateTblGoods(formData).then(res => {
        loading.value = false
        dialogVisible.value = false
        emit('complete')
    }).catch(() => {
        loading.value = false
    })
}




const setDialogData = async (row: any = null) => {
    loading.value = true
    Object.assign(formData, initialFormData)
    popTitle = '商品详情'

    if (row) {
        const data = await (await getTblGoodsInfo(row.id)).data
        Object.assign(goodsDetail, data)
        Object.keys(formData).forEach((key: string) => {
            if (data[key] != undefined) formData[key] = data[key]
        })
    }
    loading.value = false
}



defineExpose({
    openDialog: () => {
        dialogVisible.value = true
    },
    setDialogData
})


</script>
