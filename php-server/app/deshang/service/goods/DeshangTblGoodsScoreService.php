<?php


namespace app\deshang\service\goods;

use think\facade\Db;
use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;
use app\common\dao\goods\TblGoodsDao;






/**
 * 商品评分服务
 */
class DeshangTblGoodsScoreService extends BaseDeshangService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 更新商品评分
     * 
     * 根据指定时间范围内的评论计算并更新商品的平均评分
     * 
     * 注意：
     * - 此方法不管理事务，事务由外部调用方管理
     * 
     * @param array $goods_ids 商品ID数组
     * @param int $timeRange 时间范围（秒），默认1年（365 * 24 * 3600）
     * @return bool true 表示成功更新，false 表示商品ID为空
     * @throws CommonException 当更新失败时抛出异常
     */
    public function updateGoodsScore(array $goods_ids, int $timeRange = 365 * 24 * 3600): bool
    {
        if (empty($goods_ids)) {
            return false;
        }

        // 计算指定时间范围内的评分
        $time_ago = time() - $timeRange;

        // 遍历每个商品，计算平均评分
        foreach ($goods_ids as $goods_id) {
            // 使用ThinkPHP查询构建器直接计算商品评分的平均值
            $result = Db::name('tbl_goods_comment')
                ->where('goods_id', $goods_id)
                ->where('create_at', '>=', $time_ago)
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
        return true;
    }
}