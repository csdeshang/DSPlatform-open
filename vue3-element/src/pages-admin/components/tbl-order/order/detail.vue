<template>
    <!-- 订单详情 显示和修改 TblOrder 表中的数据  如果没有拓展表则使用默认的-->



    <el-drawer v-model="dialogVisible" :title="popTitle" size="75%">

        <el-scrollbar style="height: 100%;">


        <div class="ds-detail">
            <div class="section-hd">
                <div class="section-hd-top">
                    <div class="section-hd-top-left">
 
                    </div>
                    <div class="section-hd-top-right">
                        <el-button type="primary">编辑</el-button>
                    </div>
                </div>
                <div class="section-hd-center style-1">
                    <el-row :gutter="10">
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">订单ID:</div>
                                <div class="item-content">
                                    {{ orderInfo.id }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">平台:</div>
                                <div class="item-content">
                                    {{ orderInfo.platform }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">收款方:</div>
                                <div class="item-content">
                                    {{ orderInfo.pay_merchant_id == 0 ? '平台收款' : '商户收款' }}
                                </div>
                            </div>
                        </el-col>
                        <el-col :span="6">
                            <div class="item">
                                <div class="item-title">订单状态:</div>
                                <div class="item-content">
                                    {{ orderInfo.order_status_desc }}
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
                                            <div class="form-text">{{ orderInfo.user.id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="用户名" prop="username">
                                            <div class="form-text">{{ orderInfo.user.username }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="用户昵称" prop="nickname">
                                            <div class="form-text">{{ orderInfo.user.nickname }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="用户手机" prop="mobile">
                                            <div class="form-text">{{ orderInfo.user.mobile }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                        <div class="section-bd-block">
                            <div class="section-bd-block-title">
                                收货信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="收货人" prop="reciver_name">
                                            <div class="form-text">{{ orderInfo.orderAddress.reciver_name }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="收货人手机" prop="reciver_mobile">
                                            <div class="form-text">{{ orderInfo.orderAddress.reciver_mobile }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="收货地址" prop="reciver_address">
                                            <div class="form-text">{{ orderInfo.orderAddress.reciver_address }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>
                        <div class="section-bd-block" v-if="orderInfo.pay_merchant_id > 0">
                            <div class="section-bd-block-title">
                                收款商户信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="商户ID：" prop="id">
                                            <div class="form-text">{{ orderInfo.payMerchant.id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="商户名称" prop="name">
                                            <div class="form-text">{{ orderInfo.payMerchant.name }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="联系人" prop="contact_name">
                                            <div class="form-text">{{ orderInfo.payMerchant.contact_name }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="联系电话" prop="contact_phone">
                                            <div class="form-text">{{ orderInfo.payMerchant.contact_phone }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                        <div class="section-bd-block">
                            <div class="section-bd-block-title">
                                订单信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="订单来源" prop="order_from">
                                            <div class="form-text">{{ orderInfo.order_from }}</div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12">
                                        <el-form-item label="支付渠道" prop="pay_channel">
                                            <div class="form-text">{{ orderInfo.pay_channel }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="支付场景" prop="pay_scene">
                                            <div class="form-text">{{ orderInfo.pay_scene }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="订单合并ID" prop="order_merge_id">
                                            <div class="form-text">{{ orderInfo.order_merge_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="订单编号" prop="order_sn">
                                            <div class="form-text">{{ orderInfo.order_sn }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="下单推荐人" prop="order_referrer_id">
                                            <div class="form-text">{{ orderInfo.order_referrer_id }}</div>
                                        </el-form-item>
                                    </el-col>




                                    <el-col :span="12">
                                        <el-form-item label="订单状态" prop="order_status">
                                            <div class="form-text">{{ orderInfo.order_status }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="外部订单号" prop="out_trade_no">
                                            <div class="form-text">{{ orderInfo.out_trade_no }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="支付单号" prop="trade_no">
                                            <div class="form-text">{{ orderInfo.trade_no }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="商品原价" prop="original_amount">
                                            <div class="form-text">{{ orderInfo.original_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="商品总价" prop="goods_amount">
                                            <div class="form-text">{{ orderInfo.goods_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="物流费用" prop="shipping_amount">
                                            <div class="form-text">{{ orderInfo.shipping_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="优惠金额" prop="discount_amount">
                                            <div class="form-text">{{ orderInfo.discount_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="订单金额" prop="order_amount">
                                            <div class="form-text">{{ orderInfo.order_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="支付金额" prop="pay_amount">
                                            <div class="form-text">{{ orderInfo.pay_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="平台服务费" prop="service_amount">
                                            <div class="form-text">{{ orderInfo.service_amount }}</div>
                                        </el-form-item>
                                    </el-col>




                                    <el-col :span="12">
                                        <el-form-item label="发票信息" prop="invoice_info">
                                            <div class="form-text">{{ orderInfo.invoice_info }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="评价状态" prop="is_evaluate">
                                            <div class="form-text">{{ orderInfo.is_evaluate }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="删除状态" prop="is_deleted">
                                            <div class="form-text">{{ orderInfo.is_deleted }}</div>
                                        </el-form-item>
                                    </el-col>





                                    <el-col :span="12">
                                        <el-form-item label="用户备注" prop="user_remark">
                                            <div class="form-text">{{ orderInfo.user_remark }}</div>
                                        </el-form-item>
                                    </el-col>


                                    <el-col :span="12">
                                        <el-form-item label="店铺备注" prop="store_remark">
                                            <div class="form-text">{{ orderInfo.store_remark }}</div>
                                        </el-form-item>
                                    </el-col>


                                    <el-col :span="12">
                                        <el-form-item label="创建时间">
                                            <div class="form-text">{{ orderInfo.add_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="支付时间">
                                            <div class="form-text">{{ orderInfo.payment_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="交付时间">
                                            <div class="form-text">{{ orderInfo.delivery_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="发货时间">
                                            <div class="form-text">{{ orderInfo.shipping_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="完成时间">
                                            <div class="form-text">{{ orderInfo.finnshed_time }}</div>
                                        </el-form-item>
                                    </el-col>


                                    <el-col :span="12">
                                        <el-form-item label="评价时间">
                                            <div class="form-text">{{ orderInfo.evaluate_time }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="关闭时间">
                                            <div class="form-text">{{ orderInfo.close_time }}</div>
                                        </el-form-item>
                                    </el-col>





                                </el-row>
                            </div>
                        </div>


                        <div class="section-bd-block" v-if="orderInfo.orderFinance">
                            <div class="section-bd-block-title">
                                订单资金分配
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12" v-if="orderInfo.orderFinance.pay_amount > 0">
                                        <el-form-item label="订单实付金额" prop="pay_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.pay_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="orderInfo.orderFinance.distributor_amount > 0">
                                        <el-form-item label="分销金额" prop="distributor_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.distributor_amount }}</div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12">
                                        <el-form-item label="平台抽成金额(店铺)" prop="store_sys_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.store_sys_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="店铺实际收入" prop="store_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.store_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="orderInfo.orderFinance.rider_amount > 0">
                                        <el-form-item label="骑手实际收入" prop="rider_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.rider_amount }}</div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12" v-if="orderInfo.orderFinance.rider_sys_amount > 0">
                                        <el-form-item label="平台抽成金额(骑手)" prop="rider_sys_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.rider_sys_amount }}</div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12" v-if="orderInfo.orderFinance.technician_amount > 0">
                                        <el-form-item label="师傅实际收入" prop="technician_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.technician_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="orderInfo.orderFinance.technician_service_fee > 0">
                                        <el-form-item label="师傅服务费" prop="technician_service_fee">
                                            <div class="form-text">{{ orderInfo.orderFinance.technician_service_fee }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12" v-if="orderInfo.orderFinance.technician_trip_fee > 0">
                                        <el-form-item label="师傅路程费" prop="technician_trip_fee">
                                            <div class="form-text">{{ orderInfo.orderFinance.technician_trip_fee }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款金额" prop="refund_amount">
                                            <div class="form-text">{{ orderInfo.orderFinance.refund_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                        <div class="section-bd-block" v-if="orderInfo.orderDelivery">
                            <div class="section-bd-block-title">
                                交付信息
                            </div>
                            <div class="section-bd-block-content">
                                <el-row :gutter="20">
                                    <el-col :span="12">
                                        <el-form-item label="交付方式" prop="delivery_method_desc">
                                            <div class="form-text">{{ orderInfo.delivery_method_desc }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="交付状态" prop="status_desc">
                                            <div class="form-text">{{ orderInfo.orderDelivery.delivery_status_desc }}
                                            </div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                                <!-- 快递交付 -->
                                <el-row :gutter="20" v-if="orderInfo.orderDelivery.delivery_method == 'express'">
                                    <el-col :span="12">
                                        <el-form-item label="快递类型" prop="express_type">
                                            <div class="form-text">{{ orderInfo.orderDelivery.express_type }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="快递公司" prop="express_company">
                                            <div class="form-text">{{ orderInfo.orderDelivery.express_company }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="快递单号" prop="express_number">
                                            <div class="form-text">{{ orderInfo.orderDelivery.express_number }}</div>
                                        </el-form-item>
                                    </el-col>

                                </el-row>

                                <!-- 骑手配送 -->
                                <el-row :gutter="20" v-if="orderInfo.orderDelivery.delivery_method == 'rider'">
                                    <el-col :span="12">
                                        <el-form-item label="骑手ID" prop="rider_id">
                                            <div class="form-text">{{ orderInfo.orderDelivery.rider_id }}</div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12">
                                        <el-form-item label="配送总费用" prop="rider_total_fee">
                                            <div class="form-text">{{ orderInfo.orderDelivery.rider_total_fee }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="平台抽佣比例" prop="rider_fee_rate">
                                            <div class="form-text">{{ orderInfo.orderDelivery.rider_fee_rate }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="骑手实际配送费" prop="rider_fee">
                                            <div class="form-text">{{ orderInfo.orderDelivery.rider_fee }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="骑手费用说明" prop="rider_fee_desc">
                                            <div class="form-text">{{ orderInfo.orderDelivery.rider_fee_desc }}</div>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                                <!-- 上门服务 -->
                                <el-row :gutter="20" v-if="orderInfo.orderDelivery.delivery_method == 'technician'">
                                    <el-col :span="12">
                                        <el-form-item label="师傅ID" prop="technician_id">
                                            <div class="form-text">{{ orderInfo.orderDelivery.technician_id }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="预约时间" prop="technician_appt_time">
                                            <div class="form-text">{{ orderInfo.orderDelivery.technician_appt_time }}
                                            </div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="服务时长" prop="technician_duration">
                                            <div class="form-text">{{ orderInfo.orderDelivery.technician_duration }}
                                            </div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12">
                                        <el-form-item label="店铺抽佣比例" prop="technician_fee_rate">
                                            <div class="form-text">{{ orderInfo.orderDelivery.technician_fee_rate }}
                                            </div>
                                        </el-form-item>
                                    </el-col>

                                    <el-col :span="12">
                                        <el-form-item label="师傅实际收入" prop="technician_fee">
                                            <div class="form-text">{{ orderInfo.orderDelivery.technician_fee }}</div>
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
                                        <el-form-item label="正在退款中的申请" prop="refunding_count">
                                            <div class="form-text">{{ orderInfo.refunding_count }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款状态" prop="refund_status">
                                            <div class="form-text">{{ orderInfo.refund_status }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款金额" prop="refund_amount">
                                            <div class="form-text">{{ orderInfo.refund_amount }}</div>
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="12">
                                        <el-form-item label="退款截止时间">
                                            <div class="form-text">{{ orderInfo.allow_refund_time }}</div>
                                        </el-form-item>
                                    </el-col>

                                </el-row>
                            </div>
                        </div>







                    </el-tab-pane>

                    <el-tab-pane label="订单商品" name="ordergoods">
                        <DetailGoods :order_id="orderInfo.id" />
                    </el-tab-pane>

                    <el-tab-pane label="订单日志" name="orderlog">
                        <DetailLog :order_id="orderInfo.id" />
                    </el-tab-pane>
                    <el-tab-pane label="支付记录" name="paylog">
                        <DetailPayLog :order_id="orderInfo.id" :order_merge_id="orderInfo.order_merge_id" />
                    </el-tab-pane>

                    <el-tab-pane label="退款记录" name="refundlog">
                        <DetailRefund :order_id="orderInfo.id" />
                    </el-tab-pane>




                </el-tabs>
            </div>
        </div>


    </el-scrollbar>



    </el-drawer>




</template>


<script lang="ts" setup>

import { computed, reactive, ref } from 'vue';
import { getTblOrderInfo } from '@/pages-admin/main/api/tbl-order/tblOrder'
import DetailGoods from './detail-goods.vue'
import DetailLog from './detail-log.vue'
import DetailPayLog from './detail-pay-log.vue'
import DetailRefund from './detail-refund.vue'


const tabSelected = ref('base')


const dialogVisible = ref(false)
const loading = ref(false)
let popTitle: string = ''

const orderInfo = reactive({
    user: {},
    store: {},
    payMerchant: {},
    orderAddress: {},
    orderDelivery: {},
})




const setDialogData = async (row: any = null) => {
    loading.value = true
    popTitle = '订单详情'
    if (row.id) {
        const data = await (await getTblOrderInfo(row.id)).data
        Object.assign(orderInfo, data)
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
