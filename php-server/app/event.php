<?php
// 事件定义文件
return [
    'bind'      => [],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => ['\app\listener\http\HttpEndListener'],
        'LogLevel' => [],
        'LogWrite' => [],

        //用户相关
        'UserRegisterListener' => ['\app\listener\user\UserRegisterListener'],
        'UserLoginListener' => ['\app\listener\user\UserLoginListener'],
        'UserGoodsCommentListener' => ['\app\listener\user\UserGoodsCommentListener'],
        'UserInviteListener' => ['\app\listener\user\UserInviteListener'],
        // 订单相关
        'OrderCancelListener' => ['\app\listener\order\OrderCancelListener'],
        'OrderChangeAmountListener' => ['\app\listener\order\OrderChangeAmountListener'],
        'OrderCloseListener' => ['\app\listener\order\OrderCloseListener'],
        'OrderConfirmListener' => ['\app\listener\order\OrderConfirmListener'],
        'OrderDeliveryListener' => ['\app\listener\order\OrderDeliveryListener'],
        'OrderGenerateListener' => ['\app\listener\order\OrderGenerateListener'],
        'OrderPaySuccessListener' => ['\app\listener\order\OrderPaySuccessListener'],
        'OrderRefundSuccessListener' => ['\app\listener\order\OrderRefundSuccessListener'],
        


        // 系统通知
        'SysNoticeListener' => ['\app\listener\system\SysNoticeListener'],

    ],

    'subscribe' => [],
];
