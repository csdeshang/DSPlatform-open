
DROP TABLE IF EXISTS `#__admin`;
CREATE TABLE `#__admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员自增ID',
  `username` varchar(32) NOT NULL COMMENT '管理员名称',
  `password` varchar(128) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `login_time` int(11) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `login_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `is_super` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否超级管理员',
  `role_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '权限组ID',
  `create_at` int(11) NOT NULL,
  `create_by` varchar(20) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_username` (`username`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';


DROP TABLE IF EXISTS `#__admin_logs`;
CREATE TABLE `#__admin_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `username` varchar(32) DEFAULT NULL COMMENT '管理员',
  `ip` varchar(32) DEFAULT NULL,
  `method` varchar(64) DEFAULT NULL COMMENT '访问类型',
  `root` varchar(64) DEFAULT NULL COMMENT '访问根目录',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器',
  `action` varchar(64) DEFAULT NULL COMMENT '方法',
  `url` varchar(1024) DEFAULT NULL COMMENT '访问地址',
  `params` text COMMENT '请求参数',
  `result` mediumtext COMMENT '请求结果',
  `duration` int(11) DEFAULT NULL COMMENT '请求耗时(毫秒)',
  `http_code` varchar(10) DEFAULT NULL COMMENT 'HTTP状态',
  `code` varchar(10) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_username` (`username`),
  KEY `idx_ip` (`ip`),
  KEY `idx_controller` (`controller`),
  KEY `idx_action` (`action`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_http_code` (`http_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员日志';


DROP TABLE IF EXISTS `#__admin_menu`;
CREATE TABLE `#__admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员权限',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `path` varchar(128) NOT NULL COMMENT '路由地址',
  `name` varchar(128) NOT NULL COMMENT '路由的唯一标识符',
  `component` varchar(128) NOT NULL COMMENT '组件',
  `title` varchar(32) DEFAULT NULL COMMENT '显示名称',
  `icon` varchar(32) NOT NULL COMMENT '图标',
  `api_url` varchar(128) NOT NULL COMMENT '权限API地址',
  `type` varchar(10) NOT NULL COMMENT '类型 directory 目录 menu 菜单 button 按钮',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示 1显示',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可用 1可用',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统菜单';



INSERT INTO `#__admin_menu` (`id`, `pid`, `path`, `name`, `component`, `title`, `icon`, `api_url`, `type`, `sort`, `is_show`, `is_enabled`, `create_at`, `update_at`) VALUES
(1, 0, 'admin/dashboard', 'admin_dashboard', 'pages-admin/main/views/home/index', '主页', 'iconfont icon-home', '121221', 'menu', 1, 1, 1, 1741333364, 1741333364),
(2, 0, 'admin/rule', 'admin_rule', '', '权限', 'iconfont icon-setting', '', 'directory', 3, 1, 1, 1741333433, 1741333433),
(3, 2, 'admin/list', 'admin_list', 'pages-admin/main/views/admin/admin/index', '管理员', 'iconfont icon-adduser', '211212', 'menu', 255, 1, 1, 1741333488, 1741333488),
(4, 2, 'admin/roles', 'admin_roles', 'pages-admin/main/views/admin/role/index', '角色', 'iconfont icon-switchuser', '111', 'menu', 255, 1, 1, 1741333529, 1741333529),
(5, 2, 'admin/menus', 'admin_menus', 'pages-admin/main/views/admin/menu/index', '菜单管理', 'element DishDot', '1111', 'menu', 255, 1, 1, 1741333887, 1741333887),
(6, 2, 'admin/logs', 'admin_logs', 'pages-admin/main/views/admin/log/index', '管理员日志', 'element MostlyCloudy', '1111', 'menu', 255, 1, 1, 1741334066, 1741334066),
(7, 0, 'admin/setting', 'admin_setting', '', '设置', 'element Setting', '', 'directory', 20, 1, 0, 1741334144, 1741334144),
(8, 7, ' admin/setting/config', ' admin_setting_config', '', '网站设置', 'element AlarmClock', '', 'directory', 1, 1, 0, 1741334164, 1741334164),
(9, 8, 'admin/setting/base', 'admin_setting_base', 'pages-admin/main/views/setting/config/base', '基础设置', 'element AlarmClock', '111', 'menu', 255, 1, 0, 1741334226, 1741334226),
(10, 8, 'admin/setting/login', 'admin_setting_login', 'pages-admin/main/views/setting/config/login', '登录设置', 'element Apple', '122121', 'menu', 255, 1, 1, 1741334269, 1741334269),
(11, 7, 'admin/setting/area', 'admin_setting_area', 'pages-admin/main/views/system/area/index', '地区设置', 'element Apple', '122121', 'menu', 255, 1, 1, 1741334326, 1741334326),
(12, 17, 'admin/setting/payment', 'admin_setting_payment', '', '支付设置', 'iconfont icon-alipay', '', 'directory', 255, 1, 0, 1741334401, 1741334401),
(13, 12, 'admin/setting/payment/config', 'admin_setting_payment_config', 'pages-admin/main/views/trade/paymentConfig/config', '平台支付设置', 'element AddLocation', '111', 'menu', 255, 1, 0, 1741334481, 1741334481),
(14, 7, 'admin/setting/express', 'admin_setting_express', 'pages-admin/main/views/system/express/index', '物流公司', 'element AddLocation', '11111111', 'menu', 255, 1, 0, 1741334538, 1741334538),
(15, 0, 'adimn/user', 'adimn_user', '', '会员', 'element User', '', 'directory', 4, 1, 1, 1741334581, 1747667579),
(16, 15, 'admin/user/list', 'admin_user_list', 'pages-admin/main/views/user/user/list', '会员列表', 'element User', '1111', 'menu', 255, 1, 0, 1741334658, 1741334658),
(17, 0, 'admin/ finance', 'admin_ finance', '', '财务', 'iconfont icon-moneycollect', '', 'directory', 5, 1, 0, 1741334712, 1747667590),
(18, 51, 'admin/user/balance', 'admin_user_balance', 'pages-admin/main/views/user/balance/list', '会员余额', 'element AddLocation', '1212', 'menu', 255, 1, 0, 1741334879, 1747667626),
(19, 0, 'admin/mall', 'admin_mall', '', '商城平台', 'iconfont icon-shop', '', 'directory', 255, 1, 0, 1741334990, 1757592180),
(20, 19, 'admin/mall/store', 'admin_mall_store', '', '店铺管理', 'element Burger', '', 'directory', 255, 1, 1, 1741335048, 1757592192),
(21, 20, 'admin/mall/store/list', 'admin_mall_store_list', 'pages-admin/platform/mall/views/store/store/list', '店铺列表', 'iconfont icon-unorderedlist', '12', 'menu', 255, 1, 1, 1741335159, 1757592226),
(22, 20, 'admin/mall/store/category', 'admin_mall_store_category', 'pages-admin/platform/mall/views/store/category/list', '店铺分类', 'iconfont icon-safetycertificate', '212112', 'menu', 255, 1, 1, 1741335281, 1757592236),
(23, 19, 'admin/mall/goods', 'admin_mall_goods', '', '商品管理', 'element Goods', '', 'directory', 255, 1, 1, 1741335362, 1757592202),
(24, 23, 'admin/mall/goods/category', 'admin_mall_goods_category', 'pages-admin/platform/mall/views/goods/category/list', '商品分类', 'cate', '1221', 'menu', 255, 1, 1, 1741335484, 1741335484),
(25, 23, 'admin/mall/goods/list', 'admin_mall_goods_list', 'pages-admin/platform/mall/views/goods/goods/list', '商品列表', 'element Goods', '111', 'menu', 255, 1, 1, 1741335595, 1741335595),
(26, 23, 'admin/mall/goods/brand', 'admin_mall_goods_brand', 'pages-admin/platform/mall/views/goods/brand/list', '商品品牌', 'iconfont icon-codelibrary', '111', 'menu', 255, 1, 1, 1741335656, 1741335656),
(27, 19, 'admin/mall/order', 'admin_mall_order', '', '订单管理', 'element Monitor', '', 'directory', 255, 1, 1, 1741335737, 1757592210),
(28, 27, 'admin/mall/order/list', 'admin_mall_order_list', 'pages-admin/platform/mall/views/order/order/list', '订单列表', 'element ScaleToOriginal', '234', 'menu', 255, 1, 1, 1741335796, 1741335796),
(29, 0, 'admin/system', 'admin_system', '', '维护', 'element AddLocation', '', 'directory', 30, 1, 1, 1741335817, 1747667764),
(30, 29, 'admin/system/logs', 'admin_system_logs', '', '日志', 'element Brush', '', 'directory', 255, 1, 1, 1741335845, 1747667770),
(31, 30, 'admin/system/error-logs', 'admin_system_error-logs', 'pages-admin/main/views/system/errorLogs/index', '错误日志', 'element AddLocation', '211212', 'menu', 255, 1, 1, 1741335972, 1747667782),
(32, 30, 'admin/system/access-logs', 'admin_system_access-logs', 'pages-admin/main/views/system/accessLogs/index', '访问日志', 'element Aim', '12212121', 'menu', 255, 1, 1, 1741336020, 1747667788),
(33, 0, 'admin/merchant', 'admin_merchant', 'pages-admin/main/views/merchant/merchant/index', '商户', 'iconfont icon-shop', '1111111111', 'menu', 6, 1, 0, 1741336068, 1747667700),
(34, 33, 'admin/merchant/list', 'admin_merchant_list', 'pages-admin/main/views/merchant/merchant/index', '商户列表', 'element ArrowDown', '122121', 'menu', 255, 1, 0, 1741336114, 1747667709),
(35, 52, 'admin/merchant/balance', 'admin_merchant_balance', 'pages-admin/main/views/merchant/balance/index', '商户流水', 'element Apple', '2121', 'menu', 255, 1, 0, 1741336161, 1747667653),
(36, 59, 'admin/rider', 'admin_rider', '', '骑手', 'element AlarmClock', '', 'directory', 7, 1, 0, 1741336182, 1757592311),
(37, 36, 'admin/rider/list', 'admin_rider_list', 'pages-admin/main/views/rider/rider/index', '骑手列表', 'element Apple', '12211221', 'menu', 255, 1, 1, 1741336226, 1747667730),
(38, 36, 'admin/rider/balance', 'admin_rider_balance', 'pages-admin/main/views/rider/balance/index', '骑手流水', 'element AlarmClock', '21121221', 'menu', 255, 1, 1, 1741336298, 1747667740),
(39, 12, 'admin/setting/payment/merchant', 'admin_setting_payment_merchant', 'pages-admin/main/views/trade/paymentConfig/merchant', '收款设置', 'element Apple', '23', 'menu', 255, 1, 1, 1741416345, 1741416345),
(40, 0, 'admin/editable', 'admin_editable', '', '装修', 'element Aim', '', 'directory', 8, 1, 1, 1741879078, 1741879078),
(41, 40, 'admin/editable/index', 'admin_editable_index', 'pages-admin/main/views/editable/h5/index', 'H5装修', 'element AddLocation', '111', 'menu', 255, 1, 1, 1741881987, 1741881987),
(42, 40, 'admin/editable/edit', 'admin_editable_edit', 'pages-admin/main/views/editable/h5/edit', 'H5装修编辑', 'element AlarmClock', '1111111111', 'menu', 255, 0, 1, 1741883327, 1741883327),
(43, 7, 'admin/setting/platform', 'admin_setting_platform', 'pages-admin/main/views/system/platform/index', '平台设置', 'element Bicycle', '111111111111111', 'menu', 255, 1, 1, 1742221059, 1742221059),
(44, 15, 'admin/user/growth', 'admin_user_growth', '', '会员等级', 'element Brush', '', 'directory', 255, 1, 1, 1742230725, 1742230725),
(45, 15, 'admin/user/points', 'admin_user_points', '', '会员积分', 'element Microphone', '', 'directory', 255, 1, 1, 1742230752, 1742230752),
(46, 44, 'admin/user/growth/level', 'admin_user_growth_level', 'pages-admin/main/views/user/growth/level', '会员等级', 'element Aim', '111', 'menu', 255, 1, 1, 1742230877, 1742230877),
(47, 44, 'admin/user/growth/rules', 'admin_user_growth_rules', 'pages-admin/main/views/user/growth/rules', '等级规则', 'element AddLocation', '11111111111', 'menu', 255, 1, 1, 1742230914, 1742230914),
(48, 44, 'admin/user/growth/logs', 'admin_user_growth_logs', 'pages-admin/main/views/user/growth/logs', '成长值', 'element ArrowDown', '1111111111111', 'menu', 255, 1, 1, 1742230953, 1742230953),
(49, 45, 'admin/user/points/logs', 'admin_user_points_logs', 'pages-admin/main/views/user/points/logs', '积分日志', 'element AddLocation', '111111111111111', 'menu', 255, 1, 1, 1742230985, 1742230985),
(50, 45, 'admin/user/points/rules', 'admin_user_points_rules', 'pages-admin/main/views/user/points/rules', '规则配置', 'element Apple', '1111111111111111', 'menu', 255, 1, 1, 1742231019, 1742231019),
(51, 17, 'admin/finance/user/', 'admin_finance_user', '', '用户资金', 'element School', '', 'directory', 255, 1, 1, 1742367709, 1747667599),
(52, 17, 'admin/finance/merchant', 'admin_finance_merchant', '', '商户资金', 'element AddLocation', '', 'directory', 255, 1, 1, 1742367974, 1747667610),
(53, 51, 'admin/user/recharge', 'admin_user_recharge', 'pages-admin/main/views/user/recharge/logs', '充值记录', 'element Bowl', '1111111111111111', 'menu', 255, 1, 1, 1742373490, 1747667633),
(54, 51, 'admin/user/withdrawal', 'admin_user_withdrawal', 'pages-admin/main/views/user/withdrawal/logs', '提现记录', 'element Camera', '11111111111111111', 'menu', 255, 1, 1, 1742373548, 1747667642),
(55, 17, 'admin/trade', 'admin_trade', '', '交易管理', 'element Aim', '', 'directory', 255, 1, 1, 1742373664, 1747667669),
(56, 55, 'admin/trade/pay', 'admin_trade_pay', 'pages-admin/main/views/trade/pay/logs', '支付日志', 'element Monitor', '111111111', 'menu', 255, 1, 1, 1742373931, 1747667676),
(57, 55, 'admin/trade/transfer', 'admin_trade_transfer', 'pages-admin/main/views/trade/transfer/logs', '转账日志', 'element Bell', '111111', 'menu', 255, 1, 1, 1742373979, 1747667683),
(58, 55, 'admin/trade/refund', 'admin_trade_refund', 'pages-admin/main/views/trade/refund/logs', '退款日志', 'element Bell', '1111111111111', 'menu', 255, 1, 1, 1742374013, 1747667691),
(59, 0, 'admin/food', 'admin_food', '', '外卖平台', 'element Food', '', 'directory', 255, 1, 1, 1742378545, 1742378545),
(60, 0, 'admin/house', 'admin_house', '', '家政平台', 'iconfont icon-home', '', 'directory', 255, 1, 1, 1742379872, 1742379872),
(61, 0, 'admin/kms', 'admin_kms', '', '教育平台', 'element VideoCamera', '', 'directory', 255, 1, 1, 1742380049, 1742380049),
(62, 27, 'admin_mall/order/refund', 'admin_mall_order_refund', 'pages-admin/platform/mall/views/order/refund/list', '退款管理', 'iconfont icon-unorderedlist', '11111111111', 'menu', 255, 1, 1, 1742387476, 1742387476),
(63, 40, 'admin/editable/nav', 'admin_editable_nav', 'pages-admin/main/views/editable/h5/bottom-nav', '底部导航', 'element Burger', '11111111111111', 'menu', 255, 1, 1, 1742563853, 1742563853),
(64, 7, 'admin/setting/goods', 'admin_setting_goods', 'pages-admin/main/views/setting/goods/setting', '商品设置', 'element Setting', '11', 'menu', 2, 1, 1, 1743865928, 1743865928),
(65, 7, 'admin/system/lbs/provider', 'admin_system_lbs_provider', 'pages-admin/main/views/system/lbsProvider/provider-list', '地图设置', 'element MapLocation', '1', 'menu', 255, 1, 1, 1743866301, 1743866301),
(66, 7, 'admin/system/storage/provider', 'admin_system_storage_provider', 'pages-admin/main/views/system/storageProvider/provider-list', '存储设置', 'element MostlyCloudy', '11', 'menu', 255, 1, 1, 1743866578, 1743866578),
(67, 7, 'admin/system/agreement', 'admin_system_agreement', 'pages-admin/main/views/system/agreement/index', '协议设置', 'element Document', '111', 'menu', 255, 1, 1, 1743866889, 1743866889),
(68, 7, 'admin/setting/order', 'admin_setting_order', 'pages-admin/main/views/setting/order/setting', '订单设置', 'element Tickets', '1', 'menu', 255, 1, 1, 1743868430, 1743868430),
(69, 7, 'admin/setting/notice', 'admin_setting_notice', '', '消息管理', 'element Message', '', 'directory', 255, 1, 1, 1743868883, 1743868883),
(70, 69, 'admin/setting/notice/tpl', 'admin_setting_notice_tpl', 'pages-admin/main/views/system/notice/tpl', '消息模板', 'element MessageBox', '111', 'menu', 255, 1, 1, 1743868935, 1743868935),
(71, 7, 'admin/setting/sms', 'admin_setting_sms', '', '短信管理', 'element Iphone', '', 'directory', 255, 1, 1, 1743869208, 1743869208),
(72, 71, 'admin/sms/provider', 'admin_sms_provider', 'pages-admin/main/views/system/smsProvider/provider-list', '短信设置', 'element Setting', '111', 'menu', 255, 1, 1, 1743869261, 1743869261),
(73, 71, 'admin/sms/logs', 'admin_sms_logs', 'pages-admin/main/views/system/sms/logs', '短信记录', 'element MessageBox', '1111', 'menu', 255, 1, 1, 1743869328, 1743869328),
(74, 23, 'admin/mall/goods/comment', 'admin_mall_goods_comment', 'pages-admin/platform/mall/views/goods/comment/list', '评论管理', 'element Comment', '1111', 'menu', 255, 1, 1, 1743869840, 1743869840),
(75, 59, 'admin/food/store', 'admin_food_store', '', '店铺管理', 'element Burger', '', 'directory', 255, 1, 1, 1743870889, 1743870889),
(76, 75, 'admin/food/store/list', 'admin_food_store_list', 'pages-admin/platform/food/views/store/store/list', '店铺列表', 'element AddLocation', '1', 'menu', 255, 1, 1, 1743870973, 1743870973),
(77, 75, 'admin/food/store/category', 'admin_food_store_category', 'pages-admin/platform/food/views/store/category/list', '店铺分类', 'iconfont icon-safetycertificate', '111', 'menu', 255, 1, 1, 1743871035, 1743871035),
(78, 59, 'admin/food/goods', 'admin_food_goods', '', '商品管理', 'element Goods', '', 'directory', 255, 1, 1, 1743871078, 1743871078),
(79, 78, 'admin/food/goods/category', 'admin_food_goods_category', 'pages-admin/platform/food/views/goods/category/list', '商品分类', 'cate', '111', 'menu', 255, 1, 1, 1743871156, 1743871156),
(80, 78, 'admin/food/goods/list', 'admin_food_goods_list', 'pages-admin/platform/food/views/goods/goods/list', '商品列表', 'element Goods', '1111', 'menu', 255, 1, 1, 1743871218, 1743871218),
(81, 78, 'admin/food/goods/brand', 'admin_food_goods_brand', 'pages-admin/platform/food/views/goods/brand/list', '商品品牌', 'iconfont icon-codelibrary', '11', 'menu', 255, 1, 1, 1743871274, 1743871274),
(82, 78, 'admin/food/goods/comment', 'admin_food_goods_comment', 'pages-admin/platform/food/views/goods/comment/list', '评论管理', 'element Comment', '111', 'menu', 255, 1, 1, 1743871316, 1743871316),
(83, 59, 'admin/food/order', 'admin_food_order', '', '订单管理', 'element Monitor', '', 'directory', 255, 1, 1, 1743871359, 1743871359),
(84, 83, 'admin/food/order/list', 'admin_food_order_list', 'pages-admin/platform/food/views/order/order/list', '订单列表', 'element ScaleToOriginal', '11', 'menu', 255, 1, 1, 1743871462, 1743871462),
(85, 83, 'admin/food/order/refund', 'admin_food_order_refund', 'pages-admin/platform/food/views/order/refund/list', '退款管理', 'iconfont icon-unorderedlist', '111', 'menu', 255, 1, 1, 1743871498, 1743871498),
(86, 60, 'admin/house/store', 'admin_house_store', '', '店铺管理', 'element Burger', '', 'directory', 255, 1, 1, 1743871985, 1743871985),
(87, 86, 'admin/house/store/list', 'admin_house_store_list', 'pages-admin/platform/house/views/store/store/list', '店铺列表', 'iconfont icon-unorderedlist', '111', 'menu', 255, 1, 1, 1743872559, 1743872559),
(88, 86, 'admin/house/store/category', 'admin_house_store_category', 'pages-admin/platform/house/views/store/category/list', '店铺分类', 'iconfont icon-safetycertificate', '111', 'menu', 255, 1, 1, 1743872608, 1743872608),
(89, 60, 'admin/house/goods', 'admin_house_goods', '', '商品管理', 'element Goods', '', 'directory', 255, 1, 1, 1743872655, 1743872655),
(90, 89, 'admin/house/goods/category', 'admin_house_goods_category', 'pages-admin/platform/house/views/goods/category/list', '商品分类', 'cate', '111', 'menu', 255, 1, 1, 1743872695, 1743872695),
(91, 89, 'admin/house/goods/list', 'admin_house_goods_list', 'pages-admin/platform/house/views/goods/goods/list', '商品列表', 'element Goods', '11', 'menu', 255, 1, 1, 1743872726, 1743872726),
(92, 89, 'admin/house/goods/brand', 'admin_house_goods_brand', 'pages-admin/platform/house/views/goods/brand/list', '商品品牌', 'iconfont icon-codelibrary', '1', 'menu', 255, 1, 1, 1743872770, 1743872770),
(93, 89, 'admin/house/goods/comment', 'admin_house_goods_comment', 'pages-admin/platform/house/views/goods/comment/list', '评论管理', 'element Comment', '11', 'menu', 255, 1, 1, 1743872799, 1743872799),
(94, 60, 'admin/house/order', 'admin_house_order', '', '订单管理', 'element Monitor', '', 'directory', 255, 1, 1, 1743872832, 1743872832),
(95, 94, 'admin/house/order/list', 'admin_house_order_list', 'pages-admin/platform/house/views/order/order/list', '订单列表', 'element ScaleToOriginal', '11', 'menu', 255, 1, 1, 1743872868, 1743872868),
(96, 94, 'admin/house/order/refund', 'admin_house_order_refund', 'pages-admin/platform/house/views/order/refund/list', '退款管理', 'iconfont icon-unorderedlist', '111', 'menu', 255, 1, 1, 1743872897, 1743872897),
(97, 61, 'admin/kms/store', 'admin_kms_store', '', '店铺管理', 'element Burger', '', 'directory', 255, 1, 1, 1743872948, 1743872948),
(98, 97, 'admin/kms/store/list', 'admin_kms_store_list', 'pages-admin/platform/kms/views/store/store/list', '店铺列表', 'iconfont icon-unorderedlist', '1', 'menu', 255, 1, 1, 1743873135, 1743873135),
(99, 97, 'admin/kms/store/category', 'admin_kms_store_category', 'pages-admin/platform/kms/views/store/category/list', '店铺分类', 'iconfont icon-safetycertificate', '1', 'menu', 255, 1, 1, 1743873168, 1743873168),
(100, 61, 'admin/kms/goods', 'admin_kms_goods', '', '商品管理', 'element Goods', '', 'directory', 255, 1, 1, 1743873198, 1743873198),
(101, 100, 'admin/kms/goods/category', 'admin_kms_goods_category', 'pages-admin/platform/kms/views/goods/category/list', '商品分类', 'cate', '1', 'menu', 255, 1, 1, 1743873232, 1743873232),
(102, 100, 'admin/kms/goods/list', 'admin_kms_goods_list', 'pages-admin/platform/kms/views/goods/goods/list', '商品列表', 'element Goods', '1', 'menu', 255, 1, 1, 1743873267, 1743873267),
(103, 100, 'admin/kms/goods/brand', 'admin_kms_goods_brand', 'pages-admin/platform/kms/views/goods/brand/list', '商品品牌', 'iconfont icon-codelibrary', '1', 'menu', 255, 1, 1, 1743873298, 1743873298),
(104, 100, 'admin/kms/goods/comment', 'admin_kms_goods_comment', 'pages-admin/platform/kms/views/goods/comment/list', '评论管理', 'element Comment', '111', 'menu', 255, 1, 1, 1743873325, 1743873325),
(105, 61, 'admin/kms/order', 'admin_kms_order', '', '订单管理', 'element Monitor', '', 'directory', 255, 1, 1, 1743873364, 1743873364),
(106, 105, 'admin/kms/order/list', 'admin_kms_order_list', 'pages-admin/platform/kms/views/order/order/list', '订单列表', 'element ScaleToOriginal', '1', 'menu', 255, 1, 1, 1743873414, 1743873414),
(107, 105, 'admin/kms/order/refund', 'admin_kms_order_refund', 'pages-admin/platform/kms/views/order/refund/list', '退款管理', 'iconfont icon-unorderedlist', '111', 'menu', 255, 1, 1, 1743873447, 1743873447),
(108, 69, 'admin/setting/notice/logs', 'admin_setting_notice_logs', 'pages-admin/main/views/system/notice/logs', '消息记录', 'iconfont icon-unorderedlist', '1111', 'menu', 255, 1, 1, 1743944885, 1743944885),
(109, 7, 'admin/setting/email', 'admin_setting_email', 'pages-admin/main/views/setting/email/setting', '邮箱设置', 'iconfont icon-message', '1', 'menu', 255, 1, 1, 1744012736, 1744012736),
(110, 7, 'admin/article', 'admin_article', '', '系统文章', 'element DocumentRemove', '', 'directory', 255, 1, 1, 1744202426, 1757592702),
(111, 110, 'admin/article/list', 'admin_article_list', 'pages-admin/main/views/system/article/list', '文章列表', 'element Apple', '111111111111', 'menu', 255, 1, 1, 1744206084, 1744206084),
(112, 110, 'admin/article/category', 'admin_article_category', 'pages-admin/main/views/system/article/category-list', '文章分类', '11', '1111', 'menu', 255, 1, 1, 1744206106, 1744206106),
(113, 0, 'admin/wechat', 'admin_wechat', '', '微信配置', 'iconfont icon-wechat-fill', '', 'directory', 8, 1, 1, 1746174169, 1746174169),
(114, 113, 'admin/wechat/official', 'admin_wechat_official', 'pages-admin/main/views/wechat/official/index', '微信公众号', 'element Setting', '111', 'menu', 255, 1, 1, 1746175651, 1746175651),
(115, 113, 'admin/wechat/mini', 'admin_wechat_mini', 'pages-admin/main/views/wechat/mini/index', '微信小程序', 'element Setting', '1', 'menu', 255, 1, 1, 1746175694, 1746175694),
(116, 12, 'admin/setting/withdrawal', 'admin_setting_withdrawal', 'pages-admin/main/views/setting/withdrawal/setting', '用户提现设置', 'element Money', '1', 'menu', 255, 1, 1, 1746677415, 1746677415),
(117, 145, 'admin/distributor', 'admin_distributor', '', '分销', 'element Share', '', 'directory', 6, 1, 1, 1746886192, 1757593854),
(118, 117, 'admin/distributor/overview', 'admin_distributor_overview', 'pages-admin/main/views/distributor/overview', '分销概览', 'element User', '1', 'menu', 255, 1, 1, 1746886521, 1746886536),
(119, 120, 'admin/distributor/user/list', 'admin_distributor_user_list', 'pages-admin/main/views/distributor/user/index', '分销员', 'element User', '1', 'menu', 255, 1, 1, 1746886674, 1746887488),
(120, 117, 'admin/distributor/user', 'admin_distributor_user', '', '分销员', 'element User', '', 'directory', 255, 1, 1, 1746886715, 1746886759),
(121, 120, 'admin/distributor/user/apply', 'admin_distributor_user_apply', 'pages-admin/main/views/distributor/user/apply', '分销申请', 'element Burger', '1', 'menu', 255, 1, 1, 1746886876, 1746886876),
(122, 117, 'admin/distributor/setting', 'admin_distributor_setting', 'pages-admin/main/views/distributor/setting', '分销设置', 'element Setting', '1', 'menu', 255, 1, 1, 1746887028, 1746887028),
(123, 117, 'admin/distributor/level', 'admin_distributor_level', 'pages-admin/main/views/distributor/level/index', '分销等级', 'element Tickets', '1', 'menu', 255, 1, 1, 1746887165, 1746887165),
(124, 117, 'admin/distributor/goods', 'admin_distributor_goods', 'pages-admin/main/views/distributor/goods/index', '分销商品', 'element Goods', '1', 'menu', 255, 1, 1, 1746887327, 1746887327),
(125, 117, 'admin/distributor/order', 'admin_distributor_order', 'pages-admin/main/views/distributor/order/index', '分销订单', 'element Monitor', '11', 'menu', 255, 1, 1, 1746887373, 1746887373),
(126, 117, 'admin/distributor/balance', 'admin_distributor_balance', 'pages-admin/main/views/distributor/balance/index', '分销流水', 'element Money', '11', 'menu', 255, 1, 1, 1746887555, 1746887555),
(127, 36, 'admin/rider/feeRule', 'admin_rider_feeRule', 'pages-admin/main/views/rider/feeRule/index', '配送费规则', 'element Brush', '111', 'menu', 255, 1, 1, 1747667893, 1747667893),
(128, 36, 'admin/rider/order/index', 'admin_rider_order_index', 'pages-admin/main/views/rider/order/index', '配送订单', 'iconfont icon-file', '1111111', 'menu', 255, 1, 1, 1747668014, 1747668014),
(129, 0, 'admin/video', 'admin_video', '', '短视频', 'element VideoCamera', '', 'directory', 255, 1, 1, 1749360414, 1749360414),
(130, 129, 'admin/video/blogger/index', 'admin_video_blogger_index', 'pages-admin/main/views/video/blogger/index', '博主管理', 'element User', '1111111', 'menu', 255, 1, 1, 1749360703, 1749360703),
(131, 129, 'admin/video/short', 'admin_video_short', '', '短视频', 'element VideoPlay', '', 'directory', 255, 1, 1, 1749360757, 1749360865),
(132, 131, 'admin/video/short/index', 'admin_video_short_index', 'pages-admin/main/views/video/short/index', '短视频', 'element VideoPlay', '1111', 'menu', 255, 1, 1, 1749360860, 1749360860),
(133, 131, 'admin/video/short/category', 'admin_video_short_category', 'pages-admin/main/views/video/short/category', '短视频分类', 'element Suitcase', '1111', 'menu', 255, 1, 1, 1749360925, 1749360925),
(134, 36, 'admin/rider/comment/index', 'admin_rider_comment_index', 'pages-admin/main/views/rider/comment/index', '骑手评论', 'element User', '111', 'menu', 255, 1, 1, 1750399958, 1750399975),
(135, 60, 'admin/technician', 'admin_technician', '', '师傅', 'element User', '', 'directory', 7, 1, 1, 1750400023, 1757592282),
(136, 135, 'admin/technician/index', 'admin_technician_index', 'pages-admin/main/views/technician/technician/index', '师傅列表', 'element Apple', '11', 'menu', 255, 1, 1, 1750400109, 1750400109),
(137, 135, 'admin/technician/balance', 'admin_technician_balance', 'pages-admin/main/views/technician/balance/index', '师傅流水', 'element AlarmClock', '111', 'menu', 255, 1, 1, 1750400182, 1750400319),
(138, 135, 'admin/technician/order/index', 'admin_technician_order_index', 'pages-admin/main/views/technician/order/index', '师傅订单', 'element Monitor', '111', 'menu', 255, 1, 1, 1750400257, 1750400257),
(139, 135, 'admin/technician/comment/index', 'admin_technician_comment_index', 'pages-admin/main/views/technician/comment/index', '师傅评论', 'iconfont icon-comment', '1111', 'menu', 255, 1, 1, 1750400384, 1750400384),
(140, 29, 'admin/system/clear', 'admin_system_clear', 'pages-admin/main/views/system/clear/index', '清除缓存', 'element DataLine', '1111', 'menu', 255, 1, 1, 1750585932, 1761826937),
(141, 142, 'admin/setting/printer/provider', 'admin_setting_printer_provider', 'pages-admin/main/views/system/printerProvider/provider-list', '服务商设置', 'element Printer', '111', 'menu', 255, 1, 1, 1753454046, 1753454630),
(142, 7, 'admin/setting/printer', 'admin_setting_printer', '', '打印机设置', 'element Printer', '', 'directory', 255, 1, 1, 1753454107, 1753454107),
(143, 142, 'admin/store/printer/list', 'admin_store_printer_list', 'pages-admin/main/views/store/printer/list', '店铺打印机', 'element ShoppingBag', '11', 'menu', 255, 1, 1, 1753454690, 1753454690),
(144, 142, 'admin/store/printer/logs', 'admin_store_printer_logs', 'pages-admin/main/views/store/printer/logs', '打印记录', 'element ChatLineSquare', '111', 'menu', 255, 1, 1, 1753454769, 1753454769),
(145, 0, 'admin/marketing', 'admin_marketing', '', '营销中心', 'element Burger', '', 'directory', 255, 1, 1, 1757593814, 1757593814),
(146, 145, 'admin/points-goods', 'admin_points-goods', '', '积分商品', 'element ArrowDown', '', 'directory', 255, 1, 1, 1757594292, 1757594316),
(147, 146, 'admin/points-goods/goods', 'admin_points-goods_goods', 'pages-admin/main/views/points-goods/goods/index', '积分商品', 'element AddLocation', '111', 'menu', 255, 1, 1, 1757594368, 1757594368),
(148, 146, 'admin/points-goods/category', 'admin_points-goods_category', 'pages-admin/main/views/points-goods/category/index', '分类', 'element Aim', '111', 'menu', 255, 1, 1, 1757594409, 1757594409),
(149, 146, 'admin/points-goods/order', 'admin_points-goods_order', 'pages-admin/main/views/points-goods/order/index', '订单', 'element AddLocation', '111', 'menu', 255, 1, 1, 1757594451, 1757594451),
(150, 146, 'admin/points-goods/evaluate', 'admin_points-goods_evaluate', 'pages-admin/main/views/points-goods/evaluate/index', '评价', 'element AddLocation', '111', 'menu', 255, 1, 1, 1757594504, 1757594504),
(151, 15, 'admin/behavior/logs', 'admin_behavior_logs', 'pages-admin/main/views/user/behavior/logs', '会员行为', 'element Bell', '1212', 'menu', 255, 1, 1, 1758896949, 1758896965),
(152, 7, 'admin/system/express/provider', 'admin_system_express_provider', 'pages-admin/main/views/system/expressProvider/provider-list', '物流接口', 'element AddLocation', '111', 'menu', 255, 1, 1, 1758896949, 1758896965),
(153, 29, 'admin/system/task-queue', 'admin_system_task-queue', 'pages-admin/main/views/system/task-queue/index', '消息队列', 'element ChatLineRound', '11', 'menu', 255, 1, 1, 1761827100, 1761827237);




DROP TABLE IF EXISTS `#__admin_role`;
CREATE TABLE `#__admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员角色',
  `name` varchar(32) NOT NULL COMMENT '角色名称',
  `desc` varchar(255) NOT NULL COMMENT '角色描述',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `rules` text NOT NULL COMMENT '权限规则',
  `create_at` int(11) NOT NULL COMMENT '添加时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';


DROP TABLE IF EXISTS `#__attachment`;
CREATE TABLE `#__attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '附件',
  `cid` int(11) NOT NULL COMMENT '附件分类ID',
  `user_id` int(11) NOT NULL COMMENT '附件使用ID member_id admin_id',
  `user_scene` tinyint(4) NOT NULL COMMENT '附件使用场景 0 admin 1 member',
  `type` varchar(10) NOT NULL COMMENT 'image 图片 video 视频 audio 音频 file 文件',
  `name` varchar(20) NOT NULL COMMENT '名称',
  `path` varchar(100) NOT NULL COMMENT '路径',
  `size` int(11) NOT NULL COMMENT '附件大小',
  `upload_type` varchar(10) NOT NULL COMMENT '附件存储方式 local aliyun',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_at` int(11) NOT NULL COMMENT '图片上传时间',
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_user_scene` (`user_scene`),
  KEY `idx_type` (`type`),
  KEY `idx_upload_type` (`upload_type`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_sort` (`sort`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件管理';




DROP TABLE IF EXISTS `#__attachment_cate`;
CREATE TABLE `#__attachment_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '附件分类',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `user_id` int(11) NOT NULL COMMENT '附件使用ID member_id admin_id',
  `user_scene` tinyint(4) NOT NULL COMMENT '附件使用场景 0 admin 1 member',
  `type` varchar(10) NOT NULL COMMENT 'image 图片 video 视频 audio 音频 file 文件',
  `name` varchar(20) NOT NULL COMMENT '名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_at` int(11) NOT NULL COMMENT '添加时间',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_user_scene` (`user_scene`),
  KEY `idx_type` (`type`),
  KEY `idx_pid` (`pid`),
  KEY `idx_name` (`name`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='附件分类管理';




DROP TABLE IF EXISTS `#__distributor_apply`;
CREATE TABLE `#__distributor_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `apply_time` int(11) NOT NULL COMMENT '申请时间',
  `apply_status` tinyint(4) NOT NULL COMMENT '申请状态 0申请中 1审核通过 2拒绝',
  `apply_remark` varchar(255) DEFAULT NULL COMMENT '申请备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_id` (`user_id`),
  KEY `idx_apply_status` (`apply_status`),
  KEY `idx_apply_time` (`apply_time`),
  KEY `idx_audit_time` (`audit_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销商申请表';


DROP TABLE IF EXISTS `#__distributor_balance_log`;
CREATE TABLE `#__distributor_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `distributor_user_id` int(11) NOT NULL COMMENT '分销商ID(即用户ID)',
  `related_id` int(11) NOT NULL COMMENT '关联ID 分销订单ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型 分销 团队奖励 提现等',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_amount` decimal(20,4) NOT NULL COMMENT '变动金额',
  `before_balance` decimal(20,4) NOT NULL COMMENT '交易前余额 对账',
  `after_balance` decimal(20,4) NOT NULL COMMENT '交易后的总额',
  `change_desc` varchar(255) NOT NULL COMMENT '变更描述',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_distributor_user_id` (`distributor_user_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销佣金记录表';


DROP TABLE IF EXISTS `#__distributor_goods`;
CREATE TABLE `#__distributor_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `sku_id` int(11) NOT NULL COMMENT 'sku ID',
  `distributor_level_id` int(11) NOT NULL COMMENT '分销等级ID',
  `goods_self_ratio` decimal(4,2) NOT NULL COMMENT '自购佣金比例',
  `goods_parent1_ratio` decimal(4,2) NOT NULL COMMENT '1级佣金比例',
  `goods_parent2_ratio` decimal(4,2) NOT NULL COMMENT '2级佣金比例',
  `goods_self_amount` decimal(20,4) NOT NULL COMMENT '自购佣金金额',
  `goods_parent1_amount` decimal(20,4) NOT NULL COMMENT '1级佣金金额',
  `goods_parent2_amount` decimal(20,4) NOT NULL COMMENT '2级佣金金额',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_distributor_level_id` (`distributor_level_id`),
  UNIQUE KEY `udx_goods_sku_level` (`goods_id`, `sku_id`, `distributor_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销商品表';




DROP TABLE IF EXISTS `#__distributor_level`;
CREATE TABLE `#__distributor_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `sort` int(11) NOT NULL COMMENT '级别',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `base_self_ratio` decimal(4,2) NOT NULL COMMENT '自购佣金比例',
  `base_parent1_ratio` decimal(4,2) NOT NULL COMMENT '1级佣金比例',
  `base_parent2_ratio` decimal(4,2) DEFAULT NULL COMMENT '2级佣金比例',
  `is_default` tinyint(4) NOT NULL DEFAULT 0 COMMENT '是否是默认等级,不需以下条件',
  `self_single_amount` decimal(20,4) NOT NULL COMMENT '自购单笔消费金额',
  `self_single_amount_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `self_total_amount` decimal(20,4) NOT NULL COMMENT '自购总消费金额',
  `self_total_amount_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `self_total_count` int(11) NOT NULL COMMENT '自购消费次数',
  `self_total_count_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `parent1_total_amount` decimal(20,4) NOT NULL COMMENT '一级分销订单总金额',
  `parent1_total_amount_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `parent1_total_count` int(11) NOT NULL COMMENT '一级分销订单总数',
  `parent1_total_count_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `invite_count` int(11) NOT NULL COMMENT '邀请注册人数',
  `invite_count_is` tinyint(4) NOT NULL COMMENT '是否开启',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_sort` (`sort`),
  KEY `idx_is_default` (`is_default`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销商等级表';


INSERT INTO `#__distributor_level` (`id`, `name`, `sort`, `description`, `base_self_ratio`, `base_parent1_ratio`, `base_parent2_ratio`, `is_default`, `self_single_amount`, `self_single_amount_is`, `self_total_amount`, `self_total_amount_is`, `self_total_count`, `self_total_count_is`, `parent1_total_amount`, `parent1_total_amount_is`, `parent1_total_count`, `parent1_total_count_is`, `invite_count`, `invite_count_is`, `create_at`, `update_at`) VALUES
(1, '初级分销商', 1, '默认分销商', '2.00', '2.00', '1.00', 1, '0.0000', 0, '0.0000', 0, 0, 0, '0.0000', 0, 0, 0, 0, 0, 1746954668, 1746954668);



DROP TABLE IF EXISTS `#__distributor_order`;
CREATE TABLE `#__distributor_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '对应商城的订单编号，用于关联商城订单系统',
  `order_goods_id` int(11) NOT NULL COMMENT '订单商品表ID',
  `distributor_user_id` int(11) NOT NULL COMMENT '产生该订单的分销商 用户ID',
  `user_id` int(11) NOT NULL COMMENT '下单用户的 ID',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `pay_price` decimal(20,4) NOT NULL COMMENT '支付金额,order_goods表的pay_price',
  `distributor_level_id` int(11) NOT NULL COMMENT '分销商等级ID',
  `commission_amount` decimal(20,4) NOT NULL COMMENT '该订单产生的佣金，根据分销商等级和订单商品计算得出',
  `commission_type` varchar(10) DEFAULT NULL COMMENT '佣金类型  self自购 parent1级 parent2级',
  `commission_status` tinyint(4) NOT NULL COMMENT '佣金状态，0未付款 1待结算，2已结算  3已失效',
  `commission_remark` varchar(255) DEFAULT NULL COMMENT '佣金备注',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_distributor_user_id` (`distributor_user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_order_goods_id` (`order_goods_id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_commission_status` (`commission_status`),
  KEY `idx_commission_type` (`commission_type`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='分销订单表';





DROP TABLE IF EXISTS `#__editable_page`;
CREATE TABLE `#__editable_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(10) NOT NULL COMMENT '应用类型 system系统 mall',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `title` varchar(32) NOT NULL COMMENT '页面标题',
  `type` varchar(10) DEFAULT NULL COMMENT '类型 首页 个人中心 专题',
  `page_config` mediumtext COMMENT '页面配置数据',
  `is_default` tinyint(4) NOT NULL COMMENT '是否为默认',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_is_default` (`is_default`),
  KEY `idx_type` (`type`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='可编辑页面';

INSERT INTO `#__editable_page` (`id`, `platform`, `store_id`, `title`, `type`, `page_config`, `is_default`, `create_at`, `update_at`) VALUES
(1, 'system', 0, '系统首页', 'home', '{\"globalSettings\":{\"title\":\"系统首页\",\"backgroundColor\":\"#f5f5f5\"},\"elementsConfig\":[{\"title\":\"搜索和轮播\",\"diyName\":\"diy-search-swiper\",\"icon\":\"element Search\",\"settings\":{\"logoSetting\":{\"is_show\":1,\"image\":\"\",\"name\":\"\"},\"lbsSetting\":{\"is_show\":1},\"searchSetting\":{\"placeholder\":\"德尚网络\"},\"swiperSetting\":{\"height\":320,\"autoplay\":true,\"interval\":3000,\"indicator_dots\":true},\"imagesList\":[{\"id\":\"nav_1744985027165_142\",\"image\":\"attachment/admin/1/image/202504/04/2025040401265467eec4de50de7.jpg\",\"title\":\"图片标题\",\"link\":\"\"},{\"id\":\"nav_1744985078897_243\",\"image\":\"attachment/admin/1/image/202504/04/2025040401265467eec4de36ef6.jpg\",\"title\":\"图片标题\",\"link\":\"\"},{\"id\":\"nav_1744985085207_171\",\"image\":\"attachment/admin/1/image/202504/04/2025040401265467eec4de92dce.jpg\",\"title\":\"图片标题\",\"link\":\"\"},{\"id\":\"nav_1744985093170_412\",\"image\":\"attachment/admin/1/image/202504/18/202504181802536802234da1268.png\",\"title\":\"图片标题\",\"link\":\"\"},{\"id\":\"nav_1744985100970_340\",\"image\":\"attachment/admin/1/image/202504/18/20250418180248680223488d4fd.png\",\"title\":\"图片标题\",\"link\":\"\"}],\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"rgba(245, 245, 245, 1)\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"导航栏\",\"diyName\":\"diy-nav-bar\",\"icon\":\"element Menu\",\"settings\":{\"navList\":[{\"id\":\"nav_1744985140307_304\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f9d92d.png\",\"title\":\"商城\",\"link\":\"/home/platform/mall/pages/index\"},{\"id\":\"nav_1744985155975_291\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237faee09.png\",\"title\":\"外卖\",\"link\":\"/home/platform/food/pages/index\"},{\"id\":\"nav_1744985176708_693\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f48bf2.png\",\"title\":\"教育\",\"link\":\"/home/platform/kms/pages/index\"},{\"id\":\"nav_1744985218039_949\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f697f5.png\",\"title\":\"家政\",\"link\":\"/home/platform/house/pages/index\"},{\"id\":\"nav_1744985251735_965\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f9d69e.png\",\"title\":\"个人中心\",\"link\":\"/home/pages/user/index/index\"},{\"id\":\"nav_1753861884839_273\",\"image\":\"attachment/admin/1/image/202507/30/202507301600446889d12c0978d.png\",\"title\":\"短视频\",\"link\":\"/video/pages/index\"}],\"navSettings\":{\"itemsPerRow\":5},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"rgba(255, 255, 255, 1)\",\"to\":null},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"新闻通知\",\"diyName\":\"diy-news\",\"icon\":\"element Document\",\"settings\":{\"newsList\":[{\"id\":\"nav_1744985555361_892\",\"title\":\"最新访问\",\"link\":\"/pages/article/detail?id=1\"},{\"id\":\"nav_1744985640108_576\",\"title\":\"快速上手\",\"link\":\"/pages/article/detail?id=2\"}],\"newsSetting\":{\"type\":\"text\",\"image\":\"\",\"title\":\"新闻资讯\",\"is_show_right_btn\":\"1\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"rgba(254, 254, 254, 1)\",\"to\":null},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"图片魔方\",\"diyName\":\"diy-image-cube\",\"icon\":\"element Picture\",\"settings\":{\"imageList\":[{\"image\":\"attachment/admin/1/image/202507/30/202507301619236889d58b8df6a.jpg\",\"link\":\"\"},{\"image\":\"attachment/admin/1/image/202507/30/202507301619276889d58fd884a.jpg\",\"link\":\"\"},{\"image\":\"attachment/admin/1/image/202507/30/202507301619276889d58fd884a.jpg\",\"link\":\"\"}],\"cubeSetting\":{\"layout\":\"style5\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"mall\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":true,\"header_title\":\"购物商城\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"图片魔方\",\"diyName\":\"diy-image-cube\",\"icon\":\"element Picture\",\"settings\":{\"imageList\":[{\"image\":\"attachment/admin/1/image/202507/30/202507301616426889d4ea76f25.jpg\",\"link\":\"\"}],\"cubeSetting\":{\"layout\":\"style1\",\"is_auto_height\":false,\"height\":50},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"food\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":true,\"header_title\":\"外卖\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"house\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":true,\"header_title\":\"家政\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"kms\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":true,\"header_title\":\"视频教育\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"系统文章\",\"diyName\":\"diy-sys-article\",\"icon\":\"element Document\",\"settings\":{\"articleSetting\":{\"style\":\"type1\",\"article_cid\":\"\",\"article_nums\":\"\",\"is_show_views\":true,\"is_show_date\":false,\"is_show_header_title\":true,\"header_title\":\"最新公告\",\"header_more_link\":\"/home/pages/index\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"行间距\",\"diyName\":\"diy-row-blank\",\"icon\":\"element TakeawayBox\",\"settings\":{\"blankSetting\":{\"blankHeight\":141},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}}],\"selectedElementIndex\":null}', 0, 1743591974, 1753866458),
(2, 'mall', 0, '多店铺首页', 'home', '{\"globalSettings\":{\"title\":\"多店铺首页\",\"backgroundColor\":\"#f5f5f5\"},\"elementsConfig\":[{\"title\":\"搜索\",\"diyName\":\"diy-search\",\"icon\":\"element Search\",\"settings\":{\"logoSetting\":{\"is_show\":0,\"image\":\"\",\"name\":\"\"},\"lbsSetting\":{\"is_show\":0},\"searchSetting\":{\"placeholder\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"轮播图\",\"diyName\":\"diy-swiper\",\"icon\":\"element Picture\",\"settings\":{\"swiperSetting\":{\"height\":330,\"autoplay\":true,\"interval\":3000,\"indicator_dots\":true},\"imagesList\":[{\"id\":\"nav_1744986750546_72\",\"image\":\"attachment/admin/1/image/202504/04/2025040401265467eec4de50de7.jpg\",\"title\":\"图片标题\",\"link\":\"\"},{\"id\":\"nav_1744986788245_205\",\"image\":\"attachment/admin/1/image/202504/04/2025040401265467eec4de36ef6.jpg\",\"title\":\"图片标题\",\"link\":\"\"}],\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":null,\"to\":null},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"导航栏\",\"diyName\":\"diy-nav-bar\",\"icon\":\"element Menu\",\"settings\":{\"navList\":[{\"id\":\"nav_1761568506058_120\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa9ee0.png\",\"title\":\"商品\",\"link\":\"/home/platform/mall/pages/search/goodslist/index\"},{\"id\":\"nav_1761568327860_625\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa7a82.png\",\"title\":\"店铺\",\"link\":\"/home/platform/mall/pages/search/storelist/index\"},{\"id\":\"nav_1744986832277_151\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f55dbc.png\",\"title\":\"商品分类\",\"link\":\"/home/platform/mall/pages/goods/category/index\"},{\"id\":\"nav_1744986868253_519\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f9d92d.png\",\"title\":\"店铺分类\",\"link\":\"/home/platform/mall/pages/search/storecategory/index\"},{\"id\":\"nav_1744986880722_5\",\"image\":\"attachment/admin/1/image/202504/04/2025040400455567eebb43c8d55.jpg\",\"title\":\"食品\",\"link\":\"\"},{\"id\":\"nav_1744987548620_777\",\"image\":\"attachment/admin/1/image/202504/04/2025040401094967eec0ddf3faa.jpg\",\"title\":\"电器\",\"link\":\"\"},{\"id\":\"nav_1744987566589_343\",\"image\":\"attachment/admin/1/image/202504/04/2025040400345567eeb8af6629e.jpg\",\"title\":\"面食\",\"link\":\"\"},{\"id\":\"nav_1744987612819_554\",\"image\":\"attachment/admin/1/image/202504/04/2025040400472767eebb9fafd23.png\",\"title\":\"酒水\",\"link\":\"\"},{\"id\":\"nav_1744987655723_940\",\"image\":\"attachment/admin/1/image/202504/04/2025040400435567eebacb1aca8.jpg\",\"title\":\"方便面\",\"link\":\"\"}],\"navSettings\":{\"itemsPerRow\":4},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"图片魔方\",\"diyName\":\"diy-image-cube\",\"icon\":\"element Picture\",\"settings\":{\"imageList\":[{\"image\":\"attachment/admin/1/image/202507/30/202507301616426889d4ea72ad1.gif\",\"link\":\"\"}],\"cubeSetting\":{\"layout\":\"style1\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"店铺列表\",\"diyName\":\"diy-store-list\",\"icon\":\"element ShoppingBag\",\"settings\":{\"storeSetting\":{\"platform\":\"mall\",\"style\":\"type1\",\"store_nums\":10,\"is_show_distance\":true,\"is_show_sales\":true,\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"mall\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}}],\"selectedElementIndex\":null}', 0, 1743699088, 1761568551),
(3, 'kms', 0, '教育首页', 'home', '{\"globalSettings\":{\"title\":\"教育首页\",\"backgroundColor\":\"#f5f5f5\"},\"elementsConfig\":[{\"title\":\"搜索\",\"diyName\":\"diy-search\",\"icon\":\"element Search\",\"settings\":{\"logoSetting\":{\"is_show\":0,\"image\":\"\",\"name\":\"\"},\"lbsSetting\":{\"is_show\":0},\"searchSetting\":{\"placeholder\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"轮播图\",\"diyName\":\"diy-swiper\",\"icon\":\"element Picture\",\"settings\":{\"swiperSetting\":{\"height\":300,\"autoplay\":true,\"interval\":3000,\"indicator_dots\":true},\"imagesList\":[{\"id\":\"nav_1744987936170_113\",\"image\":\"attachment/admin/1/image/202504/18/20250418180248680223488d4fd.png\",\"title\":\"图片标题\",\"link\":\"\"}],\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"导航栏\",\"diyName\":\"diy-nav-bar\",\"icon\":\"element Menu\",\"settings\":{\"navList\":[{\"id\":\"nav_1761568874296_442\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa7a82.png\",\"title\":\"店铺\",\"link\":\"/home/platform/kms/pages/search/storelist/index\"},{\"id\":\"nav_1761568902292_960\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa9ee0.png\",\"title\":\"商品\",\"link\":\"/home/platform/kms/pages/search/goodslist/index\"},{\"id\":\"nav_1744988014306_510\",\"image\":\"attachment/admin/1/image/202504/04/2025040401420067eec868073d2.png\",\"title\":\"商品分类\",\"link\":\"/home/platform/kms/pages/goods/category/index\"},{\"id\":\"nav_1744988018510_885\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f9d92d.png\",\"title\":\"店铺分类\",\"link\":\"/home/platform/kms/pages/search/storecategory/index\"},{\"id\":\"nav_1744988132752_378\",\"image\":\"attachment/admin/1/image/202504/04/2025040400361367eeb8fdd2479.png\",\"title\":\"家庭清洁\",\"link\":\"\"}],\"navSettings\":{\"itemsPerRow\":4},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"店铺列表\",\"diyName\":\"diy-store-list\",\"icon\":\"element ShoppingBag\",\"settings\":{\"storeSetting\":{\"platform\":\"kms\",\"style\":\"type1\",\"store_nums\":10,\"is_show_distance\":false,\"is_show_sales\":false,\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"kms\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}}],\"selectedElementIndex\":null}', 0, 1744363331, 1761568969),
(4, 'food', 0, '外卖首页', 'home', '{\"globalSettings\":{\"title\":\"外卖首页\",\"backgroundColor\":\"#f5f5f5\"},\"elementsConfig\":[{\"title\":\"搜索\",\"diyName\":\"diy-search\",\"icon\":\"element Search\",\"settings\":{\"logoSetting\":{\"is_show\":0,\"image\":\"\",\"name\":\"\"},\"lbsSetting\":{\"is_show\":1},\"searchSetting\":{\"placeholder\":\"搜索你需要的外卖\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"轮播图\",\"diyName\":\"diy-swiper\",\"icon\":\"element Picture\",\"settings\":{\"swiperSetting\":{\"height\":300,\"autoplay\":true,\"interval\":3000,\"indicator_dots\":true},\"imagesList\":[{\"id\":\"nav_1744989019376_856\",\"image\":\"attachment/admin/1/image/202504/18/202504181802536802234da1268.png\",\"title\":\"图片标题\",\"link\":\"\"}],\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"导航栏\",\"diyName\":\"diy-nav-bar\",\"icon\":\"element Menu\",\"settings\":{\"navList\":[{\"id\":\"nav_1761568737216_312\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa7a82.png\",\"title\":\"店铺\",\"link\":\"/home/platform/food/pages/search/storelist/index\"},{\"id\":\"nav_1744989035581_644\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237f9d92d.png\",\"title\":\"店铺分类\",\"link\":\"/home/platform/food/pages/search/storecategory/index\"},{\"id\":\"nav_1744989062526_15\",\"image\":\"attachment/admin/1/image/202504/19/2025041918260968037a41cd3f4.png\",\"title\":\"餐饮\",\"link\":\"\"},{\"id\":\"nav_1744989086625_89\",\"image\":\"attachment/admin/1/image/202504/19/2025041918272268037a8a0567d.png\",\"title\":\"饮品\",\"link\":\"\"},{\"id\":\"nav_1744989114533_620\",\"image\":\"attachment/admin/1/image/202504/19/2025041918272168037a89ece25.png\",\"title\":\"生鲜超市\",\"link\":\"\"},{\"id\":\"nav_1744989135671_648\",\"image\":\"attachment/admin/1/image/202504/19/2025041918272168037a89e3066.png\",\"title\":\"鲜花\",\"link\":\"\"},{\"id\":\"nav_1744989145245_34\",\"image\":\"attachment/admin/1/image/202504/19/2025041918260968037a41cd357.png\",\"title\":\"便利店\",\"link\":\"\"}],\"navSettings\":{\"itemsPerRow\":5},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"图片魔方\",\"diyName\":\"diy-image-cube\",\"icon\":\"element Picture\",\"settings\":{\"imageList\":[{\"image\":\"attachment/admin/1/image/202507/30/202507301616426889d4ea72492.gif\",\"link\":\"\"}],\"cubeSetting\":{\"layout\":\"style1\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"店铺列表\",\"diyName\":\"diy-store-list\",\"icon\":\"element ShoppingBag\",\"settings\":{\"storeSetting\":{\"platform\":\"food\",\"style\":\"type1\",\"store_nums\":10,\"is_show_distance\":false,\"is_show_sales\":false,\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"food\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}}],\"selectedElementIndex\":null}', 0, 1744363342, 1761568758),
(5, 'house', 0, '家政首页', 'home', '{\"globalSettings\":{\"title\":\"家政首页\",\"backgroundColor\":\"#f5f5f5\"},\"elementsConfig\":[{\"title\":\"搜索\",\"diyName\":\"diy-search\",\"icon\":\"element Search\",\"settings\":{\"logoSetting\":{\"is_show\":0,\"image\":\"\",\"name\":\"\"},\"lbsSetting\":{\"is_show\":1},\"searchSetting\":{\"placeholder\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"轮播图\",\"diyName\":\"diy-swiper\",\"icon\":\"element Picture\",\"settings\":{\"swiperSetting\":{\"height\":300,\"autoplay\":true,\"interval\":3000,\"indicator_dots\":true},\"imagesList\":[{\"id\":\"nav_1744989240726_492\",\"image\":\"attachment/admin/1/image/202504/18/20250418180248680223488d4fd.png\",\"title\":\"图片标题\",\"link\":\"\"}],\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":0,\"right\":0},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":false,\"all\":0,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"导航栏\",\"diyName\":\"diy-nav-bar\",\"icon\":\"element Menu\",\"settings\":{\"navList\":[{\"id\":\"nav_1761569148755_374\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa9ee0.png\",\"title\":\"商品\",\"link\":\"/home/platform/house/pages/search/goodslist/index\"},{\"id\":\"nav_1744989257890_874\",\"image\":\"attachment/admin/1/image/202504/18/202504181803436802237fa7a82.png\",\"title\":\"店铺\",\"link\":\"/home/platform/house/pages/search/storelist/index\"},{\"id\":\"nav_1761569494716_403\",\"image\":\"attachment/admin/1/image/202504/19/2025041918261068037a424c124.png\",\"title\":\"附近师傅\",\"link\":\"/home/pages/technician/list\"},{\"id\":\"nav_1744989271695_523\",\"image\":\"attachment/admin/1/image/202504/19/2025041918272268037a8a14940.png\",\"title\":\"家庭清洁\",\"link\":\"\"},{\"id\":\"nav_1744989290981_814\",\"image\":\"attachment/admin/1/image/202504/19/2025041918261068037a424c124.png\",\"title\":\"家庭护理\",\"link\":\"\"},{\"id\":\"nav_1744989303264_208\",\"image\":\"attachment/admin/1/image/202504/19/2025041918260968037a41cd3e6.png\",\"title\":\"家庭烹饪\",\"link\":\"\"},{\"id\":\"nav_1744989312143_989\",\"image\":\"attachment/admin/1/image/202504/19/2025041918272168037a89e3c54.png\",\"title\":\"家庭维修\",\"link\":\"\"},{\"id\":\"nav_1744989320520_130\",\"image\":\"attachment/admin/1/image/202504/19/2025041918264568037a6551e52.png\",\"title\":\"搬家服务\",\"link\":\"\"}],\"navSettings\":{\"itemsPerRow\":5},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"店铺列表\",\"diyName\":\"diy-store-list\",\"icon\":\"element ShoppingBag\",\"settings\":{\"storeSetting\":{\"platform\":\"house\",\"style\":\"type1\",\"store_nums\":10,\"is_show_distance\":false,\"is_show_sales\":false,\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"图片魔方\",\"diyName\":\"diy-image-cube\",\"icon\":\"element Picture\",\"settings\":{\"imageList\":[{\"image\":\"attachment/admin/1/image/202507/30/202507301616426889d4ea72492.gif\",\"link\":\"\"}],\"cubeSetting\":{\"layout\":\"style1\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"师傅列表\",\"diyName\":\"diy-technician-list\",\"icon\":\"element User\",\"settings\":{\"technicianSetting\":{\"style\":\"type1\",\"technician_nums\":\"20\",\"is_show_service_count\":true,\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}},{\"title\":\"商品列表\",\"diyName\":\"diy-goods-list\",\"icon\":\"element Goods\",\"settings\":{\"goodsSetting\":{\"platform\":\"house\",\"sort\":\"default\",\"nums\":10,\"source\":\"all\",\"goods_ids\":[],\"category_ids\":[],\"brand_ids\":[],\"is_show_header_title\":false,\"header_title\":\"头部标题自定义\",\"header_more_link\":\"\"},\"styleSetting\":{\"layout\":\"grid\"},\"baseStyles\":{\"margin\":{\"top\":0,\"bottom\":10,\"left\":10,\"right\":10},\"padding\":{\"top\":0,\"bottom\":0,\"left\":0,\"right\":0},\"font\":{\"color\":\"#333333\",\"size\":13,\"weight\":100},\"gbColor\":{\"from\":\"#ffffff\",\"to\":\"#ffffff\"},\"borderRadius\":{\"isAll\":true,\"all\":10,\"topLeft\":0,\"topRight\":0,\"bottomLeft\":0,\"bottomRight\":0}}}}],\"selectedElementIndex\":null}', 0, 1744363358, 1761569779);



DROP TABLE IF EXISTS `#__merchant`;
CREATE TABLE `#__merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商户ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(32) NOT NULL COMMENT '商户名称',
  `is_allow_payment` tinyint(4) DEFAULT '0' COMMENT '是否允许收款',
  `balance` decimal(20,4) DEFAULT '0.000000' COMMENT '可用金额',
  `balance_in` decimal(20,4) DEFAULT '0.000000' COMMENT '总收入',
  `balance_out` decimal(20,4) DEFAULT '0.000000' COMMENT '总支出',
  `contact_name` varchar(32) DEFAULT NULL COMMENT '联系人',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `contact_address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `is_enabled` tinyint(4) DEFAULT '0' COMMENT '状态 0 关闭 1开启',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `allowed_store_count` int(11) NOT NULL DEFAULT '1' COMMENT '允许开店的数量',
  `apply_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `apply_status` tinyint(4) DEFAULT NULL COMMENT '审核状态 0 审核中 1审核通过 2拒绝',
  `apply_remark` varchar(255) DEFAULT NULL COMMENT '申请备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '申请拒绝时间',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '申请拒绝原因',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_id` (`user_id`),
  UNIQUE KEY `udx_merchant_name` (`name`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_apply_status` (`apply_status`),
  KEY `idx_sort` (`sort`),
  KEY `idx_apply_time` (`apply_time`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商户表';



DROP TABLE IF EXISTS `#__merchant_balance_log`;
CREATE TABLE `#__merchant_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `related_id` int(11) NOT NULL COMMENT '关联ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型 系统 订单 退款',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_amount` decimal(20,4) NOT NULL COMMENT '变动金额',
  `before_balance` decimal(20,4) NOT NULL COMMENT '交易前余额 对账',
  `after_balance` decimal(20,4) NOT NULL COMMENT '交易后的总额',
  `change_desc` varchar(255) DEFAULT NULL COMMENT '变更描述',
  `create_at` int(11) NOT NULL COMMENT '变更添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商户流水表';


DROP TABLE IF EXISTS `#__rider`;
CREATE TABLE `#__rider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '关联的用户ID',
  `name` varchar(20) NOT NULL COMMENT '名称',
  `mobile` varchar(20) NOT NULL COMMENT '联系手机',
  `status` varchar(128) NOT NULL DEFAULT '0' COMMENT '状态 0休息 1接单 2忙碌',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评价次数',
  `avg_score` decimal(3,2) NOT NULL DEFAULT '5.00' COMMENT '评价平均分',
  `service_count` int(11) DEFAULT '0' COMMENT '服务次数',
  `balance` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT '可用余额',
  `balance_in` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT '收入总额',
  `balance_out` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT '支出总额',
  `is_enabled` tinyint(4) DEFAULT NULL COMMENT '是否可用',
  `rider_latitude` decimal(10,6) DEFAULT 0 COMMENT '骑手纬度',
  `rider_longitude` decimal(11,6) DEFAULT 0 COMMENT '骑手经度',
  `rider_loc_time` int(11) DEFAULT NULL COMMENT '骑手位置更新时间',
  `apply_status` tinyint(4) DEFAULT NULL COMMENT '审核状态 0 审核中 1审核通过 2拒绝',
  `apply_remark` varchar(255) DEFAULT NULL COMMENT '申请备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_id` (`user_id`),
  UNIQUE KEY `udx_mobile` (`mobile`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_status` (`status`),
  KEY `idx_apply_status` (`apply_status`),
  KEY `idx_name` (`name`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_audit_time` (`audit_time`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='骑手配送员表';


DROP TABLE IF EXISTS `#__rider_balance_log`;
CREATE TABLE `#__rider_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rider_id` int(11) NOT NULL COMMENT '配送员ID',
  `related_id` int(11) DEFAULT NULL COMMENT '关联ID 订单ID 提现至余额ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型 配送 奖励 罚款 提现',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_amount` decimal(20,4) NOT NULL COMMENT '变动金额',
  `before_balance` decimal(20,4) NOT NULL COMMENT '交易前余额 对账',
  `after_balance` decimal(20,4) NOT NULL COMMENT '交易后的总额',
  `change_desc` varchar(255) DEFAULT NULL COMMENT '变更描述',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_rider_id` (`rider_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='骑手流水表';


DROP TABLE IF EXISTS `#__rider_track`;
CREATE TABLE `#__rider_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rider_id` int(11) NOT NULL COMMENT '骑手ID',
  `latitude` decimal(10,6) NOT NULL COMMENT '纬度',
  `longitude` decimal(11,6) NOT NULL COMMENT '经度',
  `address` varchar(255) NOT NULL DEFAULT '',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_rider_id` (`rider_id`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_latitude` (`latitude`),
  KEY `idx_longitude` (`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='骑手轨迹图';



DROP TABLE IF EXISTS `#__rider_comment`;
CREATE TABLE `#__rider_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '评价用户ID',
  `rider_id` int(11) NOT NULL COMMENT '骑手ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `content` text NOT NULL COMMENT '评价内容',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示 0不显示 1显示',
  `is_anonymous` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否匿名 0不匿名 1匿名',
  `is_reply` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否回复 0未回复 1已回复',
  `reply_content` text COMMENT '回复内容',
  `reply_time` int(11) DEFAULT NULL COMMENT '回复时间',
  `service_score` tinyint(4) NOT NULL DEFAULT '5' COMMENT '服务评分 1-5分',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_rider_id` (`rider_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_create_at` (`create_at`),
  UNIQUE KEY `udx_user_order_rider` (`user_id`, `order_id`, `rider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='骑手评价表';


DROP TABLE IF EXISTS `#__rider_fee_rule`;
CREATE TABLE `#__rider_fee_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(20) NOT NULL COMMENT '规则名称',
  `base_fee` decimal(20,4) NOT NULL COMMENT '基础配送费',
  `distance_fee_type` tinyint(4) NOT NULL COMMENT '距离费用计算类型：1阶梯式 2连续式',
  `distance_rules` text NOT NULL COMMENT '距离规则JSON:如[{"min":0,"max":3,"fee":0},{"min":3,"max":5,"fee":1}]',
  `weight_fee_type` tinyint(4) NOT NULL COMMENT '重量费用计算类型：0不计算 1阶梯式 2连续式',
  `weight_rules` text COMMENT '重量规则JSON',
  `time_period_fee_type` tinyint(4) NOT NULL COMMENT '时段费用：0不计算 1按时段加价',
  `time_period_rules` text COMMENT '时段规则JSON:[{"start":"11:30","end":"13:30","fee":3}]',
  `weather_fee_type` tinyint(4) NOT NULL COMMENT '天气费：0不计算 1恶劣天气加价',
  `weather_rules` text COMMENT '天气规则JSON',
  `rider_fee_rate` decimal(4,2) NOT NULL COMMENT '骑手配送费抽成比例(%)',
  `is_enabled` tinyint(4) DEFAULT NULL COMMENT '是否启用',
  `area_id` int(11) NOT NULL COMMENT '适用区域 0 全部',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rule_name` (`rule_name`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_area_id` (`area_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='骑手配送费规则表';


INSERT INTO `#__rider_fee_rule` (`id`, `rule_name`, `base_fee`, `distance_fee_type`, `distance_rules`, `weight_fee_type`, `weight_rules`, `time_period_fee_type`, `time_period_rules`, `weather_fee_type`, `weather_rules`, `rider_fee_rate`, `is_enabled`, `area_id`, `create_at`, `update_at`) VALUES
(1, '默认规则', '3.0000', 1, '{\"step\":[{\"min\":0,\"max\":3,\"fee\":\"1\"}],\"continuous\":{\"start_value\":3,\"per_unit_fee\":1}}', 0, '{}', 0, NULL, 0, NULL, '10.00', 1, 0, 1747668583, 1747668583);



DROP TABLE IF EXISTS `#__sys_access_logs`;
CREATE TABLE `#__sys_access_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '管理员ID',
  `username` varchar(32) DEFAULT NULL COMMENT '管理员',
  `ip` varchar(32) DEFAULT NULL,
  `method` varchar(64) DEFAULT NULL COMMENT '访问类型',
  `root` varchar(64) DEFAULT NULL COMMENT '访问根目录',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器',
  `action` varchar(64) DEFAULT NULL COMMENT '方法',
  `url` varchar(1024) DEFAULT NULL COMMENT '访问地址',
  `params` text COMMENT '请求参数',
  `result` mediumtext COMMENT '请求结果',
  `duration` int(11) DEFAULT NULL COMMENT '请求耗时(毫秒)',
  `http_code` varchar(10) DEFAULT NULL COMMENT 'HTTP状态',
  `code` varchar(10) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_username` (`username`),
  KEY `idx_ip` (`ip`),
  KEY `idx_controller` (`controller`),
  KEY `idx_action` (`action`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_http_code` (`http_code`),
  KEY `idx_method` (`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员日志';

DROP TABLE IF EXISTS `#__sys_agreement`;
CREATE TABLE `#__sys_agreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '协议自增ID',
  `code` varchar(20) NOT NULL COMMENT '协议类型',
  `title` varchar(32) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `is_show` tinyint(4) DEFAULT NULL COMMENT '是否显示',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_code` (`code`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统协议表';

INSERT INTO `#__sys_agreement` (`id`, `code`, `title`, `content`, `sort`, `is_show`, `create_at`, `update_at`) VALUES
(1, 'user_service', '用户服务协议', '用户服务协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(2, 'user_privacy', '用户隐私政策', '用户隐私政策,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(3, 'merchant_service', '商户服务协议', '商户服务协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(5, 'rider_service', '骑手服务协议', '骑手服务协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(6, 'rider_safety', '骑手安全规范', '骑手安全规范,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(7, 'technician_service', '师傅服务协议', '师傅服务协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(8, 'technician_quality', '服务质量标准', '服务质量标准,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(9, 'blogger_service', '博主服务协议', '博主服务协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(10, 'blogger_content', '内容创作规范', '内容创作规范,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL),
(11, 'blogger_copyright', '版权声明协议', '版权声明协议,后台->系统设置->协议管理 进行编辑', 0, 1, NULL, NULL);




DROP TABLE IF EXISTS `#__sys_area`;
CREATE TABLE `#__sys_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地区自增ID',
  `name` varchar(50) NOT NULL COMMENT '地区名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '地区上级ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '地区排序',
  `deep` tinyint(4) NOT NULL DEFAULT '1' COMMENT '地区深度',
  `latitude` decimal(10,6) DEFAULT NULL COMMENT '纬度',
  `longitude` decimal(11,6) DEFAULT NULL COMMENT '经度',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否展示',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_name` (`name`),
  KEY `idx_sort` (`sort`),
  KEY `idx_deep` (`deep`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_pid_deep` (`pid`, `deep`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='地区表';


DROP TABLE IF EXISTS `#__sys_article`;
CREATE TABLE `#__sys_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(32) DEFAULT NULL COMMENT '标题',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `content` text COMMENT '内容',
  `publish_author` varchar(20) DEFAULT NULL COMMENT '发布作者',
  `publish_time` int(11) DEFAULT NULL COMMENT '发布时间',
  `virtual_views` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟浏览数',
  `actual_views` int(11) NOT NULL DEFAULT '0' COMMENT '实际浏览数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示 0不显示 1显示',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_title` (`title`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_publish_time` (`publish_time`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_publish_author` (`publish_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统文章表';

INSERT INTO `#__sys_article` (`id`, `cid`, `title`, `image`, `content`, `publish_author`, `publish_time`, `virtual_views`, `actual_views`, `sort`, `is_show`, `create_at`, `update_at`) VALUES
(1, 0, '功能强大，体验升级，满足你的一切想象', '', '<p><br></p>', '系统', 1681051952, 1000, 0, 0, 1, 1744210352, 1744210352),
(2, 0, '快速上手指南', '', '<p><br></p>', '', 1682866363, 1000, 0, 0, 1, 1744210363, 1744210363);


DROP TABLE IF EXISTS `#__sys_article_category`;
CREATE TABLE `#__sys_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` varchar(32) NOT NULL COMMENT '分类名称',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `description` varchar(255) NOT NULL COMMENT '分类描述',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_name` (`name`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统文章分类';



DROP TABLE IF EXISTS `#__sys_config`;
CREATE TABLE `#__sys_config` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `config_type` varchar(30) DEFAULT NULL COMMENT '配置项的类型',
  `config_key` varchar(30) NOT NULL COMMENT '配置项的键名',
  `config_value` text COMMENT '配置项的值',
  `description` varchar(100) DEFAULT '解释,备注',
  `updatet_at` int(11) NOT NULL COMMENT '更新时间',
  `update_by` varchar(32) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_config_type` (`config_type`),
  KEY `idx_config_key` (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='配置参数表';


INSERT INTO `#__sys_config` (`id`, `config_type`, `config_key`, `config_value`, `description`, `updatet_at`, `update_by`) VALUES
(1, 'website', 'api_url', 'https://dsplatform.csdeshang.com', 'API接口地址', 1737038688, NULL),
(2, 'website', 'pc_url', '', 'PC端地址', 1737038688, NULL),
(3, 'website', 'pc_store_url', 'https://dsplatform.store.csdeshang.com', 'PC端店铺管理地址', 1737038688, NULL),
(4, 'website', 'pc_merchant_url', 'https://dsplatform.merchant.csdeshang.com', 'PC端商家管理地址', 1737038688, NULL),
(5, 'website', 'h5_url', 'https://dsplatform.uniapp.csdeshang.com', 'H5端地址', 1737038688, NULL),
(6, 'website', 'h5_store_url', '', 'H5端店铺管理地址', 1737038688, NULL),
(7, 'website', 'h5_merchant_url', '', 'H5端商家管理地址', 1737038688, NULL),
(16, 'website', 'website_work_hours', '工作日：8:30—18:00', '工作时间', 1737038688, NULL),
(17, 'website', 'website_qrcode', '', '联系二维码', 1737038688, NULL),
(18, 'website', 'website_phone', '', '联系电话', 1737038688, NULL),
(19, 'website', 'website_address', '', '地址', 1737038688, NULL),
(20, 'website', 'website_email', '', '邮箱', 1737038688, NULL),
(21, 'website', 'admin_site_name', '', '后台站点名称', 1737038688, NULL),
(22, 'website', 'admin_site_logo', '', '后台LOGO', 1737038688, NULL),
(31, 'website', 'pc_site_name', '', 'PC站点名称', 1737038688, NULL),
(32, 'website', 'pc_site_logo', '', 'PC logo', 1737038688, NULL),
(41, 'website', 'h5_site_name', '111', 'H5名称', 1737038688, NULL),
(42, 'website', 'h5_site_logo', '', 'H5 LOGO', 1737038688, NULL),
(61, 'website', 'icp_code', '', 'ICP备案号', 1737038688, NULL),
(62, 'website', 'icp_url', '', 'ICP备案链接', 1737038688, NULL),
(101, 'system', 'access_log_enabled', '1', '访问日志开关（0:关闭 1:开启）', 1737038688, NULL),
(102, 'system', 'admin_log_enabled', '1', '管理员操作日志开关（0:关闭 1:开启）', 1737038688, NULL),
(103, 'system', 'error_log_enabled', '1', '错误日志开关（0:关闭 1:开启）', 1737038688, NULL),
(104, 'system', 'queue_enabled', '1', '消息队列开关（0:关闭 1:开启）', 1737038688, NULL),
(201, 'website', 'h5_system_bottom_nav', '{\"items\":[{\"name\":\"首页\",\"link\":\"/home/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf98a62c.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9d09cb.png\",\"title\":\"首页\"},{\"name\":\"商城\",\"link\":\"/home/platform/mall/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117110067f8dca42f641.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117110067f8dca420b3c.png\",\"title\":\"商城\"},{\"name\":\"外卖\",\"link\":\"/home/platform/food/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117105067f8dc9ab2582.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117105067f8dc9aaea96.png\",\"title\":\"外卖\"},{\"name\":\"家政\",\"link\":\"/home/platform/house/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117104067f8dc9019da5.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117104067f8dc901d9b0.png\",\"title\":\"家政\"},{\"name\":\"我的\",\"link\":\"/home/pages/user/index/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9c2b6e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9a9705.png\",\"title\":\"我的\"}],\"theme\":{\"displayMode\":\"all\",\"backgroundColor\":\"\",\"color\":\"\",\"fontSize\":12}}', 'H5系统底部导航配置', 1, ''),
(202, 'website', 'h5_mall_bottom_nav', '{\"items\":[{\"name\":\"商城\",\"link\":\"/home/platform/mall/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117110067f8dca42f641.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117110067f8dca420b3c.png\",\"title\":\"商城\"},{\"name\":\"分类\",\"link\":\"/home/platform/mall/pages/goods/category/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9cd082.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9692ab.png\",\"title\":\"分类页\"},{\"name\":\"搜索\",\"link\":\"/home/platform/mall/pages/search/goodslist/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9d5b5e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9e00cf.png\",\"title\":\"搜索\"},{\"name\":\"购物车\",\"link\":\"/home/platform/mall/pages/cart/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf933dd3.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf93147b.png\",\"title\":\"购物车\"},{\"name\":\"个人中心\",\"link\":\"/home/pages/user/index/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9c2b6e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9a9705.png\",\"title\":\"我的\"}],\"theme\":{\"displayMode\":\"all\",\"backgroundColor\":\"\",\"color\":\"\",\"fontSize\":12}}', 'Mall底部导航配置', 1, ''),
(203, 'website', 'h5_food_bottom_nav', '{\"items\":[{\"name\":\"外卖\",\"link\":\"/home/platform/food/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117105067f8dc9ab2582.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117105067f8dc9aaea96.png\",\"title\":\"外卖\"},{\"name\":\"分类\",\"link\":\"/home/platform/food/pages/search/storecategory/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9cd082.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9692ab.png\",\"title\":\"分类页\"},{\"name\":\"搜索\",\"link\":\"/home/platform/food/pages/search/storelist/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9d5b5e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9e00cf.png\",\"title\":\"搜索\"},{\"name\":\"购物车\",\"link\":\"/home/platform/food/pages/cart/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf933dd3.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf93147b.png\",\"title\":\"购物车\"},{\"name\":\"个人中心\",\"link\":\"/home/pages/user/index/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9c2b6e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9a9705.png\",\"title\":\"我的\"}],\"theme\":{\"displayMode\":\"all\",\"backgroundColor\":\"\",\"color\":\"\",\"fontSize\":12}}', '外卖底部导航配置', 1, ''),
(204, 'website', 'h5_kms_bottom_nav', '{\"items\":[{\"name\":\"教育\",\"link\":\"/home/platform/kms/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117103167f8dc879f5ba.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117103167f8dc87a5ac8.png\",\"title\":\"教育\"},{\"name\":\"分类\",\"link\":\"/home/platform/kms/pages/goods/category/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9cd082.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9692ab.png\",\"title\":\"分类页\"},{\"name\":\"搜索\",\"link\":\"/home/platform/kms/pages/search/goodslist/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9d5b5e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9e00cf.png\",\"title\":\"搜索\"},{\"name\":\"购物车\",\"link\":\"/home/platform/kms/pages/cart/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf933dd3.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf93147b.png\",\"title\":\"购物车\"},{\"name\":\"个人中心\",\"link\":\"/home/pages/user/index/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9c2b6e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9a9705.png\",\"title\":\"我的\"}],\"theme\":{\"displayMode\":\"all\",\"backgroundColor\":\"\",\"color\":\"\",\"fontSize\":12}}', '教育底部导航配置', 1, ''),
(205, 'website', 'h5_house_bottom_nav', '{\"items\":[{\"name\":\"家政\",\"link\":\"/home/platform/house/pages/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/11/2025041117104067f8dc9019da5.png\",\"activeIcon\":\"attachment/admin/1/image/202504/11/2025041117104067f8dc901d9b0.png\",\"title\":\"家政\"},{\"name\":\"分类\",\"link\":\"/home/platform/house/pages/search/storecategory/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9cd082.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9692ab.png\",\"title\":\"分类页\"},{\"name\":\"搜索\",\"link\":\"/home/platform/house/pages/search/storelist/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9d5b5e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9e00cf.png\",\"title\":\"搜索\"},{\"name\":\"购物车\",\"link\":\"/home/platform/house/pages/cart/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf933dd3.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf93147b.png\",\"title\":\"购物车\"},{\"name\":\"个人中心\",\"link\":\"/home/pages/user/index/index\",\"inactiveIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9c2b6e.png\",\"activeIcon\":\"attachment/admin/1/image/202504/02/2025040219140167ed1bf9a9705.png\",\"title\":\"我的\"}],\"theme\":{\"displayMode\":\"all\",\"backgroundColor\":\"\",\"color\":\"\",\"fontSize\":12}}', '家政服务底部导航配置', 1, ''),
(301, 'growth', 'growth_login_amount', '100', '登录获取', 1, ''),
(302, 'growth', 'growth_login_enabled', '1', '是否开启', 1, ''),
(303, 'growth', 'growth_register_amount', '10', '注册获取成长值', 1, ''),
(304, 'growth', 'growth_register_enabled', '1', '是否开启', 1, ''),
(305, 'growth', 'growth_pay_amount', '10', '支付获取成长值', 1, ''),
(306, 'growth', 'growth_pay_enabled', '1', '是否开启', 1, ''),
(307, 'growth', 'growth_payrate_amount', '10', '金额比例获取，1元获取成长值', 0, ''),
(308, 'growth', 'growth_payrate_enabled', '1', '是否开启按比例获取', 1, ''),
(309, 'growth', 'growth_review_amount', '10', '评价获取', 1, ''),
(310, 'growth', 'growth_review_enabled', '1', '是否开启', 1, ''),
(311, 'growth', 'growth_invite_amount', '10', '邀请注册', 0, ''),
(312, 'growth', 'growth_invite_enabled', '1', '是否开启', 0, ''),
(401, 'points', 'points_login_amount', '100', '登录获取', 1, ''),
(402, 'points', 'points_login_enabled', '1', '是否开启', 1, ''),
(403, 'points', 'points_register_amount', '10', '注册获取积分', 1, ''),
(404, 'points', 'points_register_enabled', '1', '是否开启', 1, ''),
(405, 'points', 'points_pay_amount', '10', '支付获取积分', 1, ''),
(406, 'points', 'points_pay_enabled', '1', '是否开启', 1, ''),
(407, 'points', 'points_payrate_amount', '10', '金额比例获取，1元获取积分', 0, ''),
(408, 'points', 'points_payrate_enabled', '1', '是否开启', 1, ''),
(409, 'points', 'points_review_amount', '10', '评价获取', 1, ''),
(410, 'points', 'points_review_enabled', '1', '是否开启', 1, ''),
(411, 'points', 'points_invite_amount', '10', '邀请注册', 0, ''),
(412, 'points', 'points_invite_enabled', '1', '是否开启', 0, ''),
(501, 'goods', 'goods_need_audit', '0', '商品是否需要审核', 1, ''),
(502, 'goods', 'goods_category_select_limit', '10', '商品分类选择限制', 1, ''),
(601, 'email', 'email_is_enabled', '0', '是否开启邮件服务', 1, ''),
(602, 'email', 'smtp_host', 'smtp.126.com', 'SMTP 服务器', 1, ''),
(603, 'email', 'smtp_port', '465', 'SMTP端口', 1, ''),
(604, 'email', 'smtp_from_email', '', '发件人邮箱', 1, ''),
(605, 'email', 'smtp_user', '', 'SMTP用户名', 1, ''),
(606, 'email', 'smtp_pass', '', 'SMTP密码', 1, ''),
(607, 'email', 'smtp_secure', 'ssl', '安全协议(ssl/tls)', 1, ''),
(701, 'sms', 'sms_is_enabled', '0', '是否开启短信服务', 1, ''),
(702, 'sms', 'sms_default_provider', 'aliyun', '默认服务商', 1, ''),
(703, 'sms', 'sms_config_aliyun', '', '阿里云短信配置信息', 1, ''),
(704, 'sms', 'sms_config_tencent', '', '腾讯云短信配置信息', 1, ''),
(801, 'storage', 'storage_default_provider', 'local', '默认存储', 1, ''),
(802, 'storage', 'storage_config_local', '[]', '本地存储配置', 1, ''),
(803, 'storage', 'storage_config_aliyun', NULL, '阿里云存储配置', 1, ''),
(804, 'storage', 'storage_config_tencent', NULL, '腾讯云存储配置', 1, ''),
(901, 'lbs', 'lbs_is_enabled', '0', '是否使用地图', 1, ''),
(902, 'lbs', 'lbs_default_provider', NULL, '默认地图服务商', 1, ''),
(903, 'lbs', 'lbs_config_tencent', NULL, '腾讯地图配置', 1, ''),
(904, 'lbs', 'lbs_config_baidu', NULL, '百度地图配置', 1, ''),
(905, 'lbs', 'lbs_config_gaode', NULL, '高德地图配置', 1, ''),
(906, 'lbs', 'lbs_config_tianditu', NULL, '天地图配置', 1, ''),
(1001, 'express', 'express_is_enabled', '1', '是否开启快递查询', 1, ''),
(1002, 'express', 'express_default_provider', 'kuaidiniao', '默认快递查询服务商', 1, ''),
(1003, 'express', 'express_config_kuaidiniao', NULL, '快递鸟配置', 1, ''),
(1004, 'express', 'express_config_kuaidi100', NULL, '快递100配置', 1, ''),
(1101, 'printer', 'printer_is_enabled', '1', '是否开启打印机服务', 1, ''),
(1102, 'printer', 'printer_default_provider', 'feie', '默认打印机服务商', 1, ''),
(1103, 'printer', 'printer_config_feie', NULL, '飞鹅打印机配置', 1, ''),
(1104, 'printer', 'printer_config_yilian', NULL, '易联云打印机配置', 1, ''),
(1105, 'printer', 'printer_config_xinye', NULL, '芯烨打印机配置', 1, ''),
(1106, 'printer', 'printer_config_jiabo', NULL, '佳博打印机配置', 1, ''),
(1301, 'user_login', 'user_login_normal', '1', '是否开启普通登录或注册', 1, ''),
(1302, 'user_login', 'user_login_mobile', '0', '是否开启手机验证码登录或注册', 1, ''),
(1303, 'user_login', 'user_login_wechat', '0', '是否开启微信登录或注册', 1, ''),
(1304, 'user_login', 'user_login_logo', NULL, '用户登录显示LOGO', 1, ''),
(1401, 'order_auto', 'auto_cancel_order_enabled', '1', '是否开启自动取消订单功能（1：开启，0：关闭）', 1, ''),
(1402, 'order_auto', 'auto_cancel_order_hours', '2', '自动取消订单的小时数（下单后多少小时未支付自动取消）', 1, ''),
(1403, 'order_auto', 'auto_confirm_order_enabled', '1', '是否开启自动确认收货功能（1：开启，0：关闭）', 1, ''),
(1404, 'order_auto', 'auto_confirm_order_hours', '168', '自动确认收货的小时数（发货后多少小时未确认收货自动确认）', 1, ''),
(1405, 'order_auto', 'refund_order_enabled', '1', '是否开启确认收货退款', 1, ''),
(1406, 'order_auto', 'refund_order_days', '2', '确认收货可申请退款的天数', 1, ''),
(2001, 'distributor', 'distributor_is_enabled', '1', '是否开启分销功能', 1, ''),
(2002, 'distributor', 'distributor_tier', '1', '分销员层级', 1, ''),
(2003, 'distributor', 'distributor_self_enabled', '1', '是否开启分销员自购分佣', 1, ''),
(2004, 'distributor', 'distributor_apply_type', 'audit', '分销商申请方式, audit 审核, auto 自动, manual 手动', 1, ''),
(2005, 'distributor', 'distributor_conditions', 'none', '分销商申请条件 none 无条件 amount 消费金额 count 消费次数', 1, ''),
(2006, 'distributor', 'distributor_apply_amount', '0', '分销商申请条件 amount 时，消费金额', 1, ''),
(2007, 'distributor', 'distributor_apply_count', '0', '分销商申请条件 count 时，消费次数', 1, ''),
(3030, 'user_withdrawal', 'withdrawal_min_amount', '10', '最低提现金额', 1, ''),
(3031, 'user_withdrawal', 'withdrawal_fee_rate', '6', '提现手续费', 1, '');



DROP TABLE IF EXISTS `#__sys_error_logs`;
CREATE TABLE `#__sys_error_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) DEFAULT NULL COMMENT '错误文件',
  `line` int(11) DEFAULT NULL COMMENT '错误行',
  `message` text COMMENT '错误信息',
  `code` varchar(10) DEFAULT NULL,
  `exception_class` varchar(32) DEFAULT NULL COMMENT '异常类名',
  `ip` varchar(32) DEFAULT NULL COMMENT '访问IP',
  `method` varchar(64) DEFAULT NULL COMMENT '访问类型',
  `root` varchar(64) DEFAULT NULL COMMENT '根目录',
  `controller` varchar(64) DEFAULT NULL COMMENT '控制器',
  `action` varchar(64) DEFAULT NULL COMMENT '方法',
  `url` varchar(1024) DEFAULT NULL COMMENT 'url',
  `params` text COMMENT '请求参数',
  `duration` int(11) DEFAULT NULL COMMENT '请求耗时(毫秒)',
  `previous` text DEFAULT NULL COMMENT '前置异常信息（JSON格式）',
  `create_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exception_class` (`exception_class`),
  KEY `idx_file` (`file`),
  KEY `idx_controller` (`controller`),
  KEY `idx_action` (`action`),
  KEY `idx_ip` (`ip`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_code` (`code`),
  KEY `idx_method` (`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='错误日志';


DROP TABLE IF EXISTS `#__sys_express`;
CREATE TABLE `#__sys_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) DEFAULT NULL COMMENT '快递公司编号',
  `kdniao_code` varchar(20) DEFAULT NULL COMMENT '快递鸟编号',
  `kd100_code` varchar(20) DEFAULT NULL COMMENT '快递100编号',
  `name` varchar(20) DEFAULT NULL COMMENT '快递名称',
  `logo` varchar(255) DEFAULT NULL,
  `url` varchar(32) DEFAULT NULL COMMENT '链接',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) DEFAULT NULL COMMENT '是否显示',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_code` (`code`),
  KEY `idx_name` (`name`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='快递公司表';

INSERT INTO `#__sys_express` (`id`, `code`, `kdniao_code`, `kd100_code`, `name`, `logo`, `url`, `sort`, `is_show`, `create_at`, `update_at`) VALUES
(1, 'ZTO', 'ZTO', 'zhongtong', '中通快递', '', 'https://www.zto.com/', 0, 1, 1751614607, 1751615192),
(2, 'STO', 'STO', 'shentong', '申通快递', '', 'https://www.sto.cn/pc', 0, 1, 1751614774, 1751615201),
(3, 'SF', 'SF', 'shunfeng', '顺丰速运', '', 'https://www.sf-express.com', 0, 1, 1751614897, 1751615212),
(4, 'YTO', 'YTO', 'yuantong', '圆通速递', '', 'https://www.yto.net.cn/', 0, 1, 1751614930, 1751615219),
(5, 'EMS', 'EMS', 'ems', 'EMS', '', 'https://www.ems.com.cn/', 0, 1, 1751614976, 1751615226),
(6, 'DBL', 'DBL', 'debangkuaidi', '德邦快递', '', 'https://www.deppon.com/', 0, 1, 1751615022, 1751615233),
(7, 'YD', 'YD', 'yunda', '韵达速递', '', 'https://www.yundaex.com', 0, 1, 1751615069, 1751615298),
(8, 'JTSD', 'JTSD', 'jtexpress', '极兔速递', '', 'https://www.jtexpress.cn/', 0, 1, 1751615172, 1751615307);


DROP TABLE IF EXISTS `#__sys_notice_log`;
CREATE TABLE `#__sys_notice_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(32) NOT NULL COMMENT 'notice_tpl表中 key',
  `notice_channel` varchar(20) NOT NULL COMMENT '通知类型 internal email sms wechat_official wechat_mini',
  `receiver` varchar(32) DEFAULT NULL COMMENT '接收手机号 openid email 根据notice_channel区分',
  `title` varchar(64) DEFAULT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已读 0未读 1已读',
  `send_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发送状态 0发送中 1已发送 2发送失败',
  `send_params` text COMMENT '发送请求参数  可拓展重发',
  `send_result` text COMMENT '发送结果 发送返回的结果',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_key` (`key`),
  KEY `idx_notice_channel` (`notice_channel`),
  KEY `idx_receiver` (`receiver`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_send_status` (`send_status`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消息通知记录';

DROP TABLE IF EXISTS `#__sys_notice_sms_log`;
CREATE TABLE `#__sys_notice_sms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `key` varchar(32) NOT NULL COMMENT 'notice_tpl表中 key',
  `sms_provider` varchar(10) NOT NULL COMMENT '短信服务商 阿里云 腾讯云',
  `sms_template_id` varchar(32) NOT NULL COMMENT 'notice_tpl表中 一致',
  `mobile` varchar(20) NOT NULL COMMENT '接收手机',
  `content` varchar(255) NOT NULL COMMENT '短信内容',
  `code` varchar(20) DEFAULT NULL COMMENT '验证码，注册，登录，密码重置',
  `is_verify` tinyint(4) DEFAULT '0' COMMENT '是否验证 0  1已验证',
  `send_status` tinyint(4) DEFAULT '0' COMMENT '发送状态 0发送中 1已发送 2发送失败',
  `send_params` text COMMENT '发送请求参数',
  `send_result` text COMMENT '发送结果 发送返回的结果',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_key` (`key`),
  KEY `idx_mobile` (`mobile`),
  KEY `idx_code` (`code`),
  KEY `idx_is_verify` (`is_verify`),
  KEY `idx_send_status` (`send_status`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_sms_provider` (`sms_provider`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短信消息记录';


DROP TABLE IF EXISTS `#__sys_notice_tpl`;
CREATE TABLE `#__sys_notice_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(10) DEFAULT NULL COMMENT '预留针对不同类型',
  `key` varchar(32) NOT NULL COMMENT '标识',
  `title` varchar(32) NOT NULL COMMENT '显示标题',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `template_type` tinyint(4) NOT NULL COMMENT '模板类型 1业务通知 2验证码(注册，登录)',
  `receiver_type` tinyint(4) DEFAULT NULL COMMENT '接收类型 1用户 2店铺',
  `interna_template` varchar(255) DEFAULT NULL COMMENT '站内通知模板',
  `email_switch` tinyint(4) DEFAULT NULL COMMENT '邮箱通知是否开启',
  `email_template` varchar(255) DEFAULT NULL COMMENT '邮箱通知模板',
  `sms_switch` tinyint(4) DEFAULT NULL COMMENT '短信通知是否开启',
  `sms_template_id` varchar(32) DEFAULT NULL COMMENT '短信模板ID',
  `sms_template` varchar(255) DEFAULT NULL COMMENT '邮箱通知模板',
  `wechat_official_switch` tinyint(4) DEFAULT NULL COMMENT '微信公众号通知是否开启',
  `wechat_official_template_id` varchar(64) DEFAULT NULL COMMENT '微信公众号模板ID',
  `wechat_mini_switch` tinyint(4) DEFAULT NULL COMMENT '微信小程序通知是否开启',
  `wechat_mini_template_id` varchar(64) DEFAULT NULL COMMENT '微信小程序模板ID',
  `supported_channels` varchar(64) DEFAULT NULL COMMENT '发送渠道  interna|email|sms|wechat',
  `update_at` int(11) DEFAULT NULL,
  `create_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_key` (`key`),
  KEY `idx_platform` (`platform`),
  KEY `idx_template_type` (`template_type`),
  KEY `idx_receiver_type` (`receiver_type`),
  KEY `idx_email_switch` (`email_switch`),
  KEY `idx_sms_switch` (`sms_switch`),
  KEY `idx_wechat_official_switch` (`wechat_official_switch`),
  KEY `idx_wechat_mini_switch` (`wechat_mini_switch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='消息通知模板';


INSERT INTO `#__sys_notice_tpl` (`id`, `platform`, `key`, `title`, `description`, `template_type`, `receiver_type`, `interna_template`, `email_switch`, `email_template`, `sms_switch`, `sms_template_id`, `sms_template`, `wechat_official_switch`, `wechat_official_template_id`, `wechat_mini_switch`, `wechat_mini_template_id`, `supported_channels`, `update_at`, `create_at`) VALUES
(1, NULL, 'user_register', '注册短信', '可选变量 验证码:{code}', 2, 1, NULL, NULL, NULL, 1, '11', '您的手机验证码{code}，请不要轻易告诉其他人', NULL, NULL, 0, '', 'sms', NULL, NULL),
(2, NULL, 'user_login', '登录短信', '可选变量 验证码:{code}', 2, 1, NULL, NULL, NULL, 1, '11', '您的手机验证码{code}，请不要轻易告诉其他人', NULL, NULL, 0, '', 'sms', NULL, NULL),
(3, NULL, 'user_reset_password', '密码重置', '可选变量 验证码:{code}', 2, 1, NULL, NULL, NULL, 1, '11', '您的手机验证码{code}，请不要轻易告诉其他人', NULL, NULL, 0, '', 'sms', NULL, NULL),
(4, NULL, 'user_bind_mobile', '绑定手机', '可选变量 验证码:{code}', 2, 1, NULL, NULL, NULL, 1, '11', '您的手机验证码{code}，请不要轻易告诉其他人', NULL, NULL, 0, '', 'sms', NULL, NULL),
(5, NULL, 'user_unbind_mobile', '解绑手机', '可选变量 验证码:{code}', 2, 1, NULL, NULL, NULL, 1, '11', '您的手机验证码{code}，请不要轻易告诉其他人', NULL, NULL, 0, '', 'sms', NULL, NULL),
(21, NULL, 'user_order_delivery', '订单交付(发货)', '可选变量 店铺名:{store_name},订单号:{order_sn},交付时间:{$delivery_time}', 1, 1, '您在店铺“{store_name}”购买的商品已发货。', 1, '您在店铺“{store_name}”购买的商品已发货。', 1, '11', '您在店铺“{store_name}”购买的商品已发货。', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(31, NULL, 'user_refund_approved', '退款同意', '可选变量 店铺名:{store_name}', 1, 1, '您申请的退款已同意', 1, '您申请的退款已同意', 1, '11', '您申请的退款已同意', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(32, NULL, 'user_refund_rejected', '退款拒绝', '可选变量 店铺名:{store_name}', 1, 1, '您申请的退款，被店铺拒绝', 1, '您申请的退款，被店铺拒绝', 1, '11', '您申请的退款，被店铺拒绝', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(122, NULL, 'store_order_payment_success', '订单支付', '可选变量 订单号:{order_sn},订单ID:{order_id},支付金额:{pay_amount}', 1, 2, '您有订单需要处理，订单编号：{order_sn}。', 1, '您有订单需要处理，订单编号：{order_sn}。', 1, '11', '您有订单需要处理，订单编号：{order_sn}。', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(125, NULL, 'store_order_cancel', '订单取消', '可选变量 订单号:{order_sn},订单ID:{order_id},支付金额:{pay_amount}', 1, 2, '您店铺有订单被取消，订单编号：{order_sn}。', 1, '您店铺有订单被取消，订单编号：{order_sn}。', 1, '11', '您店铺有订单被取消，订单编号：{order_sn}。', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(128, NULL, 'store_order_confirm', '订单确认', '可选变量 订单号:{order_sn},订单ID:{order_id},支付金额:{pay_amount}', 1, 2, '您店铺有订单确认收货，订单编号：{order_sn}。', 1, '您店铺有订单确认收货，订单编号：{order_sn}。', 1, '11', '您店铺有订单确认收货，订单编号：{order_sn}。', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(131, NULL, 'store_refund_request', '退款申请', '可选变量 订单号:{order_sn},订单ID:{order_id},申请金额:{apply_amount}', 1, 2, '店铺有退款申请，请立即处理，订单号：{order_sn}', 1, '店铺有退款申请，请立即处理，订单号：{order_sn}', 1, '11', '店铺有退款申请，请立即处理，订单号：{order_sn}', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(141, NULL, 'store_goods_review_failed', '商品审核失败', '可选变量 商品名称:{goods_name}', 1, 2, '您店铺的商品：{goods_name}，审核失败', 1, '您店铺的商品：{goods_name}，审核失败', 1, '11', '您店铺的商品：{goods_name}，审核失败', 0, '11', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL),
(142, NULL, 'store_goods_offline', '商品下架', '可选变量 商品名称:{goods_name}', 1, 2, '您店铺的商品：{goods_name}，被系统下架', 1, '您店铺的商品：{goods_name}，被系统下架', 1, '1', '您店铺的商品：{goods_name}，被系统下架', 0, '1', 0, '', 'interna|email|sms|wechat_official|wechat_mini', NULL, NULL);



DROP TABLE IF EXISTS `#__sys_platform`;
CREATE TABLE `#__sys_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '应用名称',
  `platform` varchar(10) DEFAULT NULL COMMENT '唯一标识 mall kms house food',
  `scene` varchar(10) DEFAULT NULL COMMENT '应用场景sys系统级 店铺级store',
  `icon` varchar(255) DEFAULT NULL COMMENT '应用图标',
  `version` varchar(10) NOT NULL COMMENT '版本',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `is_enable` tinyint(4) DEFAULT '0' COMMENT '是否启用',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `create_by` varchar(32) NOT NULL COMMENT '创建人',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `update_by` varchar(32) DEFAULT NULL COMMENT '更新人',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_platform` (`platform`),
  KEY `idx_scene` (`scene`),
  KEY `idx_is_enable` (`is_enable`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='安装应用系统';


INSERT INTO `#__sys_platform` (`id`, `name`, `platform`, `scene`, `icon`, `version`, `description`, `sort`, `is_enable`, `create_at`, `create_by`, `update_at`, `update_by`) VALUES
(1, '系统', 'system', 'system', NULL, '', NULL, 0, 1, 1, '1', NULL, NULL),
(2, '多店铺', 'mall', 'store', NULL, '1.0.0', NULL, 0, 1, 1, '1', NULL, NULL),
(3, '视频教育系统', 'kms', 'store', NULL, '1.0.0', NULL, 0, 1, 1, '1', NULL, NULL),
(4, '外卖系统', 'food', 'store', NULL, '1.0.0', NULL, 0, 1, 1, '1', NULL, NULL),
(5, '家政服务', 'house', 'store', NULL, '1.0.0', NULL, 0, 1, 1, '1', NULL, NULL);



DROP TABLE IF EXISTS `#__sys_task_queue`;
CREATE TABLE `#__sys_task_queue` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `biz_key` varchar(100) NOT NULL COMMENT '业务幂等键（唯一）',
  `queue_type` varchar(50) NOT NULL COMMENT '任务类型',
  `queue_group` varchar(50) NOT NULL DEFAULT 'default' COMMENT '队列分组',
  `payload` text NOT NULL COMMENT '任务载荷（JSON格式，包含完整的任务信息）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态：0=待处理,1=完成,2=失败,3=处理中',
  `priority` tinyint(4) NOT NULL DEFAULT '0' COMMENT '优先级：越高越先处理',
  `retry_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '已重试次数',
  `max_retries` tinyint(4) NOT NULL DEFAULT '3' COMMENT '最大重试次数',
  `error_message` text COMMENT '错误信息',
  `consume_ms` int(11) NOT NULL DEFAULT '0' COMMENT '消费耗时(毫秒)',
  `scheduled_at` int(11) NOT NULL DEFAULT '0' COMMENT '计划执行时间',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_biz_key` (`biz_key`),
  KEY `idx_queue_type` (`queue_type`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_queue_group` (`queue_group`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_update_at` (`update_at`),
  KEY `idx_scheduled_at` (`scheduled_at`),
  KEY `idx_pop_batch` (`status`, `scheduled_at`, `retry_count`, `priority`, `id`) COMMENT '优化popBatch查询：覆盖查询条件和排序字段'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统消息队列';


DROP TABLE IF EXISTS `#__sys_task_cron`;
CREATE TABLE `#__sys_task_cron` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `cron_type` varchar(50) NOT NULL COMMENT '任务类型',
  `params` text NOT NULL COMMENT '任务参数（JSON格式）',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态：1=启用,0=禁用',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统计划任务';


DROP TABLE IF EXISTS `#__sys_task_cron_log`;
CREATE TABLE `#__sys_task_cron_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '任务ID',
  `cron_type` varchar(50) NOT NULL COMMENT '任务类型',
  `result` text NOT NULL COMMENT '任务结果（JSON格式）',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1=成功,0=失败',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统计划任务日志';


DROP TABLE IF EXISTS `#__tbl_cart`;
CREATE TABLE `#__tbl_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车自增ID',
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall kms house food',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `sku_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Sku ID',
  `quantity` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `promotion_platform` varchar(10) DEFAULT NULL COMMENT 'default 为多平台通用活动 mall kms house food 为单独平台活动',
  `promotion_type` varchar(20) DEFAULT NULL COMMENT '活动类型',
  `promotion_related_id` int(11) DEFAULT NULL COMMENT '活动ID',
  `promotion_price` decimal(20,4) NOT NULL COMMENT '活动价格,用于结账,未参加活动为sku_price',
  `is_active` tinyint(4) NOT NULL DEFAULT '1' COMMENT '商品状态（1: 可用, 0: 不可用）',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_user_store` (`user_id`, `store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='购物车数据表';


DROP TABLE IF EXISTS `#__tbl_goods`;
CREATE TABLE `#__tbl_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品自增ID',
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall kms o2o',
  `goods_name` varchar(128) NOT NULL COMMENT '商品名称',
  `goods_advword` varchar(150) DEFAULT NULL COMMENT '商品广告词',
  `goods_minprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品最低价格',
  `goods_body` text COMMENT '商品内容',
  `goods_parameters` text,
  `goods_status` tinyint(4) DEFAULT '0' COMMENT '商品状态 0:下架 1:上架',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `brand_id` int(11) DEFAULT '0' COMMENT '品牌ID',
  `store_goods_cid` int(11) DEFAULT '0' COMMENT '店铺商品分类id',
  `cover_image` varchar(255) NOT NULL DEFAULT '' COMMENT '主图',
  `slide_image` varchar(2000) NOT NULL DEFAULT '' COMMENT '轮播图',
  `goods_video` varchar(255) NOT NULL DEFAULT '' COMMENT '商品视频',
  `stock_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品库存',
  `click_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品点击数量',
  `sales_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品销售数量',
  `virtual_sales_num` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟销售数量',
  `collect_num` int(11) NOT NULL DEFAULT '0' COMMENT '商品收藏数量',
  `evaluate_num` int(11) NOT NULL DEFAULT '0' COMMENT '评价数',
  `goods_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `avg_goods_score` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '商品平均评分',
  `is_flashsale_goods` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否限时促销',
  `flashsale_goods_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '限时促销状态 0 未开始 1进行中 2已结束',
  `is_wholesale_goods` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否批发',
  `is_userdiscount_goods` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否平台会员价',
  `is_distributor_goods` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否是分销商品',
  `distributor_goods_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分销商品类型 0默认(分销等级设置比例) 1单独设置比例 2固定金额',
  `sys_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '系统状态 0待审核 1正常 2审核失败 3违规下架',
  `sys_status_reason` varchar(255) DEFAULT NULL COMMENT '系统状态理由 审核失败 及下架',
  `sys_recommend_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '系统推荐状态 0不推荐 1推荐',
  `mall_express_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'mall快递类型 0包邮 1统一运费 2模板',
  `mall_express_tpl_id` int(11) NOT NULL DEFAULT '0' COMMENT 'mall快递运费模板ID(可拓展)',
  `mall_express_fee` decimal(20,4) NOT NULL DEFAULT '0.0000' COMMENT 'mall固定运费',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `create_at` int(11) NOT NULL COMMENT '商品添加时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '商品添加时间',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态 0:未删除 1删除',
  `deleted_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_goods_name` (`goods_name`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_goods_status` (`goods_status`),
  KEY `idx_brand_id` (`brand_id`),
  KEY `idx_sys_status` (`sys_status`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_goods_sort` (`goods_sort`),
  KEY `idx_sales_num` (`sales_num`),
  KEY `idx_sys_recommend_status` (`sys_recommend_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';



DROP TABLE IF EXISTS `#__tbl_goods_attr`;
CREATE TABLE `#__tbl_goods_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `attr_name` varchar(20) NOT NULL COMMENT '属性名称',
  `attr_value` varchar(255) DEFAULT NULL COMMENT '属性值',
  `attr_sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品属性表';


DROP TABLE IF EXISTS `#__tbl_goods_brand`;
CREATE TABLE `#__tbl_goods_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '品牌自增ID',
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall kms house food',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID 0 为平台',
  `name` varchar(50) DEFAULT NULL COMMENT '品牌名称',
  `description` varchar(255) DEFAULT NULL COMMENT '品牌描述',
  `logo` varchar(100) DEFAULT NULL COMMENT '品牌图片',
  `sort` int(11) DEFAULT '0' COMMENT '品牌排序',
  `is_recommend` tinyint(4) DEFAULT '0' COMMENT '品牌推荐，0为否，1为是',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '前台显示 0:否 1:是',
  `apply_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '品牌申请，0为申请中，1为通过，2为拒绝 默认为1',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_pid` (`pid`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='品牌表';




DROP TABLE IF EXISTS `#__tbl_goods_category`;
CREATE TABLE `#__tbl_goods_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺分类自增ID',
  `platform` varchar(10) NOT NULL COMMENT '应用类型 system mall kms house food',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `name` varchar(32) NOT NULL COMMENT '分类名称',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `seo_title` varchar(32) DEFAULT NULL COMMENT '分类名称',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT '分类关键词',
  `seo_description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '分类前台显示 0:否 1:是',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '分类排序',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_pid` (`pid`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_platform_pid` (`platform`, `pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品分类';


DROP TABLE IF EXISTS `#__tbl_goods_category_rel`;
CREATE TABLE `#__tbl_goods_category_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `category_id` int(11) NOT NULL COMMENT '分类ID',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_category_id` (`category_id`),
  UNIQUE KEY `udx_goods_category` (`goods_id`, `category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品分类关联表';




DROP TABLE IF EXISTS `#__tbl_goods_comment`;
CREATE TABLE `#__tbl_goods_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `order_goods_id` int(11) NOT NULL COMMENT '订单商品表ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `sku_id` int(11) NOT NULL COMMENT 'Sku ID',
  `content` text NOT NULL COMMENT '评价内容',
  `images` text COMMENT '评价图片',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `is_anonymous` tinyint(4) DEFAULT NULL COMMENT '是否匿名',
  `is_reply` tinyint(4) DEFAULT NULL COMMENT '是否回复',
  `reply_content` text COMMENT '回复内容',
  `reply_time` int(11) DEFAULT NULL COMMENT '回复时间',
  `goods_score` tinyint(4) DEFAULT NULL COMMENT '商品评分',
  `describe_score` tinyint(4) DEFAULT NULL COMMENT '描述评分(店铺)',
  `logistics_score` tinyint(4) DEFAULT NULL COMMENT '物流评分(店铺)',
  `service_score` tinyint(4) DEFAULT NULL COMMENT '服务评分(店铺)',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态 0:未删除 1删除',
  `deleted_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_goods_score` (`goods_score`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品评价表';


DROP TABLE IF EXISTS `#__tbl_goods_favorites`;
CREATE TABLE `#__tbl_goods_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_create_at` (`create_at`),
  UNIQUE KEY `udx_user_goods` (`user_id`, `goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品收藏表';


DROP TABLE IF EXISTS `#__tbl_goods_flashsale`;
CREATE TABLE `#__tbl_goods_flashsale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `sku_id` int(11) NOT NULL COMMENT '规格ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `flashsale_price` decimal(20,4) NOT NULL COMMENT '限时折扣价',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '0未开始 1进行中 2已结束',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_status` (`status`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_end_time` (`end_time`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品级促销[限时折扣]';

DROP TABLE IF EXISTS `#__tbl_goods_userdiscount`;
CREATE TABLE `#__tbl_goods_userdiscount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `sku_id` int(11) NOT NULL COMMENT '规格ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `user_level_id` int(11) NOT NULL COMMENT '用户等级ID(关联user_growth_level表)',
  `user_level_price` decimal(20,4) NOT NULL COMMENT '优惠价',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_user_level_id` (`user_level_id`),
  UNIQUE KEY `udx_goods_sku_level` (`goods_id`, `sku_id`, `user_level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品级促销[会员等级价格阶梯]';

DROP TABLE IF EXISTS `#__tbl_goods_wholesale`;
CREATE TABLE `#__tbl_goods_wholesale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `sku_id` int(11) NOT NULL COMMENT '规格ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `wholesale_price` decimal(20,4) NOT NULL COMMENT '批发价格',
  `quantity_min` int(11) NOT NULL COMMENT '最小数量',
  `quantity_max` int(11) NOT NULL COMMENT '最大数量',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_sku_id` (`sku_id`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品级促销[价格阶梯]';


DROP TABLE IF EXISTS `#__tbl_goods_sku`;
CREATE TABLE `#__tbl_goods_sku` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品sku',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `sku_name` varchar(20) NOT NULL COMMENT '名称',
  `sku_image` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  `sku_code` varchar(20) NOT NULL DEFAULT '' COMMENT '编码',
  `sku_price` decimal(20,4) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `sku_stock` int(11) NOT NULL DEFAULT '0' COMMENT '商品sku库存',
  `sku_spec_format` text COMMENT 'sku规格格式',
  `market_price` decimal(20,4) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `cost_price` decimal(20,4) NOT NULL DEFAULT '0.00' COMMENT '成本价',
  `is_default` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否默认',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_is_default` (`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品规格表';



DROP TABLE IF EXISTS `#__tbl_goods_spec`;
CREATE TABLE `#__tbl_goods_spec` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '规格id',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `spec_name` varchar(20) NOT NULL COMMENT '规格名称',
  `spec_value` text COMMENT '规格值',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品规格表';



DROP TABLE IF EXISTS `#__tbl_order`;
CREATE TABLE `#__tbl_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单自增id',
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall',
  `order_from` varchar(10) DEFAULT NULL COMMENT '订单来源 h5 pc',
  `pay_merchant_id` int(11) NOT NULL COMMENT '收款商户ID  0为后台收款',
  `pay_channel` varchar(20) DEFAULT NULL COMMENT '支付渠道 alipay wechat',
  `pay_scene` varchar(20) DEFAULT NULL COMMENT '支付场景 H5 PC 等',
  `order_merge_id` int(11) DEFAULT '0' COMMENT '合并支付批次ID（外键关联MergeOrder表）',
  `order_sn` varchar(32) NOT NULL COMMENT '订单编号(内部订单标识)',
  `order_status` tinyint(4) NOT NULL DEFAULT '10' COMMENT '订单状态：0:已取消 10:未付款 20:待发货 30:已发货 40:快递已取件 50:已收货',
  `order_referrer_id` int(11) DEFAULT '0' COMMENT '下单推荐人(非用户注册邀请人)',
  `out_trade_no` varchar(32) DEFAULT NULL COMMENT '支付单号 支付参数的out_trade_no',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '第三方平台交易号',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `store_id` int(11) NOT NULL COMMENT '卖家店铺ID',
  `user_id` int(11) NOT NULL COMMENT '买家ID',
  `delivery_method` varchar(20) DEFAULT '' COMMENT '交付方式 快递交付,到店自提,骑手配送,上门服务,现场服务',
  `original_amount` decimal(20,4) DEFAULT NULL COMMENT '商品总原价',
  `goods_amount` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '商品总优惠价',
  `shipping_amount` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '运费',
  `discount_amount` decimal(20,4) NOT NULL COMMENT '优惠金额',
  `order_amount` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '订单总价格',
  `pay_amount` decimal(20,4) NOT NULL COMMENT '支付价格',
  `service_amount` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '平台服务费,确认收货后扣除',
  `invoice_info` varchar(255) DEFAULT NULL COMMENT '订单发票信息',
  `is_evaluate` tinyint(4) DEFAULT '0' COMMENT '评价状态 0：未评价 1：已评价',
  `refunding_count` tinyint(4) DEFAULT '0' COMMENT '正在退款中的数量',
  `refund_status` tinyint(4) DEFAULT '0' COMMENT '退款状态 0:无退款 1:部分退款 2:全部退款(已完成)',
  `refund_amount` decimal(20,4) DEFAULT '0.000000' COMMENT '退款金额',
  `allow_refund_time` int(11) NOT NULL DEFAULT '0' COMMENT '允许退款时间',
  `user_remark` varchar(255) DEFAULT NULL COMMENT '买家备注',
  `store_remark` varchar(255) DEFAULT NULL COMMENT '店铺备注',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单生成时间',
  `payment_time` int(11) DEFAULT '0' COMMENT '支付(付款)时间',
  `delivery_time` int(11) NOT NULL DEFAULT '0' COMMENT '交付时间',
  `shipping_time` int(11) NOT NULL DEFAULT '0' COMMENT '配送时间',
  `finnshed_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单完成时间',
  `evaluate_time` int(11) NOT NULL DEFAULT '0' COMMENT '评价时间',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `close_time` int(11) NOT NULL DEFAULT '0' COMMENT '订单关闭时间',
  `cancel_time` int(11) NOT NULL DEFAULT '0' COMMENT '取消时间',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态 0:未删除 1删除',
  `deleted_time` int(11) DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_order_sn` (`order_sn`),
  KEY `idx_platform` (`platform`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_out_trade_no` (`out_trade_no`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_pay_channel` (`pay_channel`),
  KEY `idx_trade_no` (`trade_no`),
  KEY `idx_add_time` (`add_time`),
  KEY `idx_payment_time` (`payment_time`),
  KEY `idx_delivery_method` (`delivery_method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单表';


DROP TABLE IF EXISTS `#__tbl_order_address`;
CREATE TABLE `#__tbl_order_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `reciver_name` varchar(50) DEFAULT NULL COMMENT '收货人姓名',
  `reciver_mobile` varchar(20) DEFAULT NULL COMMENT '收货人手机',
  `reciver_address` varchar(255) DEFAULT NULL COMMENT '收货人地址',
  `reciver_latitude` decimal(10,6) DEFAULT NULL COMMENT '收货人纬度',
  `reciver_longitude` decimal(11,6) DEFAULT NULL COMMENT '收货人经度',
  `shipper_name` varchar(50) DEFAULT NULL COMMENT '发货人姓名',
  `shipper_mobile` varchar(20) DEFAULT NULL COMMENT '发货人手机',
  `shipper_address` varchar(255) DEFAULT NULL COMMENT '发货人地址',
  `shipper_latitude` decimal(10,6) DEFAULT NULL COMMENT '发货人纬度',
  `shipper_longitude` decimal(11,6) DEFAULT NULL COMMENT '发货人经度',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单收货地址表';


DROP TABLE IF EXISTS `#__tbl_order_delivery`;
CREATE TABLE `#__tbl_order_delivery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `delivery_method` varchar(20) DEFAULT '' COMMENT '交付方式 快递交付,到店自提,骑手配送,上门服务,现场服务',
  `express_type` tinyint(4) DEFAULT NULL COMMENT '快递类型 1快递 2无需物流',
  `express_number` varchar(20) DEFAULT NULL COMMENT '物流订单号',
  `express_company` varchar(20) DEFAULT NULL COMMENT '物流公司',
  `rider_id` int(11) DEFAULT '0' COMMENT '骑手ID',
  `rider_total_fee` decimal(20,4) DEFAULT '0.0000' COMMENT '配送总费用',
  `rider_fee_rate` decimal(4,2) DEFAULT '0.00' COMMENT '平台抽佣比例',
  `rider_fee` decimal(20,4) DEFAULT '0.0000' COMMENT '骑手实际配送费',
  `rider_distance` varchar(20) DEFAULT NULL COMMENT '骑手配送距离',
  `rider_fee_desc` varchar(255) DEFAULT NULL COMMENT '骑手费用说明 如 距离，重量，天气，时间计算规则',
  `rider_complete_time` int(11) DEFAULT NULL COMMENT '骑手配送完成时间',
  `technician_id` int(11) DEFAULT '0' COMMENT '师傅ID',
  `technician_appt_time` int(11) DEFAULT '0' COMMENT '师傅预约时间',
  `technician_duration` int(11) DEFAULT '0' COMMENT '师傅服务时长',
  `technician_fee_rate` decimal(4,2) DEFAULT '0.00' COMMENT '店铺抽佣比例',
  `technician_fee` decimal(20,4) DEFAULT '0.0000' COMMENT '师傅实际服务费(pay_amount - shipping_amount) * (1-technician_fee_rate) + shipping_amount',
  `delivery_status` tinyint(4) DEFAULT '10' COMMENT '状态 交付状态',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单交付表';


DROP TABLE IF EXISTS `#__tbl_order_finance`;
CREATE TABLE `#__tbl_order_finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `pay_amount` decimal(20,4) NOT NULL COMMENT '订单实付金额',
  `distributor_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '分销金额',
  `store_sys_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '店铺平台抽成金额',
  `store_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '店铺实际收入',
  `rider_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '骑手实际收入',
  `rider_sys_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '骑手平台抽成金额',
  `technician_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '师傅服务费',
  `technician_service_fee` decimal(20,4) DEFAULT '0.0000' COMMENT '师傅服务费平台抽成',
  `technician_trip_fee` decimal(20,4) DEFAULT '0.0000' COMMENT '师傅服务费路程费',
  `refund_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '退款金额',
  `finance_status` tinyint(4) DEFAULT '0' COMMENT '结算状态0未结算 1已结算',
  `settle_time` int(11) DEFAULT NULL COMMENT '结算时间',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单资金分配表/订单分账';


DROP TABLE IF EXISTS `#__tbl_order_goods`;
CREATE TABLE `#__tbl_order_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单商品表自增ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '买家ID',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_name` varchar(128) NOT NULL COMMENT '商品名称',
  `sku_id` bigint(20) NOT NULL COMMENT '规格ID',
  `sku_name` varchar(32) NOT NULL COMMENT '规格名称',
  `promotion_platform` varchar(10) DEFAULT NULL COMMENT 'default 为多平台通用活动 mall kms house food 为单独平台活动',
  `promotion_type` varchar(20) DEFAULT NULL COMMENT '活动类型',
  `promotion_related_id` int(11) DEFAULT NULL COMMENT '活动ID',
  `promotion_price` decimal(20,4) NOT NULL COMMENT '活动价格,与cart价格一致',
  `discount_price` decimal(20,4) DEFAULT '0.000000',
  `sku_price` decimal(20,4) NOT NULL COMMENT '商品价格',
  `pay_price` decimal(20,4) NOT NULL COMMENT '商品实际成交价',
  `goods_num` int(11) NOT NULL DEFAULT '1' COMMENT '商品数量',
  `goods_image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `commis_rate` tinyint(4) NOT NULL DEFAULT '0' COMMENT '佣金比例',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单商品表';


DROP TABLE IF EXISTS `#__tbl_order_log`;
CREATE TABLE `#__tbl_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_status` tinyint(4) NOT NULL COMMENT '订单状态',
  `delivery_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '交付状态',
  `message` varchar(255) NOT NULL COMMENT '日志描述',
  `latitude` decimal(10,6) DEFAULT NULL COMMENT '纬度',
  `longitude` decimal(11,6) DEFAULT NULL COMMENT '经度',
  `images` text DEFAULT NULL COMMENT '现场图片',
  `create_role` varchar(10) NOT NULL COMMENT '创建人角色',
  `create_by` varchar(32) NOT NULL COMMENT '创建人',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `extra` varchar(255) DEFAULT NULL COMMENT '参数',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_order_status` (`order_status`),
  KEY `idx_delivery_status` (`delivery_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单日志';


DROP TABLE IF EXISTS `#__tbl_order_merge`;
CREATE TABLE `#__tbl_order_merge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `out_trade_no` varchar(32) DEFAULT NULL COMMENT '商家订单号',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '第三方平台交易号',
  `total_amount` decimal(20,4) NOT NULL COMMENT '支付总价',
  `status` tinyint(4) NOT NULL COMMENT '支付状态 0未支付 1已支付 2已关闭',
  `pay_channel` varchar(20) DEFAULT NULL COMMENT '支付渠道 alipay wechat',
  `pay_scene` varchar(20) DEFAULT NULL COMMENT '支付场景 h5 pc',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_out_trade_no` (`out_trade_no`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_trade_no` (`trade_no`),
  KEY `idx_status` (`status`),
  KEY `idx_pay_channel` (`pay_channel`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单合并支付批次表';


DROP TABLE IF EXISTS `#__tbl_order_refund`;
CREATE TABLE `#__tbl_order_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall',
  `order_id` int(11) NOT NULL COMMENT '订单表ID',
  `order_goods_id` int(11) NOT NULL COMMENT '订单商品表ID 0为全部退款',
  `out_refund_no` varchar(32) DEFAULT NULL COMMENT '商户退款单号',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `apply_amount` decimal(20,4) NOT NULL COMMENT '申请退款金额',
  `refund_merchant_id` int(11) NOT NULL COMMENT '退款商户ID,0为系统退款 其他为商户退款 与pay_merchant_id一致',
  `refund_type` tinyint(4) NOT NULL COMMENT '退款类型 1仅退款 2退货退款',
  `refund_status` tinyint(4) NOT NULL COMMENT '退款状态',
  `refund_method` tinyint(4) DEFAULT NULL COMMENT '退款方式 1原路退回 2退回余额 3人工处理',
  `refund_amount` decimal(20,4) NOT NULL COMMENT '退款金额',
  `refund_explain` varchar(255) DEFAULT NULL COMMENT '退款申请说明',
  `refund_images` text COMMENT '退款申请图片',
  `refund_address` varchar(255) DEFAULT NULL COMMENT '退款地址',
  `express_number` varchar(20) DEFAULT NULL COMMENT '物流订单',
  `express_company` varchar(20) DEFAULT NULL COMMENT '物流名称',
  `agree_reason` varchar(255) DEFAULT NULL COMMENT '店铺同意退款备注',
  `agree_time` int(11) DEFAULT NULL COMMENT '店铺同意时间',
  `reject_reason` varchar(255) DEFAULT NULL COMMENT '店铺拒绝退款备注',
  `reject_time` int(11) DEFAULT NULL COMMENT '店铺拒绝时间',
  `success_time` int(11) DEFAULT NULL COMMENT '退款成功时间',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='退款订单表';


DROP TABLE IF EXISTS `#__tbl_order_refund_log`;
CREATE TABLE `#__tbl_order_refund_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refund_id` int(11) NOT NULL COMMENT '退款ID order_refund_log主键ID',
  `refund_status` tinyint(4) NOT NULL COMMENT '退款状态',
  `message` varchar(255) DEFAULT NULL COMMENT '日志描述',
  `create_role` varchar(10) DEFAULT NULL COMMENT '创建人角色 user用户 store(店铺) system(系统)',
  `create_uid` int(11) NOT NULL COMMENT '创建人',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_refund_id` (`refund_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='退款日志表';


DROP TABLE IF EXISTS `#__tbl_store`;
CREATE TABLE `#__tbl_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺自增ID',
  `platform` varchar(20) NOT NULL COMMENT '应用类型 system mall kms house food',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `store_name` varchar(50) NOT NULL COMMENT '店铺名称',
  `store_logo` varchar(255) DEFAULT NULL COMMENT '店铺LOGO',
  `store_introduction` varchar(255) DEFAULT NULL COMMENT '店铺简介',
  `store_business_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '店铺营业状态 0休息 1正常营业',
  `service_fee_rate` decimal(10,2) DEFAULT '0.00' COMMENT '店铺服务费,单位:百分比',
  `category_id` int(11) DEFAULT NULL COMMENT '店铺分类ID',
  `area_id` int(11) DEFAULT NULL COMMENT '地区ID',
  `area_info` varchar(100) DEFAULT NULL COMMENT '店铺地区名称',
  `address` varchar(100) DEFAULT NULL COMMENT '店铺地址',
  `store_latitude` decimal(10,6) DEFAULT 0 COMMENT '店铺的纬度',
  `store_longitude` decimal(11,6) DEFAULT 0 COMMENT '店铺的经度',
  `contact_name` varchar(20) DEFAULT NULL COMMENT '联系人',
  `contact_phone` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `business_license` varchar(255) DEFAULT NULL COMMENT '店铺营业执照',
  `seo_title` varchar(255) DEFAULT NULL COMMENT '店铺SEO标题',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT '店铺SEO关键字',
  `seo_description` varchar(255) DEFAULT NULL COMMENT '店铺SEO描述',
  `avg_describe_score` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '描述评分',
  `avg_logistics_score` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '物流评分',
  `avg_service_score` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '服务评分',
  `sales_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺销量',
  `collect_num` int(11) NOT NULL DEFAULT '0' COMMENT '店铺收藏数量',
  `is_recommend` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否推荐:0否 1是',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '店铺排序',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT '店铺状态:0关闭，1开启',
  `apply_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '店铺申请状态:0待审核，1审核通过，2审核拒绝',
  `apply_remark` varchar(255) DEFAULT NULL COMMENT '店铺申请备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_store_name` (`store_name`),
  KEY `idx_platform` (`platform`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_area_id` (`area_id`),
  KEY `idx_store_business_status` (`store_business_status`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_apply_status` (`apply_status`),
  KEY `idx_is_recommend` (`is_recommend`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_sort` (`sort`),
  KEY `idx_sales_num` (`sales_num`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公共店铺表';


DROP TABLE IF EXISTS `#__tbl_store_auth_user`;
CREATE TABLE `#__tbl_store_auth_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `is_default` tinyint(4) DEFAULT '0' COMMENT '是否为默认 1默认 0',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `create_by` varchar(32) DEFAULT NULL COMMENT '创建人',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='公共店铺授权用户表';


DROP TABLE IF EXISTS `#__tbl_store_category`;
CREATE TABLE `#__tbl_store_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺分类自增ID',
  `platform` varchar(10) NOT NULL COMMENT '应用类型 system mall kms house food',
  `pid` int(11) NOT NULL COMMENT '上级分类ID',
  `name` varchar(20) NOT NULL COMMENT '店铺分类名称',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `is_show` tinyint(4) NOT NULL COMMENT '是否显示 1显示',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '店铺分类排序',
  `service_fee_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '默认服务费率，主要用于显示和申请默认费率',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_platform` (`platform`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺分类';



DROP TABLE IF EXISTS `#__tbl_store_coupon`;
CREATE TABLE `#__tbl_store_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) DEFAULT NULL,
  `coupon_name` varchar(32) DEFAULT NULL COMMENT '优惠券名称',
  `coupon_type` tinyint(4) DEFAULT NULL COMMENT '优惠券类型 满减券 折扣券 直减券',
  `discount_value` decimal(20,4) DEFAULT NULL COMMENT '折扣值（满减券为减免金额，折扣券为折扣比例，直减券为固定减免金额）',
  `min_spend` decimal(20,4) DEFAULT NULL COMMENT '最低消费金额（使用条件）',
  `start_time` int(11) DEFAULT NULL COMMENT '优惠券生效时间',
  `end_time` int(11) DEFAULT NULL COMMENT '优惠券失效时间',
  `total_stock` int(11) DEFAULT NULL COMMENT '总库存数量（优惠券创建时的初始数量）',
  `claimed_stock` int(11) DEFAULT NULL COMMENT '已领取数量（已发放给用户的优惠券数量）',
  `available_stock` int(11) DEFAULT NULL COMMENT '剩余可领取数量（总库存 - 已领取数量）',
  `limit_per_user` int(11) DEFAULT NULL COMMENT '每个用户最多可领取的优惠券数量',
  `claim_type` tinyint(4) DEFAULT NULL COMMENT '优惠券的领取类型 手动 发放 注册',
  `points_required` int(11) DEFAULT NULL COMMENT '领取该优惠券所需的积分数量。如果为0，则表示无需积分即可领取。',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0失效 1正常',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号[预留字段]',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺优惠券表';




DROP TABLE IF EXISTS `#__tbl_store_coupon_user`;
CREATE TABLE `#__tbl_store_coupon_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `coupon_id` int(11) NOT NULL COMMENT '优惠券ID',
  `status` int(11) NOT NULL COMMENT '状态 0未使用 1已使用 2已过期',
  `used_time` int(11) DEFAULT NULL COMMENT '使用时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_coupon_id` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺优惠券关联表';


DROP TABLE IF EXISTS `#__tbl_store_favorites`;
CREATE TABLE `#__tbl_store_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户店铺收藏表';


DROP TABLE IF EXISTS `#__tbl_store_goods_category`;
CREATE TABLE `#__tbl_store_goods_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL COMMENT '分类所属店铺',
  `pid` int(11) NOT NULL COMMENT '上级ID',
  `name` varchar(20) NOT NULL COMMENT '名称',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `is_show` tinyint(4) NOT NULL COMMENT '分类前台显示 0:否 1:是',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺商品分类表';




DROP TABLE IF EXISTS `#__tbl_store_printer`;
CREATE TABLE `#__tbl_store_printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `printer_name` varchar(100) NOT NULL COMMENT '打印机名称',
  `printer_sn` varchar(50) NOT NULL COMMENT '打印机编号',
  `printer_key` varchar(100) NOT NULL COMMENT '打印机密钥',
  `printer_mobile` varchar(20) DEFAULT NULL COMMENT '打印机手机号(WIFI模式选填)',
  `printer_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '打印机绑定状态 1 已绑定 0 未绑定',
  `printer_provider` varchar(20) NOT NULL COMMENT '打印机服务商',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否启用 1是 0否',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_store_printer_sn` (`store_id`, `printer_sn`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_printer_sn` (`printer_sn`),
  KEY `idx_printer_status` (`printer_status`),
  KEY `idx_is_enabled` (`is_enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺打印机配置表';


DROP TABLE IF EXISTS `#__tbl_store_printer_log`;
CREATE TABLE `#__tbl_store_printer_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `printer_id` int(11) NOT NULL COMMENT '打印机ID',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `print_content` text COMMENT '打印内容',
  `print_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '打印状态 0失败 1成功',
  `print_result` text COMMENT '打印结果',
  `print_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '打印类型 1订单打印',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_printer_id` (`printer_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_print_status` (`print_status`),
  KEY `idx_print_type` (`print_type`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='店铺打印机打印记录表';




DROP TABLE IF EXISTS `#__technician`;
CREATE TABLE `#__technician` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '关联用户ID',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `name` varchar(20) NOT NULL COMMENT '师傅名称',
  `mobile` varchar(20) DEFAULT NULL COMMENT '师傅联系电话',
  `avatar` varchar(255) DEFAULT NULL COMMENT '师傅头像',
  `slide_image` varchar(2000) DEFAULT NULL COMMENT '轮播图',
  `gender` tinyint(4) DEFAULT '0' COMMENT '师傅性别 0未知 1男 2女',
  `certificate_info` varchar(255) DEFAULT NULL COMMENT '资质证书性质',
  `work_years` int(11) DEFAULT NULL COMMENT '工作年限',
  `description` text COMMENT '描述',
  `visit_count` int(11) DEFAULT '0' COMMENT '访问次数',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评价次数',
  `avg_score` decimal(3,2) NOT NULL DEFAULT '5.00' COMMENT '评价平均分',
  `service_count` int(11) DEFAULT '0' COMMENT '服务次数',
  `technician_status` tinyint(4) DEFAULT '0' COMMENT '师傅状态 0休息 1接单 2忙碌',
  `technician_fee_rate` int(11) DEFAULT NULL COMMENT '店铺抽师傅的佣金比例',
  `technician_latitude` decimal(10,6) DEFAULT 0 COMMENT '师傅纬度',
  `technician_longitude` decimal(11,6) DEFAULT 0 COMMENT '师傅经度',
  `technician_loc_time` int(11) DEFAULT NULL COMMENT '师傅位置更新时间',
  `balance` decimal(20,4) DEFAULT '0.0000' COMMENT '可用余额',
  `balance_in` decimal(20,4) DEFAULT '0.0000' COMMENT '总收入',
  `balance_out` decimal(20,4) DEFAULT '0.0000' COMMENT '总支出',
  `is_enabled` tinyint(4) DEFAULT '0' COMMENT '是否可用',
  `apply_time` int(11) DEFAULT NULL COMMENT '申请时间',
  `apply_status` tinyint(4) DEFAULT NULL COMMENT '审核状态 0 审核中 1审核通过 2拒绝',
  `apply_remark` varchar(255) DEFAULT NULL COMMENT '申请备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_id` (`user_id`),
  UNIQUE KEY `udx_mobile` (`mobile`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_apply_status` (`apply_status`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_audit_time` (`audit_time`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='上门服务师傅表';


DROP TABLE IF EXISTS `#__technician_balance_log`;
CREATE TABLE `#__technician_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technician_id` int(11) NOT NULL COMMENT '师傅ID',
  `related_id` int(11) DEFAULT NULL COMMENT '关联ID 订单ID 提现至余额ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型 服务 提现 奖励 罚款',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_amount` decimal(20,4) NOT NULL COMMENT '变动金额',
  `before_balance` decimal(20,4) NOT NULL COMMENT '交易前余额 对账',
  `after_balance` decimal(20,4) NOT NULL COMMENT '交易后的总额',
  `change_desc` varchar(255) DEFAULT NULL COMMENT '变更描述',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_technician_id` (`technician_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='师傅流水表';


DROP TABLE IF EXISTS `#__technician_comment`;
CREATE TABLE `#__technician_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '评价用户ID',
  `technician_id` int(11) NOT NULL COMMENT '师傅ID',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `content` text NOT NULL COMMENT '评价内容',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否显示 0不显示 1显示',
  `is_anonymous` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否匿名 0不匿名 1匿名',
  `is_reply` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否回复 0未回复 1已回复',
  `reply_content` text COMMENT '回复内容',
  `reply_time` int(11) DEFAULT NULL COMMENT '回复时间',
  `service_score` tinyint(4) NOT NULL DEFAULT '5' COMMENT '服务评分 1-5分',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_technician_id` (`technician_id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_create_at` (`create_at`),
  UNIQUE KEY `udx_user_order_technician` (`user_id`, `order_id`, `technician_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='师傅评价表';


DROP TABLE IF EXISTS `#__technician_goods_rel`;
CREATE TABLE `#__technician_goods_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `technician_id` int(11) NOT NULL COMMENT '师傅ID',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_goods_technician` (`goods_id`, `technician_id`),
  KEY `idx_technician_id` (`technician_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='师傅关联商品';



DROP TABLE IF EXISTS `#__technician_track`;
CREATE TABLE `#__technician_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technician_id` int(11) NOT NULL COMMENT '师傅ID',
  `latitude` decimal(10,6) NOT NULL COMMENT '纬度',
  `longitude` decimal(11,6) NOT NULL COMMENT '经度',
  `address` varchar(255) NOT NULL DEFAULT '',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_technician_id` (`technician_id`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_latitude` (`latitude`),
  KEY `idx_longitude` (`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='师傅轨迹图';




DROP TABLE IF EXISTS `#__trade_pay_log`;
CREATE TABLE `#__trade_pay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '支付ID',
  `source_type` varchar(20) NOT NULL COMMENT '来源 比如订单,充值',
  `source_id` int(11) NOT NULL COMMENT '来源ID',
  `out_trade_no` varchar(32) DEFAULT NULL COMMENT '商户订单号',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '支付订单号 微信中参数是 transaction_id',
  `pay_merchant_id` int(11) NOT NULL COMMENT '收款商户ID  0为后台收款',
  `pay_channel` varchar(20) DEFAULT NULL COMMENT '支付渠道 alipay wechat',
  `pay_scene` varchar(20) DEFAULT NULL COMMENT '支付场景 H5 APP支付等',
  `pay_amount` decimal(20,4) NOT NULL COMMENT '支付金额',
  `pay_status` int(11) NOT NULL COMMENT '支付状态 0 未支付 1已支付 2已关闭',
  `buyer_id` varchar(128) DEFAULT NULL COMMENT '付款号 支付宝 buyer_id 微信  openid',
  `seller_id` varchar(32) DEFAULT NULL COMMENT '收款号 支付宝 seller_id 微信 mchid',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付时间',
  `close_time` int(11) DEFAULT NULL COMMENT '关闭时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_out_trade_no` (`out_trade_no`),
  UNIQUE KEY `udx_trade_no` (`trade_no`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_source_type` (`source_type`),
  KEY `idx_source_id` (`source_id`),
  KEY `idx_pay_merchant_id` (`pay_merchant_id`),
  KEY `idx_pay_channel` (`pay_channel`),
  KEY `idx_pay_scene` (`pay_scene`),
  KEY `idx_pay_status` (`pay_status`),
  KEY `idx_pay_time` (`pay_time`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户支付记录';


DROP TABLE IF EXISTS `#__trade_refund_log`;
CREATE TABLE `#__trade_refund_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '退款用户ID',
  `source_type` varchar(20) NOT NULL COMMENT '来源 退款',
  `source_id` int(11) NOT NULL COMMENT '来源ID',
  `out_trade_no` varchar(32) DEFAULT NULL COMMENT '商户订单号',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '支付订单号',
  `out_refund_no` varchar(32) NOT NULL COMMENT '商户退款单号',
  `pay_amount` decimal(20,4) NOT NULL COMMENT '支付金额',
  `refund_merchant_id` int(11) NOT NULL COMMENT '退款商户ID',
  `refund_amount` decimal(20,4) NOT NULL COMMENT '退款金额',
  `refund_channel` varchar(20) NOT NULL COMMENT '支付渠道',
  `refund_scene` varchar(20) DEFAULT NULL COMMENT '支付场景 H5 APP',
  `refund_status` tinyint(4) NOT NULL COMMENT '退款状态 0待退款 1退款完成 2退款关闭',
  `refund_time` int(11) NOT NULL COMMENT '退款时间',
  `close_time` int(11) DEFAULT NULL COMMENT '关闭时间',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_out_refund_no` (`out_refund_no`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_source_type` (`source_type`),
  KEY `idx_source_id` (`source_id`),
  KEY `idx_out_trade_no` (`out_trade_no`),
  KEY `idx_trade_no` (`trade_no`),
  KEY `idx_refund_merchant_id` (`refund_merchant_id`),
  KEY `idx_refund_channel` (`refund_channel`),
  KEY `idx_refund_status` (`refund_status`),
  KEY `idx_refund_time` (`refund_time`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易退款记录';


DROP TABLE IF EXISTS `#__trade_transfer_log`;
CREATE TABLE `#__trade_transfer_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `source_type` varchar(20) NOT NULL COMMENT '关联类型 提现',
  `source_id` int(11) NOT NULL COMMENT '关联ID',
  `out_transfer_no` varchar(32) NOT NULL COMMENT '微信out_bill_no 支付宝out_biz_no',
  `transfer_no` varchar(32) DEFAULT NULL COMMENT '转账单号',
  `transfer_type` varchar(20) NOT NULL COMMENT '转账类型 支付宝、微信',
  `transfer_amount` decimal(20,4) NOT NULL COMMENT '转账金额',
  `transfer_status` tinyint(4) NOT NULL COMMENT '状态 0 失败 1成功',
  `transfer_response` text COMMENT '返回结果，',
  `account_type` varchar(20) NOT NULL COMMENT '提现账户类型',
  `account_name` varchar(20) NOT NULL COMMENT '账户名称 支付宝、微信、银行卡',
  `account_number` varchar(32) NOT NULL COMMENT '卡号',
  `account_holder` varchar(10) NOT NULL COMMENT '持有人',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_out_transfer_no` (`out_transfer_no`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_source_type` (`source_type`),
  KEY `idx_source_id` (`source_id`),
  KEY `idx_transfer_no` (`transfer_no`),
  KEY `idx_transfer_type` (`transfer_type`),
  KEY `idx_transfer_status` (`transfer_status`),
  KEY `idx_account_type` (`account_type`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交易转账表';


DROP TABLE IF EXISTS `#__trade_payment_config`;
CREATE TABLE `#__trade_payment_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) NOT NULL COMMENT '商户ID  0为系统',
  `payment_channel` varchar(20) NOT NULL COMMENT '支付渠道 WECHAT ALIPAY',
  `payment_scene` varchar(20) NOT NULL COMMENT '支付场景 H5  PC 小程序 等支付方式',
  `config_data` text COMMENT '支付配置',
  `is_enabled` tinyint(4) DEFAULT NULL COMMENT '是否启用 0未启用 1启用',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_merchant_channel_scene` (`merchant_id`, `payment_channel`, `payment_scene`),
  KEY `idx_payment_channel` (`payment_channel`),
  KEY `idx_payment_scene` (`payment_scene`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_sort` (`sort`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='支付配置表';

INSERT INTO `#__trade_payment_config` (`id`, `merchant_id`, `payment_channel`, `payment_scene`, `config_data`, `is_enabled`, `sort`, `create_at`, `update_at`) VALUES
(1, 0, 'balance_pay', 'h5', '[]', 1, 0, 1743924006, 1743924006),
(2, 0, 'balance_pay', 'pc', '[]', 1, 0, 1743924011, 1743924011),
(3, 0, 'balance_pay', 'app', '[]', 1, 0, 1743924017, 1743924017),
(4, 0, 'balance_pay', 'wechat_mini', '[]', 1, 0, 1743924023, 1743924023),
(5, 0, 'balance_pay', 'douyin_mini', '[]', 1, 0, 1743924026, 1743924026);


DROP TABLE IF EXISTS `#__user`;
CREATE TABLE `#__user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员自增ID',
  `username` varchar(32) NOT NULL COMMENT '会员用户名',
  `password` varchar(128) NOT NULL COMMENT '会员密码',
  `pay_password` varchar(128) DEFAULT NULL COMMENT '支付密码',
  `nickname` varchar(32) DEFAULT NULL COMMENT '会员昵称',
  `avatar` varchar(128) DEFAULT NULL COMMENT '会员头像',
  `sex` tinyint(4) DEFAULT NULL COMMENT '会员性别 0未知 1男 2女',
  `birthday` int(11) DEFAULT NULL COMMENT '会员生日',
  `email` varchar(32) DEFAULT NULL COMMENT '会员邮箱',
  `email_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `mobile_bind` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否绑定手机',
  `qq` varchar(20) DEFAULT NULL COMMENT '会员QQ',
  `inviter_id` int(11) DEFAULT NULL COMMENT '邀请人ID',
  `login_num` int(11) NOT NULL DEFAULT '0' COMMENT '会员登录次数',
  `login_time` int(11) DEFAULT '0' COMMENT '会员当前登录时间',
  `old_login_time` int(11) DEFAULT '0' COMMENT '会员上次登录时间',
  `login_ip` varchar(20) DEFAULT NULL COMMENT '会员当前登录IP',
  `old_login_ip` varchar(20) DEFAULT NULL COMMENT '会员上次登录IP',
  `growth` int(11) DEFAULT '0' COMMENT '成长值',
  `growth_level_id` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '会员积分',
  `points_in` int(11) NOT NULL DEFAULT '0' COMMENT '积分收入总额',
  `points_out` int(11) NOT NULL DEFAULT '0' COMMENT '积分支出总额',
  `balance` decimal(20,4) NOT NULL DEFAULT '0.00000000' COMMENT '预存款可用金额',
  `balance_in` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '收入总额',
  `balance_out` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '支出总额',
  `idcard_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '实名认证状态（0默认1审核中2未通过3已认证）',
  `idcard_name` varchar(32) DEFAULT NULL COMMENT '会员真实姓名',
  `idcard_number` varchar(32) DEFAULT NULL COMMENT '实名认证身份证号',
  `idcard_image1` varchar(255) DEFAULT NULL COMMENT '手持身份证照',
  `idcard_image2` varchar(255) DEFAULT NULL COMMENT '身份证正面照',
  `idcard_image3` varchar(255) DEFAULT NULL COMMENT '身份证反面照',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可用 1为开启 0为关闭',
  `is_distributor` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为分销商 1为分销商 0为非分销商',
  `distributor_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分销商状态 1为正常 2为冻结',
  `distributor_level_id` int(11) NOT NULL DEFAULT '0' COMMENT '分销商等级ID',
  `distributor_balance` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '分销商余额',
  `distributor_balance_in` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '分销商收入总额',
  `distributor_balance_out` decimal(20,4) NOT NULL DEFAULT '0.000000' COMMENT '分销商支出总额',
  `distributor_addtime` int(11) DEFAULT NULL COMMENT '分销商添加时间',
  `following_blogger_count` int(11) NOT NULL DEFAULT '0' COMMENT '关注博主数量',
  `version` int(11) NOT NULL DEFAULT '0' COMMENT '乐观锁版本号',
  `create_at` int(11) NOT NULL COMMENT '会员添加时间',
  `update_at` int(11) DEFAULT NULL COMMENT '会员更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_username` (`username`),
  UNIQUE KEY `udx_mobile` (`mobile`),
  UNIQUE KEY `udx_email` (`email`),
  KEY `idx_inviter_id` (`inviter_id`),
  KEY `idx_mobile_bind` (`mobile_bind`),
  KEY `idx_email_bind` (`email_bind`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_is_distributor` (`is_distributor`),
  KEY `idx_distributor_status` (`distributor_status`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_login_time` (`login_time`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';




-- 用户操作行为记录表
DROP TABLE IF EXISTS `#__user_behavior_log`;
CREATE TABLE `#__user_behavior_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(32) DEFAULT NULL COMMENT '用户名',
  `behavior_type` varchar(50) NOT NULL COMMENT '行为类型 login-登录 register-注册',
  `behavior_scene` varchar(20) DEFAULT NULL COMMENT '行为场景 web-网页 app-应用 mini-小程序 admin-管理后台',
  `ip_address` varchar(45) NOT NULL COMMENT 'IP地址',
  `user_agent` text DEFAULT NULL COMMENT '用户代理',
  `device_type` varchar(50) DEFAULT NULL COMMENT '设备类型 mobile-手机 tablet-平板 desktop-电脑',
  `browser` varchar(50) DEFAULT NULL COMMENT '浏览器 Chrome Firefox Safari Edge',
  `os` varchar(50) DEFAULT NULL COMMENT '操作系统 Windows macOS Linux iOS Android',
  `behavior_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '行为状态 1成功 0失败',
  `failure_reason` varchar(255) DEFAULT NULL COMMENT '失败原因',
  `is_abnormal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否异常行为 0正常 1异常',
  `abnormal_reason` varchar(255) DEFAULT NULL COMMENT '异常原因',
  `risk_level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '风险等级 0无风险 1低风险 2中风险 3高风险',
  `extra_data` text DEFAULT NULL COMMENT '额外数据（JSON格式）',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_username` (`username`),
  KEY `idx_behavior_type` (`behavior_type`),
  KEY `idx_behavior_scene` (`behavior_scene`),
  KEY `idx_ip_address` (`ip_address`),
  KEY `idx_behavior_status` (`behavior_status`),
  KEY `idx_is_abnormal` (`is_abnormal`),
  KEY `idx_risk_level` (`risk_level`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_ip_time` (`ip_address`, `create_at`),
  KEY `idx_user_time` (`user_id`, `create_at`),
  KEY `idx_type_status` (`behavior_type`, `behavior_status`),
  KEY `idx_abnormal_risk` (`is_abnormal`, `risk_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户操作行为记录表';


DROP TABLE IF EXISTS `#__user_address`;
CREATE TABLE `#__user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地址ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `real_name` varchar(32) NOT NULL COMMENT '会员姓名',
  `mob_phone` varchar(11) DEFAULT NULL COMMENT '手机',
  `province_id` int(11) DEFAULT NULL COMMENT '省级ID',
  `city_id` int(11) DEFAULT NULL COMMENT '市级ID',
  `district_id` int(11) NOT NULL DEFAULT '0' COMMENT '区县ID',
  `area_id` int(11) NOT NULL DEFAULT '0' COMMENT '地区ID',
  `area_info` varchar(128) NOT NULL COMMENT '地区信息',
  `poi_address` varchar(255) DEFAULT NULL COMMENT 'POI地址（地图返回的address）',
  `poi_name` varchar(100) DEFAULT NULL COMMENT 'POI名称（地图返回的name）',
  `house_number` varchar(100) DEFAULT NULL COMMENT '门牌号',
  `address_detail` varchar(255) NOT NULL COMMENT '详细地址 poi_address + poi_name + house_number',
  `is_default` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1默认收货地址',
  `latitude` decimal(10,6) DEFAULT 0 COMMENT '纬度',
  `longitude` decimal(11,6) DEFAULT 0 COMMENT '经度',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_province_id` (`province_id`),
  KEY `idx_city_id` (`city_id`),
  KEY `idx_district_id` (`district_id`),
  KEY `idx_area_id` (`area_id`),
  KEY `idx_is_default` (`is_default`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户地址';


DROP TABLE IF EXISTS `#__user_balance_log`;
CREATE TABLE `#__user_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '预存款变更日志自增ID',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `related_id` int(11) NOT NULL COMMENT '关联ID 比如 退款ID 提现ID 充值ID',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型',
  `change_amount` decimal(20,4) NOT NULL COMMENT '变动金额',
  `before_balance` decimal(20,4) NOT NULL COMMENT '交易前余额（用于对账与审计）',
  `after_balance` decimal(20,4) NOT NULL COMMENT '变动后的总额',
  `create_at` int(11) NOT NULL COMMENT '变更添加时间',
  `change_desc` varchar(255) DEFAULT NULL COMMENT '变更描述',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户余额流水';


DROP TABLE IF EXISTS `#__user_growth_level`;
CREATE TABLE `#__user_growth_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(20) NOT NULL COMMENT '等级名称',
  `min_growth` int(11) NOT NULL DEFAULT '0' COMMENT '达到该等级的最低成长值',
  `description` text NOT NULL COMMENT '等级描述',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_level_name` (`level_name`),
  KEY `idx_min_growth` (`min_growth`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分记录表';


INSERT INTO `#__user_growth_level` (`id`, `level_name`, `min_growth`, `description`, `create_at`, `update_at`) VALUES
(1, '普通会员', 0, '', 1743875198, 1743875198),
(2, '银卡会员', 1000, '', 1743875352, 1743875352),
(3, '金卡会员	', 5000, '', 1743875364, 1743875364),
(4, '白金会员	', 20000, '', 1743875376, 1743875376),
(5, '钻石会员	', 100000, '', 1743875389, 1743875389),
(6, '黑卡会员	', 10000000, '', 1743875400, 1743875400);


DROP TABLE IF EXISTS `#__user_growth_log`;
CREATE TABLE `#__user_growth_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `related_id` int(11) DEFAULT '0' COMMENT '关联ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_num` int(11) NOT NULL COMMENT '变动成长值',
  `before_num` int(11) NOT NULL COMMENT '修改前成长值',
  `after_num` int(11) NOT NULL COMMENT '修改后的积分',
  `change_desc` varchar(255) NOT NULL COMMENT '描述',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='成长值记录表';


DROP TABLE IF EXISTS `#__user_identity`;
CREATE TABLE `#__user_identity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `merchant_id` int(11) NOT NULL COMMENT '商户ID 0为系统关联',
  `wx_event_openid` VARCHAR(32)   COMMENT '微信公众号事件openid' ,
  `wx_oauth_openid` VARCHAR(32)   COMMENT '微信网页授权,用于支付,多个用户可关联同一个openid' ,
  `wx_mini_openid` VARCHAR(32)   COMMENT '小程序' ,
  `wx_app_openid` VARCHAR(32)   COMMENT 'APP' ,
  `wx_unionid` VARCHAR(32)   COMMENT '微信unionid,用于账户绑定唯一标识' ,
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_merchant` (`user_id`, `merchant_id`),
  UNIQUE KEY `udx_wx_unionid` (`wx_unionid`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_wx_event_openid` (`wx_event_openid`),
  KEY `idx_wx_oauth_openid` (`wx_oauth_openid`),
  KEY `idx_wx_mini_openid` (`wx_mini_openid`),
  KEY `idx_wx_app_openid` (`wx_app_openid`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户关联身份信息表';


DROP TABLE IF EXISTS `#__user_points_log`;
CREATE TABLE `#__user_points_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `related_id` int(11) DEFAULT '0' COMMENT '关联ID',
  `change_type` varchar(20) NOT NULL COMMENT '变动类型',
  `change_mode` tinyint(4) NOT NULL COMMENT '增加1 减少2',
  `change_num` int(11) NOT NULL COMMENT '变动积分',
  `before_num` int(11) NOT NULL COMMENT '修改前积分 对账',
  `after_num` int(11) NOT NULL COMMENT '交易后的积分',
  `change_desc` varchar(255) NOT NULL COMMENT '变更描述',
  `create_at` int(11) NOT NULL COMMENT '变更添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_related_id` (`related_id`),
  KEY `idx_change_type` (`change_type`),
  KEY `idx_change_mode` (`change_mode`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='积分记录表';


DROP TABLE IF EXISTS `#__user_recharge_log`;
CREATE TABLE `#__user_recharge_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `out_trade_no` varchar(32) DEFAULT NULL COMMENT '支付单号',
  `trade_no` varchar(32) DEFAULT NULL COMMENT '第三方平台交易号',
  `pay_merchant_id` int(11) NOT NULL COMMENT '支付商户ID 0为系统收款',
  `pay_channel` varchar(20) DEFAULT NULL COMMENT '支付渠道 alipay wechat',
  `pay_scene` varchar(20) DEFAULT NULL COMMENT '支付场景 H5 PC 等',
  `recharge_amount` decimal(20,4) DEFAULT NULL COMMENT '充值金额',
  `recharge_status` tinyint(4) DEFAULT NULL COMMENT '充值状态 0未支付 1完成',
  `pay_time` int(11) DEFAULT NULL COMMENT '支付完成时间',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户充值记录';


DROP TABLE IF EXISTS `#__user_withdrawal_account`;
CREATE TABLE `#__user_withdrawal_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `account_type` varchar(10) NOT NULL COMMENT '账户类型，如银行卡、支付宝、微信支付等',
  `account_name` varchar(20) NOT NULL COMMENT '账户名称',
  `account_number` varchar(32) NOT NULL COMMENT '账户号码，如银行卡号、支付宝账号、微信支付账号等',
  `account_holder` varchar(10) NOT NULL COMMENT '账户持有人',
  `is_default` tinyint(4) DEFAULT '0' COMMENT '是否默认账户 1默认',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户提现账户表';


DROP TABLE IF EXISTS `#__user_withdrawal_log`;
CREATE TABLE `#__user_withdrawal_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `out_transfer_no` varchar(32) DEFAULT NULL COMMENT '微信out_bill_no 支付宝out_biz_no',
  `transfer_type` varchar(10) DEFAULT NULL COMMENT '转账类型 微信 支付宝 手工转',
  `transfer_remark` varchar(255) DEFAULT NULL COMMENT '转账备注，针对手工转账',
  `account_type` varchar(10) DEFAULT NULL COMMENT '提现账户类型',
  `account_name` varchar(20) DEFAULT NULL COMMENT '名称',
  `account_number` varchar(32) DEFAULT NULL COMMENT '卡号',
  `account_holder` varchar(10) DEFAULT NULL COMMENT '持有人',
  `apply_amount` decimal(20,4) DEFAULT NULL COMMENT '申请提现金额',
  `fee_amount` decimal(20,4) DEFAULT NULL COMMENT '手续费',
  `withdrawal_amount` decimal(20,4) DEFAULT NULL COMMENT '提现金额',
  `operation_time` int(11) DEFAULT NULL COMMENT '处理时间',
  `operation_remark` varchar(255) DEFAULT NULL COMMENT '处理备注',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 待审核 通过  拒绝',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户提现申请表';



DROP TABLE IF EXISTS `#__wechat_setting`;
CREATE TABLE `#__wechat_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_id` int(11) DEFAULT NULL COMMENT '商户ID',
  `wechat_official_setting` text COMMENT '微信公众号配置json',
  `wechat_official_menu` text COMMENT '微信公众号菜单json',
  `wechat_mini_setting` text COMMENT '微信小程序配置json',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_merchant_id` (`merchant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信配置';


DROP TABLE IF EXISTS `#__wechat_subscribe_record`;
CREATE TABLE `#__wechat_subscribe_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `template_key` varchar(32) NOT NULL COMMENT '模板key',
  `template_id` varchar(64) NOT NULL COMMENT '微信模板ID',
  `subscribe_type` varchar(20) NOT NULL COMMENT '订阅类型 mini=小程序 official=公众号',
  `subscribe_status` varchar(20) NOT NULL COMMENT '订阅状态 accept|reject|ban|filter|unknown',
  `send_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '发送状态 0=未发送 1=已发送 2=发送失败',
  `send_time` int(11) NOT NULL DEFAULT '0' COMMENT '发送时间',
  `send_params` text COMMENT '发送请求参数',
  `send_result` text COMMENT '发送结果详情',
  `subscribe_time` int(11) NOT NULL COMMENT '订阅时间',
  `expire_time` int(11) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `update_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_template_key` (`template_key`),
  KEY `idx_subscribe_type` (`subscribe_type`),
  KEY `idx_subscribe_status` (`subscribe_status`),
  KEY `idx_send_status` (`send_status`),
  KEY `idx_expire_time` (`expire_time`),
  KEY `idx_user_template` (`user_id`, `template_key`, `subscribe_type`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信订阅记录表'; 


DROP TABLE IF EXISTS `#__wechat_push_log`;
CREATE TABLE `#__wechat_push_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `merchant_id` int(11) NOT NULL DEFAULT '0' COMMENT '商户ID',
  `type` varchar(20) NOT NULL COMMENT '类别 official=公众号 mini=小程序',
  `message` text NOT NULL COMMENT '消息内容(JSON)',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_merchant_id` (`merchant_id`),
  KEY `idx_type` (`type`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_merchant_type` (`merchant_id`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='微信推送消息日志表'; 