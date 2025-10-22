<?php

namespace app\adminapi\service\video;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\video\VideoShortDao;
use app\deshang\exceptions\CommonException;

class VideoShortService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取短视频分页列表
     * @param array $data
     * @return array
     */
    public function getVideoShortPages(array $data): array
    {
        $condition = [];
        
        if (isset($data['title']) && $data['title'] != '') {
            $condition[] = ['title', 'like', '%' . $data['title'] . '%'];
        }
        
        if (isset($data['blogger_id']) && $data['blogger_id'] != '') {
            $condition[] = ['blogger_id', '=', $data['blogger_id']];
        }
        
        if (isset($data['type']) && $data['type'] != '') {
            $condition[] = ['type', '=', $data['type']];
        }
        
        if (isset($data['audit_status']) && $data['audit_status'] != '') {
            $condition[] = ['audit_status', '=', $data['audit_status']];
        }
        
        if (isset($data['is_recommend']) && $data['is_recommend'] != '') {
            $condition[] = ['is_recommend', '=', $data['is_recommend']];
        }

        $result = (new VideoShortDao())->getWithRelVideoShortPages($condition);
        
        return $result;
    }

    /**
     * 获取短视频详情
     * @param int $id
     * @return array
     */
    public function getVideoShortInfo(int $id): array
    {
        $condition = [['id', '=', $id]];
        $result = (new VideoShortDao())->getVideoShortInfo($condition);
        return $result;
    }

    /**
     * 更新短视频信息
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateVideoShort(int $id, array $data): int
    {
        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new VideoShortDao())->updateVideoShort($condition, $data);
        return $result;
    }

    /**
     * 切换短视频字段状态
     * @param array $data
     * @return int
     */
    public function toggleVideoShortField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_recommend', 'is_top', 'is_hot'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new VideoShortDao())->getVideoShortInfoById($id);
        if (empty($currentInfo)) {
            throw new CommonException('短视频不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new VideoShortDao())->updateVideoShort($condition, $updateData);
        return $result;
    }

    /**
     * 审核短视频
     * @param array $data
     * @return int
     */
    public function auditVideoShort(array $data): int
    {
        $id = $data['id'];
        $condition = [['id', '=', $id]];
        
        $updateData = [
            'audit_status' => $data['audit_status'],
            'audit_remark' => $data['audit_remark'],
            'audit_time' => time()
        ];
        
        // 如果审核通过，设置发布时间
        if ($data['audit_status'] == 1) {
            $updateData['publish_at'] = time();
        }
        
        $result = (new VideoShortDao())->updateVideoShort($condition, $updateData);
        return $result;
    }
} 