<?php


namespace app\listener\user;

use think\facade\Db;
use app\deshang\exceptions\CommonException;


use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\common\enum\user\UserGrowthEnum;
use app\deshang\service\user\DeshangUserGrowthService;

use app\common\dao\store\TblStoreDao;
use app\common\dao\goods\TblGoodsCommentDao;
use app\common\dao\goods\TblGoodsDao;



class UserGoodsCommentListener
{
    public function handle(array $params)
    {
        // 评论获取积分
        $this->goodsCommentGetPoints($params);

        // 评论获取成长值
        $this->goodsCommentGetGrowth($params);

        // 更新店铺评分
        $this->updateStoreScore($params);
        
        // 更新商品评分
        $this->updateGoodsScore($params);
    }


    // 评论获取积分
    public function goodsCommentGetPoints($params)
    {
        // 评论获取积分
        $points_review_enabled = sysConfig('points:points_review_enabled');
        if ($points_review_enabled == 1) {
            // 积分
            $points_review_amount = sysConfig('points:points_review_amount');

            if ($points_review_amount > 0) {
                $add_data = array(
                    'user_id' => $params['user_id'],
                    'related_id' => 0,
                    'change_type' => UserPointsEnum::TYPE_GOODS_COMMENT,
                    'change_mode' => UserPointsEnum::MODE_INCREASE,
                    'change_num' => $points_review_amount,
                    'change_desc' => '评论获取积分',
                );

                Db::startTrans();
                try {
                    (new DeshangUserPointsService())->modifyUserPoints($add_data);
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    throw new CommonException('获取到的异常' . $e->getMessage());
                }
            }
        }
    }


    // 登录获取成长值
    public function goodsCommentGetGrowth($params)
    {
        // 评论获取成长值
        $growth_review_enabled = sysConfig('growth:growth_review_enabled');
        if ($growth_review_enabled == 1) {
            // 成长值
            $growth_review_amount = sysConfig('growth:growth_review_amount');

            if ($growth_review_amount > 0) {
                $add_data = array(
                    'user_id' => $params['user_id'],
                    'related_id' => 0,
                    'change_type' => UserGrowthEnum::TYPE_GOODS_COMMENT,
                    'change_mode' => UserGrowthEnum::MODE_INCREASE,
                    'change_num' => $growth_review_amount,
                    'change_desc' => '评论获取成长值',
                );
                Db::startTrans();
                try {
                    (new DeshangUserGrowthService())->modifyUserGrowth($add_data);
                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    throw new CommonException('获取到的异常' . $e->getMessage());
                }
            }
        }
    }


    // 更新店铺评分
    public function updateStoreScore($params)
    {
        $store_id = $params['store_id'];

        // 获取店铺信息
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $store_id]]);
        if (empty($store_info)) {
            throw new CommonException('评价更新店铺评分-店铺不存在');
        }

        // 计算1年内的评分（从当前时间往前推1年）
        $one_year_ago = time() - (365 * 24 * 3600);

        // 使用ThinkPHP查询构建器直接计算各项评分的平均值
        $result = Db::name('tbl_goods_comment')
            ->where('store_id', $store_id)
            ->where('create_at', '>=', $one_year_ago)
            ->where('is_show', 1)
            ->where('is_deleted', 0)
            ->field([
                'AVG(describe_score) as avg_describe_score',
                'AVG(logistics_score) as avg_logistics_score',
                'AVG(service_score) as avg_service_score',
                'COUNT(*) as total_count'
            ])
            ->find();

        if (empty($result) || $result['total_count'] == 0) {
            // 如果没有评论，保持默认评分
            return;
        }

        // 计算平均分（保留2位小数）
        $avg_describe_score = $result['avg_describe_score'] ? round($result['avg_describe_score'], 2) : 0.00;
        $avg_logistics_score = $result['avg_logistics_score'] ? round($result['avg_logistics_score'], 2) : 0.00;
        $avg_service_score = $result['avg_service_score'] ? round($result['avg_service_score'], 2) : 0.00;

        // 更新店铺评分
        $update_data = [
            'avg_describe_score' => $avg_describe_score,
            'avg_logistics_score' => $avg_logistics_score,
            'avg_service_score' => $avg_service_score,
        ];


        (new TblStoreDao())->updateStore([['id', '=', $store_id]], $update_data);
    }




    // 更新商品评分
    public function updateGoodsScore($params)
    {
        $goods_ids = $params['goods_ids'] ?? [];
        
        if (empty($goods_ids)) {
            return;
        }
        
        // 计算1年内的评分（从当前时间往前推1年）
        $one_year_ago = time() - (365 * 24 * 3600);
        
        // 遍历每个商品，计算平均评分
        foreach ($goods_ids as $goods_id) {
            // 使用ThinkPHP查询构建器直接计算商品评分的平均值
            $result = Db::name('tbl_goods_comment')
                ->where('goods_id', $goods_id)
                ->where('create_at', '>=', $one_year_ago)
                ->where('is_show', 1)
                ->where('is_deleted', 0)
                ->field([
                    'AVG(goods_score) as avg_goods_score',
                    'COUNT(*) as total_count'
                ])
                ->find();
            
            if (empty($result) || $result['total_count'] == 0) {
                // 如果没有评论，保持默认评分
                continue;
            }
            
            // 计算平均分（保留2位小数）
            $avg_goods_score = $result['avg_goods_score'] ? round($result['avg_goods_score'], 2) : 0.00;
            
            // 更新商品评分
            $update_data = [
                'avg_goods_score' => $avg_goods_score,
            ];
            
            (new TblGoodsDao())->updateGoods([['id', '=', $goods_id]], $update_data);
        }
    }






}
