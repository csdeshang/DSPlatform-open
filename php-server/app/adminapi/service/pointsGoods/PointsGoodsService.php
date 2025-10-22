<?php

namespace app\adminapi\service\pointsGoods;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\pointsGoods\PointsGoodsDao;
use app\deshang\exceptions\CommonException;

class PointsGoodsService extends BaseAdminService
{
    /**
     * 获取积分商品分页列表
     * 
     * @param array $data 查询参数
     * @return array 分页数据
     */
    public function getPointsGoodsPages($data)
    {
        $condition = [];
        
        // 商品名称模糊搜索
        if (isset($data['goods_name']) && !empty($data['goods_name'])) {
            $condition[] = ['goods_name', 'like', '%' . $data['goods_name'] . '%'];
        }
        
        // 商品状态筛选
        if (isset($data['goods_status']) && in_array($data['goods_status'], [0, 1])) {
            $condition[] = ['goods_status', '=', $data['goods_status']];
        }
        
        // 分类筛选
        if (isset($data['category_id']) && $data['category_id'] > 0) {
            $condition[] = ['category_id', '=', $data['category_id']];
        }
        
        // 未删除
        $condition[] = ['is_deleted', '=', 0];

        $result = (new PointsGoodsDao())->getPointsGoodsPages($condition);
        
        // 处理分页数据中的轮播图，将字符串转换为数组
        if (isset($result['data']) && is_array($result['data'])) {
            foreach ($result['data'] as &$item) {
                if (isset($item['slide_image']) && is_string($item['slide_image'])) {
                    $item['slide_image'] = $item['slide_image'] ? explode(',', $item['slide_image']) : [];
                }
            }
        }
        
        return $result;
    }

    /**
     * 获取积分商品详情
     * 
     * @param int $id 积分商品ID
     * @return array 积分商品详情
     */
    public function getPointsGoodsInfo(int $id)
    {
        $result = (new PointsGoodsDao())->getPointsGoodsInfoById($id);
        
        // 处理轮播图数据，将字符串转换为数组
        if ($result && isset($result['slide_image']) && is_string($result['slide_image'])) {
            $result['slide_image'] = $result['slide_image'] ? explode(',', $result['slide_image']) : [];
        }
        
        return $result;
    }

    /**
     * 创建积分商品
     * 
     * @param array $data 积分商品数据
     * @return int 新创建的积分商品ID
     */
    public function createPointsGoods(array $data)
    {
        // 设置创建时间
        $data['create_at'] = time();
        
        // 处理轮播图 - 保持数组格式
        if (isset($data['slide_image']) && is_array($data['slide_image'])) {
            // 自动设置商品主图为第一张轮播图
            if (!empty($data['slide_image'])) {
                $data['goods_image'] = $data['slide_image'][0];
            }
            // 将数组转换为逗号分隔的字符串存储到数据库
            $data['slide_image'] = implode(',', $data['slide_image']);
        }
        
        // 设置默认值
        $data['click_num'] = 0;
        $data['exchange_num'] = 0;
        $data['is_deleted'] = 0;

        return (new PointsGoodsDao())->createPointsGoods($data);
    }

    /**
     * 更新积分商品
     * 
     * @param array $data 积分商品数据
     * @return bool 是否更新成功
     */
    public function updatePointsGoods(array $data)
    {
        // 设置更新时间
        $data['update_at'] = time();
        
        // 处理轮播图 - 保持数组格式
        if (isset($data['slide_image']) && is_array($data['slide_image'])) {
            // 自动设置商品主图为第一张轮播图
            if (!empty($data['slide_image'])) {
                $data['goods_image'] = $data['slide_image'][0];
            }
            // 将数组转换为逗号分隔的字符串存储到数据库
            $data['slide_image'] = implode(',', $data['slide_image']);
        }

        $condition = [['id', '=', $data['id']]];
        return (new PointsGoodsDao())->updatePointsGoods($condition, $data);
    }

    /**
     * 删除积分商品
     * 
     * @param int $id 积分商品ID
     * @return bool 是否删除成功
     */
    public function deletePointsGoods(int $id)
    {
        $condition = [['id', '=', $id]];
        return (new PointsGoodsDao())->softDeletePointsGoods($condition);
    }

}
