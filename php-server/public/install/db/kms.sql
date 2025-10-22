
-- 课时表（关联商品表）
DROP TABLE IF EXISTS `#__kms_lesson`;
CREATE TABLE `#__kms_lesson` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '课时ID',
  `goods_id` int(11) NOT NULL COMMENT '商品ID（课程ID）',
  `store_id` int(11) NOT NULL COMMENT '店铺ID',
  `lesson_name` varchar(255) NOT NULL COMMENT '课时名称',
  `lesson_desc` text COMMENT '课时描述',
  `lesson_type` tinyint(1) DEFAULT 1 COMMENT '课时类型：1-视频 2-图文 3-音频',
  `video_url` varchar(500) DEFAULT NULL COMMENT '视频URL',
  `audio_url` varchar(500) DEFAULT NULL COMMENT '音频URL',
  `content` text COMMENT '图文内容',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `is_free` tinyint(1) DEFAULT 0 COMMENT '是否免费：0-收费 1-免费',
  `create_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_at` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_store_id` (`store_id`),
  KEY `idx_sort` (`sort`),
  KEY `idx_is_free` (`is_free`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='KMS课时表';