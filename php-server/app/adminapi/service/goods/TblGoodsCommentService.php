<?php

namespace app\adminapi\service\goods;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\goods\TblGoodsCommentDao;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

// 商品评论 多平台通用
class TblGoodsCommentService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取商品评论分页列表
     */
    public function getTblGoodsCommentPages($data)
    {
        $condition = [];
        
        // 平台类型搜索
        if (isset($data['platform']) && $data['platform'] != '') {
            $condition[] = ['platform', '=', $data['platform']];
        }
        
        // 用户ID搜索
        if (isset($data['user_id']) && $data['user_id'] != '') {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 商品ID搜索
        if (isset($data['goods_id']) && $data['goods_id'] != '') {
            $condition[] = ['goods_id', '=', $data['goods_id']];
        }
        
        // 商品名搜索
        if (isset($data['goods_name']) && $data['goods_name'] != '') {
            $goodsIds = SearchHelper::getGoodsIdsByGoodsName($data['goods_name']);
            $condition[] = ['goods_id', 'in', $goodsIds];
        }
        
        // 店铺ID搜索
        if (isset($data['store_id']) && $data['store_id'] != '') {
            $condition[] = ['store_id', '=', $data['store_id']];
        }
        
        // 店铺名搜索
        if (isset($data['store_name']) && $data['store_name'] != '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
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
        
        // 是否匿名
        if (isset($data['is_anonymous']) && $data['is_anonymous'] != '') {
            $condition[] = ['is_anonymous', '=', $data['is_anonymous']];
        }

        $result = (new TblGoodsCommentDao())->getWithRelGoodsCommentPages($condition);
        return $result;
    }

    /**
     * 切换字段状态（专门用于布尔字段）
     */
    public function toggleTblGoodsCommentField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_show', 'is_anonymous', 'is_reply'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new TblGoodsCommentDao())->getGoodsCommentById($id);
        if (empty($currentInfo)) {
            throw new CommonException('评论不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new TblGoodsCommentDao())->updateGoodsComment($condition, $updateData);
        return $result;
    }
}