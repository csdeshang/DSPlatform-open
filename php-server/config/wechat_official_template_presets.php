<?php

/**
 * 微信公众号模板预设配置
 * 
 * key 与 sys_notice_tpl表 中的 key字段 对应
 * 每个模板包含：
 * - template_id_short: 微信模板标题ID
 * - kidList: 关键词ID列表
 * - category_name: 模板分类名称
 * - template_fields: 字段映射配置
 *   - param_key: 系统参数名（业务字段）
 *   - field_name: 字段中文名称
 *   - wechat_field: 微信字段名
 * - example_content: 示例内容（用于日志显示）
 */

/**
 * 服务号消息  分为 模板消息 和 订阅消息  , 此处是模板消息的配置
 * 订阅消息 ： https://developers.weixin.qq.com/doc/service/api/notify/subscribe/api_templatesubscribe.html
 * 模板消息 ： https://developers.weixin.qq.com/doc/service/api/notify/template/api_sendtemplatemessage.html
 */
return [
    'user_order_delivery' => [
        'name' => '订单发货通知',
        'desc' => '用户订单发货后的通知',
        'template_id_short' => '42984',
        'keyword_name_list' => ['订单编号', '订单金额', '发货时间'],
        'category_name' => '百货/超市/便利店',
        'sceneDesc' => '订单交付(发货)',
        'template_fields' => [
            [
                'param_key' => 'order_sn',           // 系统对应的字段（业务参数名）
                'field_name' => '订单编号',           // 字段名称（中文描述）
                'wechat_field' => 'character_string2' // 微信字段（微信模板字段）
            ],
            [
                'param_key' => 'order_amount',
                'field_name' => '订单金额',
                'wechat_field' => 'amount8'
            ],
            [
                'param_key' => 'delivery_time',
                'field_name' => '发货时间',
                'wechat_field' => 'time12'
            ]
        ],
        'example_content' => '您的订单已发货\n订单编号：{{order_sn}}\n订单金额：{{order_amount}}\n发货时间：{{delivery_time}}'
    ],

];
