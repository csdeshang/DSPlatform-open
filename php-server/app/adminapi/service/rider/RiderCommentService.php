<?php

namespace app\adminapi\service\rider;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\rider\RiderCommentDao;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

class RiderCommentService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取骑手评论分页列表
     */
    public function getRiderCommentPages($data)
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
        
        // 骑手ID搜索
        if (isset($data['rider_id']) && $data['rider_id'] != '') {
            $condition[] = ['rider_id', '=', $data['rider_id']];
        }
        
        // 订单ID搜索
        if (isset($data['order_id']) && $data['order_id'] != '') {
            $condition[] = ['order_id', '=', $data['order_id']];
        }
        
        // 是否显示
        if (isset($data['is_show']) && $data['is_show'] != '') {
            $condition[] = ['is_show', '=', $data['is_show']];
        }
        
        // 是否回复
        if (isset($data['is_reply']) && $data['is_reply'] != '') {
            $condition[] = ['is_reply', '=', $data['is_reply']];
        }

        $result = (new RiderCommentDao())->getWithRelRiderCommentPages($condition);
        return $result;
    }

    /**
     * 切换字段状态（专门用于布尔字段）
     */
    public function toggleRiderCommentField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_show', 'is_anonymous', 'is_reply'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new RiderCommentDao())->getRiderCommentById($id);
        if (empty($currentInfo)) {
            throw new CommonException('评论不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new RiderCommentDao())->updateRiderComment($condition, $updateData);
        return $result;
    }
} 