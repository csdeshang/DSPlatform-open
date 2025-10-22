<?php

namespace app\adminapi\service\video;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\video\VideoCommentDao;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

class VideoCommentService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取视频评论分页列表
     */
    public function getVideoCommentPages($data)
    {
        $condition = [];
        
        // 用户ID搜索
        if (isset($data['user_id']) && $data['user_id'] != '') {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 内容类型搜索
        if (isset($data['content_type']) && $data['content_type'] != '') {
            $condition[] = ['content_type', '=', $data['content_type']];
        }
        
        // 内容ID搜索
        if (isset($data['content_id']) && $data['content_id'] != '') {
            $condition[] = ['content_id', '=', $data['content_id']];
        }
        
        // 是否显示
        if (isset($data['is_show']) && $data['is_show'] != '') {
            $condition[] = ['is_show', '=', $data['is_show']];
        }
        
        // 是否置顶
        if (isset($data['is_top']) && $data['is_top'] != '') {
            $condition[] = ['is_top', '=', $data['is_top']];
        }

        $result = (new VideoCommentDao())->getWithRelVideoCommentPages($condition);
        return $result;
    }

    /**
     * 切换字段状态（专门用于布尔字段）
     */
    public function toggleVideoCommentField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_show', 'is_top'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new VideoCommentDao())->getVideoCommentInfo([['id', '=', $id]]);
        if (empty($currentInfo)) {
            throw new CommonException('评论不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new VideoCommentDao())->updateVideoComment($condition, $updateData);
        return $result;
    }
} 