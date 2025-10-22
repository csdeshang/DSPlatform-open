<?php

namespace app\adminapi\service\pointsGoods;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\pointsGoods\PointsGoodsEvaluateDao;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

class PointsGoodsEvaluateService extends BaseAdminService
{
    /**
     * 获取积分商品评价分页列表
     * 
     * @param array $data 查询参数
     * @return array 分页数据
     */
    public function getPointsGoodsEvaluatePages($data)
    {
        $condition = [];
        
        // 用户ID筛选
        if (isset($data['user_id']) && $data['user_id'] > 0) {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        // 商品ID筛选
        if (isset($data['goods_id']) && $data['goods_id'] > 0) {
            $condition[] = ['goods_id', '=', $data['goods_id']];
        }
        
        // 订单ID筛选
        if (isset($data['order_id']) && $data['order_id'] > 0) {
            $condition[] = ['order_id', '=', $data['order_id']];
        }
        
        // 显示状态筛选
        if (isset($data['is_show']) && in_array($data['is_show'], ['0', '1'])) {
            $condition[] = ['is_show', '=', $data['is_show']];
        }
        
        // 匿名状态筛选
        if (isset($data['is_anonymous']) && in_array($data['is_anonymous'], ['0', '1'])) {
            $condition[] = ['is_anonymous', '=', $data['is_anonymous']];
        }
        
        // 未删除
        $condition[] = ['is_deleted', '=', 0];

        $result = (new PointsGoodsEvaluateDao())->getPointsGoodsEvaluatePages($condition);
        
        return $result;
    }

    /**
     * 切换积分商品评价字段状态
     * 
     * @param array $data 切换数据
     * @return bool 是否切换成功
     */
    public function togglePointsGoodsEvaluateField(array $data)
    {
        $evaluate = (new PointsGoodsEvaluateDao())->getPointsGoodsEvaluateInfoById($data['id']);
        if (empty($evaluate)) {
            throw new CommonException('评价不存在');
        }

        $field = $data['field'];
        $currentValue = $evaluate[$field];
        $newValue = $currentValue == '1' ? '0' : '1';

        $updateData = [
            $field => $newValue,
            'update_at' => time()
        ];

        $condition = [['id', '=', $data['id']]];
        return (new PointsGoodsEvaluateDao())->updatePointsGoodsEvaluate($condition, $updateData);
    }

    /**
     * 商家回复积分商品评价
     * 
     * @param array $data 回复数据
     * @return bool 是否回复成功
     */
    public function replyPointsGoodsEvaluate(array $data)
    {
        $evaluate = (new PointsGoodsEvaluateDao())->getPointsGoodsEvaluateInfoById($data['id']);
        if (empty($evaluate)) {
            throw new CommonException('评价不存在');
        }

        $updateData = [
            'reply_content' => $data['reply_content'],
            'reply_time' => time(),
            'update_at' => time()
        ];

        $condition = [['id', '=', $data['id']]];
        return (new PointsGoodsEvaluateDao())->updatePointsGoodsEvaluate($condition, $updateData);
    }

}