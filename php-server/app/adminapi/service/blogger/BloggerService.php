<?php

namespace app\adminapi\service\blogger;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\blogger\BloggerDao;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

/**
 * 管理端博主服务类
 */
class BloggerService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取博主分页列表
     * @param array $data 查询条件
     * @return array
     */
    public function getBloggerPages($data)
    {
        $condition = [];
        
        // 博主昵称搜索
        if (!empty($data['blogger_name'])) {
            $condition[] = ['blogger_name', 'like', '%' . $data['blogger_name'] . '%'];
        }
        
        // 用户ID搜索
        if (!empty($data['user_id'])) {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 认证状态筛选
        if (isset($data['verification_status']) && $data['verification_status'] !== '') {
            $condition[] = ['verification_status', '=', $data['verification_status']];
        }
        
        // 认证类型筛选
        if (!empty($data['verification_type'])) {
            $condition[] = ['verification_type', '=', $data['verification_type']];
        }
        
        // 直播权限筛选
        if (isset($data['is_live_enabled']) && $data['is_live_enabled'] !== '') {
            $condition[] = ['is_live_enabled', '=', $data['is_live_enabled']];
        }
        
        // 短剧权限筛选
        if (isset($data['is_drama_enabled']) && $data['is_drama_enabled'] !== '') {
            $condition[] = ['is_drama_enabled', '=', $data['is_drama_enabled']];
        }
        
        // 是否可用筛选
        if (isset($data['is_enabled']) && $data['is_enabled'] !== '') {
            $condition[] = ['is_enabled', '=', $data['is_enabled']];
        }

        $result = (new BloggerDao())->getWithRelBloggerPages($condition);
        return $result;
    }

    /**
     * 获取博主详情
     * @param int $id 博主ID
     * @return array
     */
    public function getBloggerInfo($id)
    {
        $condition = [
            ['id', '=', $id]
        ];
        $result = (new BloggerDao())->getBloggerInfo($condition);
        if (empty($result)) {
            throw new CommonException('博主不存在');
        }
        return $result;
    }

    /**
     * 更新博主信息
     * @param int $id 博主ID
     * @param array $data 更新数据
     * @return int
     */
    public function updateBlogger(int $id, array $data): int
    {
        $condition = [];
        $condition[] = ['id', '=', $id];

        // 添加更新时间
        $data['update_at'] = time();

        $result = (new BloggerDao())->updateBlogger($condition, $data);
        return $result;
    }

    /**
     * 切换字段状态（专门用于布尔字段）
     */
    public function toggleBloggerField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_live_enabled', 'is_drama_enabled', 'is_enabled'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = $this->getBloggerInfo($id);
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == 1 ? 0 : 1;
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new BloggerDao())->updateBlogger($condition, $updateData);
        return $result;
    }
} 