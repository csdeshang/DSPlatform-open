<template>
    <!-- 订单详情 显示和修改 TblOrder 表中的数据  如果没有拓展表则使用默认的-->



    <el-drawer v-model="dialogVisible" :title="popTitle" size="60%">



        <div class="ds-detail">


            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">

                    </div>
                    <div class="section-hd-top-right">
                        <el-button v-if="refundInfo.store_refund_actions.includes('agree_refund')" type="success"
                            @click="handleAgree(refundInfo)">同意退款</el-button>
                        <el-button v-if="refundInfo.store_refund_actions.includes('reject_refund')" type="danger"
                            @click="handleReject(refundInfo)">拒绝退款</el-button>
                        <el-button v-if="refundInfo.store_refund_actions.includes('receive_goods')" type="danger"
                            @click="handleReceiveGoods(refundInfo)">收到退货</el-button>

                    </div>
                </div>
                <div class="section-hd-center style-1">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">退款ID:</div>
                                <div class="item-content">
                                    {{ refundInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">平台:</div>
                                <div class="item-content">
                                    {{ refundInfo.platform }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">退款类型:</div>
                                <div class="item-content">
                                    {{ refundInfo.refund_type_desc }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">退款状态:</div>
                                <div class="item-content">
                                    {{ refundInfo.refund_status_desc }}
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


                        <div class="section-bd-block">
                            <div class="section-bd-block-title">
                                用户信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="用户ID" prop="id">
                                            <div class="form-text">{{ refundInfo.user.id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="用户名" prop="username">
                                            <div class="form-text">{{ refundInfo.user.username }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="用户昵称" prop="nickname">
                                            <div class="form-text">{{ refundInfo.user.nickname }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                        <div class="section-bd-block">
                            <div class="section-bd-block-title">
                                退款信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="用户ID" prop="user_id">
                                            <div class="form-text">{{ refundInfo.user_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="店铺ID" prop="store_id">
                                            <div class="form-text">{{ refundInfo.store_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款单号" prop="out_refund_no">
                                            <div class="form-text">{{ refundInfo.out_refund_no }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="订单ID" prop="order_id">
                                            <div class="form-text">{{ refundInfo.order_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="商品ID" prop="order_goods_id">
                                            <div class="form-text">{{ refundInfo.order_goods_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="申请金额" prop="apply_amount">
                                            <div class="form-text">{{ refundInfo.apply_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="实际退款" prop="refund_amount">
                                            <div class="form-text">{{ refundInfo.refund_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款类型" prop="refund_type">
                                            <div class="form-text">{{ refundInfo.refund_type_desc }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款方式" prop="refund_method">
                                            <div class="form-text">{{ refundInfo.refund_method_desc }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款状态" prop="refund_status">
                                            <div class="form-text">{{ refundInfo.refund_status_desc }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="申请时间" prop="create_at">
                                            <div class="form-text">{{ refundInfo.create_at }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="同意时间" prop="agree_time">
                                            <div class="form-text">{{ refundInfo.agree_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="成功时间" prop="success_time">
                                            <div class="form-text">{{ refundInfo.success_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="拒绝时间" prop="reject_time">
                                            <div class="form-text">{{ refundInfo.reject_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="更新时间" prop="update_at">
                                            <div class="form-text">{{ refundInfo.update_at }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="24">
                                        <el-form-item label="退款说明" prop="refund_explain">
                                            <div class="form-text">{{ refundInfo.refund_explain || '无' }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="24">
                                        <el-form-item label="拒绝原因" prop="reject_reason">
                                            <div class="form-text">{{ refundInfo.reject_reason || '无' }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="refundInfo.refund_type === 2">
                                        <el-form-item label="物流公司" prop="express_company">
                                            <div class="form-text">{{ refundInfo.express_company || '无' }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="refundInfo.refund_type === 2">
                                        <el-form-item label="物流单号" prop="express_number">
                                            <div class="form-text">{{ refundInfo.express_number || '无' }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="24" v-if="refundInfo.refund_address">
                                        <el-form-item label="退货地址" prop="refund_address">
                                            <div class="form-text">{{ refundInfo.refund_address }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="24" v-if="refundInfo.refund_images">
                                        <el-form-item label="退款凭证" prop="refund_images">
                                            <div class="refund-images">
                                                <el-image v-for="(img, index) in refundInfo.refund_images.split(',')"
                                                    :key="index" :src="formatImageUrl(img, ThumbnailPresets.small)"
                                                    style="width: 80px; height: 80px; margin-right: 10px;"
                                                    :preview-src-list="refundInfo.refund_images.split(',')" />
                                            </div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                    </el-tab-pane>

 

                    <el-tab-pane label="退款记录" name="orderlog">
                        <detail-log :refund_id="refundInfo.id" />
                    </el-tab-pane>



                </el-tabs>
            </div>
        </div>






    </el-drawer>




</template>


<script lang="ts" setup>

import { computed, reactive, ref } from 'vue';

import { formatImageUrl, ThumbnailPresets } from '@/utils/image'


import { ElMessage, ElMessageBox } from 'element-plus';
import { getTblOrderRefundInfo } from '@/pages-admin/main/api/tbl-order/tblOrderRefund'
import detailLog from './detail-log.vue'




const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const tabSelected = ref('base')

// 定义符合实际数据结构的类型
interface User {
    id: number;
    username: string;
    nickname: string;
    avatar: string | null;
}

interface Store {
    id: number;
    store_name: string;
}

interface OrderRefundLog {
    refund_status_desc: string;
    id: number;
    refund_id: number;
    refund_status: number;
    message: string;
    create_role: string;
    create_uid: number;
    create_at: string;
}

interface RefundInfo {
    refund_type_desc: string;
    refund_status_desc: string;
    refund_method_desc: string;
    id: number;
    platform: string;
    order_id: number;
    order_goods_id: number;
    out_refund_no: string;
    user_id: number;
    store_id: number;
    apply_amount: number;
    refund_type: number;
    refund_status: number;
    refund_method: number;
    refund_amount: number;
    refund_explain: string | null;
    refund_images: string | null;
    refund_address: string;
    express_number: string;
    express_company: string;
    reject_reason: string | null;
    agree_time: number | null;
    reject_time: number | null;
    success_time: number | null;
    create_at: string;
    update_at: string;
    store: Store;
    user: User;
    orderRefundLogList: OrderRefundLog[];
    store_refund_actions: string[];
    user_refund_actions: string[];
    order_no?: string; // 可选字段
}

// 使用定义的类型初始化refundInfo
const refundInfo = reactive<RefundInfo>({
    refund_type_desc: '',
    refund_status_desc: '',
    refund_method_desc: '',
    id: 0,
    platform: '',
    order_id: 0,
    order_goods_id: 0,
    out_refund_no: '',
    user_id: 0,
    store_id: 0,
    apply_amount: 0,
    refund_type: 0,
    refund_status: 0,
    refund_method: 0,
    refund_amount: 0,
    refund_explain: null,
    refund_images: null,
    refund_address: '',
    express_number: '',
    express_company: '',
    reject_reason: null,
    agree_time: null,
    reject_time: null,
    success_time: null,
    create_at: '',
    update_at: '',
    store: { id: 0, store_name: '' },
    user: { id: 0, username: '', nickname: '', avatar: null },
    orderRefundLogList: [],
    store_refund_actions: [],
    user_refund_actions: []
})




const setDialogData = async (row: any = null) => {
    loading.value = true
    popTitle = '退款详情'
    if (row.id) {
        const data = await (await getTblOrderRefundInfo(row.id)).data
        Object.assign(refundInfo, data)
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
