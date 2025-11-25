<?php

namespace app\deshang\service\store;

use think\facade\Db;
use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;
use app\common\dao\store\TblStoreDao;

/**
 * 店铺评分服务
 */
class DeshangTblStoreScoreService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新店铺评分
     * 
     * 根据指定时间范围内的评论计算并更新店铺的平均评分
     * 
     * 注意：
     * - 此方法不管理事务，事务由外部调用方管理
     * 
     * @param int $store_id 店铺ID
     * @param int $timeRange 时间范围（秒），默认1年（365 * 24 * 3600）
     * @return bool true 表示成功更新，false 表示店铺不存在或没有评论
     * @throws CommonException 当店铺不存在时抛出异常
     */
    public function updateStoreScore(int $store_id, int $timeRange = 365 * 24 * 3600): bool
    {
        if (empty($store_id)) {
            return false;
        }

        // 获取店铺信息
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $store_id]]);
        if (empty($store_info)) {
            throw new CommonException('评价更新店铺评分-店铺不存在');
        }

        // 计算指定时间范围内的评分
        $time_ago = time() - $timeRange;

        // 使用ThinkPHP查询构建器直接计算各项评分的平均值
        $result = Db::name('tbl_goods_comment')
            ->where('store_id', $store_id)
            ->where('create_at', '>=', $time_ago)
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
            return false;
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

        return true;
    }
}