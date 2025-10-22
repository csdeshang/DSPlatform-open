DROP TABLE IF EXISTS `#__blogger`;
CREATE TABLE `#__blogger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blogger_name` varchar(32) NOT NULL COMMENT '博主昵称',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `description` text COMMENT '描述',
  `follower_count` int(11) DEFAULT '0' COMMENT '粉丝数 关注数在user表 following_blogger_count',
  `video_count` int(11) DEFAULT '0' COMMENT '视频数量',
  `drama_count` int(11) DEFAULT '0' COMMENT '短剧数量',
  `live_count` int(11) DEFAULT '0' COMMENT '直播次数',
  `total_likes` int(11) DEFAULT '0' COMMENT '总获赞数',
  `total_views` int(11) DEFAULT '0' COMMENT '总播放量',
  `total_collect` int(11) DEFAULT '0' COMMENT '总收藏',
  `verification_status` tinyint(4) DEFAULT '0' COMMENT '认证状态 0待认证 1认证通过 2认证失败',
  `verification_type` tinyint(4) DEFAULT '0' COMMENT '认证类型 1个人认证 2企业认证',
  `verification_desc` varchar(255) DEFAULT NULL COMMENT '认证说明',
  `is_live_enabled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否开通直播权限',
  `is_drama_enabled` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否开通短剧权限',
  `is_enabled` tinyint(4) DEFAULT '1' COMMENT '是否可用',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_id` (`user_id`),
  KEY `idx_blogger_name` (`blogger_name`),
  KEY `idx_verification_status` (`verification_status`),
  KEY `idx_is_enabled` (`is_enabled`),
  KEY `idx_follower_count` (`follower_count`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短视频博主';


DROP TABLE IF EXISTS `#__blogger_follow`;
CREATE TABLE `#__blogger_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `blogger_id` int(11) NOT NULL COMMENT '博主ID',
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_blogger` (`user_id`, `blogger_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_blogger_id` (`blogger_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='博主关注表';


DROP TABLE IF EXISTS `#__video_category`;
CREATE TABLE `#__video_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '父分类ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `type` varchar(20) NOT NULL COMMENT '类型 short/drama/live',
  `description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示',
  `create_at` int(11) NOT NULL,
  `update_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),  
  KEY `idx_sort` (`sort`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频分类表'; 

INSERT INTO `#__video_category` (`id`, `pid`, `name`, `type`, `description`, `sort`, `is_show`, `create_at`, `update_at`) VALUES
(1, 0, '美食', 'short', '', 255, 1, 1751465022, 1751465022),
(2, 0, '家居', 'short', '', 255, 1, 1751465033, 1751465033),
(3, 0, '风景', 'short', '', 255, 1, 1751465039, 1751465039),
(4, 0, '情感', 'short', '', 255, 1, 1751465064, 1751465064),
(5, 4, '婚姻', 'short', '', 255, 1, 1751465083, 1751465083),
(6, 4, '家庭', 'short', '', 255, 1, 1751465090, 1751465090);


DROP TABLE IF EXISTS `#__video_collect`;
CREATE TABLE `#__video_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content_type` varchar(20) NOT NULL COMMENT '内容类型 short/drama/live',
  `content_id` int(11) NOT NULL,
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_content` (`user_id`, `content_type`, `content_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_content` (`content_type`, `content_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='收藏表';


DROP TABLE IF EXISTS `#__video_comment`;
CREATE TABLE `#__video_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `blogger_id` int(11) NOT NULL COMMENT '博主ID(用于管理评论)',
  `content_type` varchar(20) NOT NULL COMMENT '内容类型 short/drama/live',
  `content_id` int(11) NOT NULL COMMENT '内容ID',
  `comment_content` varchar(255) NOT NULL COMMENT '评论内容',
  `pid` int(11) NOT NULL COMMENT '父评论ID(0表示一级评论)',
  `like_count` int(11) DEFAULT '0' COMMENT '点赞数',
  `reply_count` int(11) DEFAULT '0' COMMENT '回复数',
  `is_top` tinyint(4) DEFAULT '0' COMMENT '是否置顶',
  `is_show` tinyint(4) DEFAULT '1' COMMENT '是否显示 0不显示 1显示',
  `ip_address` varchar(50) DEFAULT NULL COMMENT 'IP地址',
  `create_at` int(11) DEFAULT NULL,
  `update_at` int(11) DEFAULT NULL,
  `is_deleted` tinyint(4) DEFAULT '0' COMMENT '是否删除 0正常 1删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_content` (`content_type`, `content_id`, `is_deleted`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_is_top` (`is_top`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_like_count` (`like_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频评论(通用)';

DROP TABLE IF EXISTS `#__video_comment_like`;
CREATE TABLE `#__video_comment_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `comment_id` int(11) NOT NULL COMMENT '评论ID',
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_comment` (`user_id`, `comment_id`),
  KEY `idx_comment_id` (`comment_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='评论点赞表';

DROP TABLE IF EXISTS `#__video_like`;
CREATE TABLE `#__video_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `content_type` varchar(20) NOT NULL COMMENT '内容类型 short/drama/live',
  `content_id` int(11) NOT NULL COMMENT '内容ID',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_content` (`user_id`, `content_type`, `content_id`),
  KEY `idx_content` (`content_type`, `content_id`),
  KEY `idx_create_at` (`create_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短视频点赞表';

DROP TABLE IF EXISTS `#__video_view_log`;
CREATE TABLE `#__video_view_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `content_type` varchar(20) NOT NULL COMMENT '内容类型 short/drama/live',
  `content_id` int(11) NOT NULL COMMENT '内容ID',
  `blogger_id` int(11) NOT NULL COMMENT '博主ID',
  `ip_address` varchar(50) DEFAULT NULL COMMENT 'IP地址',
  `create_at` int(11) NOT NULL COMMENT '首次观看时间',
  `update_at` int(11) DEFAULT NULL COMMENT '最后观看时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_user_content_date` (`user_id`, `content_type`, `content_id`, `create_at`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_content` (`content_type`, `content_id`),
  KEY `idx_blogger_id` (`blogger_id`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_update_at` (`update_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='视频浏览记录表';

DROP TABLE IF EXISTS `#__video_short`;
CREATE TABLE `#__video_short` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blogger_id` int(11) NOT NULL COMMENT '博主ID',
  `type` tinyint(4) DEFAULT NULL COMMENT '内容类型 1video 2image',
  `title` varchar(32) NOT NULL COMMENT '标题',
  `description` text NOT NULL COMMENT '描述',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID',
  `cover_image` varchar(255) DEFAULT NULL COMMENT '封面图',
  `video_url` varchar(255) DEFAULT NULL COMMENT '视频地址(视频类型)',
  `images_url` text COMMENT '图片数组(图片类型)',
  `view_count` int(11) DEFAULT '0' COMMENT '观看次数',
  `like_count` int(11) DEFAULT '0' COMMENT '点赞数',
  `comment_count` int(11) DEFAULT '0' COMMENT '评论数',
  `share_count` int(11) DEFAULT '0' COMMENT '分享数',
  `collect_count` int(11) DEFAULT '0' COMMENT '收藏数',
  `is_recommend` tinyint(4) DEFAULT '0' COMMENT '是否推荐',
  `is_top` tinyint(4) DEFAULT '0' COMMENT '是否置顶',
  `is_hot` tinyint(4) DEFAULT '0' COMMENT '是否热门',
  `audit_status` tinyint(4) DEFAULT '0' COMMENT '审核状态 0审核中 1已发布 2已下架',
  `audit_remark` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `audit_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `publish_at` int(11) DEFAULT NULL COMMENT '发布时间',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0未删除 1已删除',
  `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_blogger_id` (`blogger_id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_audit_status` (`audit_status`),
  KEY `idx_create_at` (`create_at`),
  KEY `idx_view_count` (`view_count`),
  KEY `idx_like_count` (`like_count`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短视频基础信息表';

DROP TABLE IF EXISTS `#__video_short_goods_rel`;
CREATE TABLE `#__video_short_goods_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_short_id` int(11) NOT NULL COMMENT '短视频ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udx_video_goods` (`video_short_id`, `goods_id`),
  KEY `idx_video_short_id` (`video_short_id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短视频关联商品表';


