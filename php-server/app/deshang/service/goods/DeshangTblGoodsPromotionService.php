<?php


namespace app\deshang\service\goods;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;


use app\common\dao\goods\TblGoodsFlashsaleDao;
use app\common\dao\goods\TblGoodsWholesaleDao;
use app\common\dao\goods\TblGoodsUserdiscountDao;
use app\common\dao\user\UserDao;

use app\common\enum\goods\TblGoodsPromotionEnum;
use app\common\enum\goods\TblGoodsFlashsaleEnum;



class DeshangTblGoodsPromotionService extends BaseDeshangService
{

    public $flashsaleDao;
    public $wholesaleDao;
    public $userdiscountDao;
    public $userDao;

    public function __construct()
    {
        parent::__construct();
        $this->flashsaleDao = new TblGoodsFlashsaleDao();
        $this->wholesaleDao = new TblGoodsWholesaleDao();
        $this->userdiscountDao = new TblGoodsUserdiscountDao();
        $this->userDao = new UserDao();
    }


    // 获取商品促销价格
    public function getTblGoodsPromotionPrice(array $sku, $quantity, $user_id, $promotion_platform, $promotion_type)
    {
        // 初始化结果，默认使用原价
        $result = [
            'promotion_platform' => '',
            'promotion_type' => '',
            'promotion_related_id' => 0,
            'promotion_price' => $sku['sku_price'],
            'all_promotions' => [] // 存储所有可用的促销信息
        ];

        // 获取所有可用的促销活动
        $promotions = [];


        // 1. 商品促销[限时折扣]
        $flashsale = $this->getGoodsPromotionFlashsale($sku);
        if (!empty($flashsale)) {
            $promotions[] = [
                'type' => TblGoodsPromotionEnum::PROMOTION_TYPE_FLASHSALE,
                'price' => $flashsale['promotion_price'],
                'data' => $flashsale
            ];
        }

        // 2. 商品促销[批发价]
        $wholesale = $this->getGoodsPromotionWholesale($sku, $quantity);

        if (!empty($wholesale)) {
            $promotions[] = [
                'type' => TblGoodsPromotionEnum::PROMOTION_TYPE_WHOLESALE,
                'price' => $wholesale['promotion_price'],
                'data' => $wholesale
            ];
        }

        // 3. 商品促销[平台会员折扣价]
        $userdiscount = $this->getGoodsPromotionUserdiscount($sku, $user_id);
        if (!empty($userdiscount)) {
            $promotions[] = [
                'type' => TblGoodsPromotionEnum::PROMOTION_TYPE_USER_DISCOUNT,
                'price' => $userdiscount['promotion_price'],
                'data' => $userdiscount
            ];
        }




        // 保存所有促销信息
        $result['all_promotions'] = $promotions;

        // 如果指定了促销类型，则使用指定的促销  [ 如需要自动获取最低价 则不需要指定]
        if ($promotion_type && $promotion_platform) {
            foreach ($promotions as $promo) {
                if (
                    $promo['data']['promotion_type'] == $promotion_type &&
                    $promo['data']['promotion_platform'] == $promotion_platform
                ) {
                    $result = array_merge($result, $promo['data']);
                    return $result;
                }
            }
        }

        // 如果没有指定或指定的促销不可用，则自动选择最低价格
        if (!empty($promotions)) {
            // 按价格排序
            usort($promotions, function ($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            // 使用价格最低的促销
            $result = array_merge($result, $promotions[0]['data']);
        }





        return $result;
    }


    // 获取商品商品促销信息[限时折扣]
    public function getGoodsPromotionFlashsale(array $sku)
    {

        // 判断商品是否存在限时折扣
        $condition = [];
        $condition[] = ['goods_id', '=', $sku['goods_id']];
        $condition[] = ['sku_id', '=', $sku['id']];
        $condition[] = ['status', '=', TblGoodsFlashsaleEnum::STATUS_START];
        $condition[] = ['start_time', '<=', time()];
        $condition[] = ['end_time', '>=', time()];

        $result = $this->flashsaleDao->getGoodsFlashsaleInfo($condition);

        if ($result) {
            return [
                'promotion_platform' => 'default',
                'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_FLASHSALE,
                'promotion_related_id' => $result['id'],
                'promotion_price' => $result['flashsale_price'],
            ];
        }

        return [];
    }


    // 获取商品促销信息[批发价]
    public function getGoodsPromotionWholesale(array $sku, int $quantity)
    {
        // 批发价必须有购买数量
        if ($quantity <= 0) {
            return [];
        }

        // 查询所有该商品的批发价格阶梯
        $condition = [
            ['goods_id', '=', $sku['goods_id']],
            ['sku_id', '=', $sku['id']]
        ];

        // 获取所有价格阶梯
        $wholesaleList = $this->wholesaleDao->getGoodsWholesaleList($condition);
        if (empty($wholesaleList)) {
            return [];
        }


        // 筛选符合条件的价格阶梯
        $matched = null;
        foreach ($wholesaleList as $wholesale) {
            // 数量必须大于等于最小数量
            if ($quantity < $wholesale['quantity_min']) {
                continue;
            }

            // 如果最大数量为null或者数量小于等于最大数量
            if (is_null($wholesale['quantity_max']) || $quantity <= $wholesale['quantity_max']) {
                // 如果还没有匹配记录，或者当前记录的最小数量更大（更优先）
                if (is_null($matched) || $wholesale['quantity_min'] > $matched['quantity_min']) {
                    $matched = $wholesale;
                }
            }
        }


        if ($matched) {
            return [
                'promotion_platform' => 'default',
                'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_WHOLESALE,
                'promotion_related_id' => $matched['id'],
                'promotion_price' => $matched['wholesale_price'],
                'quantity_min' => $matched['quantity_min'],
                'quantity_max' => $matched['quantity_max'],
            ];
        }

        return [];
    }


    // 获取商品促销信息[平台会员折扣价]
    public function getGoodsPromotionUserdiscount(array $sku, int $user_id)
    {
        // 必须有用户ID
        if (empty($user_id)) {
            return [];
        }

        // 获取用户信息，主要是获取用户等级ID
        $userInfo = $this->userDao->getUserInfoById($user_id);
        if (empty($userInfo) || empty($userInfo['growth_level_id'])) {
            return [];
        }

        // 判断商品是否存在会员价格
        $condition = [
            ['goods_id', '=', $sku['goods_id']],
            ['sku_id', '=', $sku['id']],
            ['user_level_id', '=', $userInfo['growth_level_id']]
        ];

        $result = $this->userdiscountDao->getGoodsUserdiscountInfo($condition);

        if ($result && $result['user_level_price'] > 0 && $result['user_level_price'] < $sku['sku_price']) {
            return [
                'promotion_platform' => 'platform',
                'promotion_type' => TblGoodsPromotionEnum::PROMOTION_TYPE_USER_DISCOUNT,
                'promotion_related_id' => $result['id'],
                'promotion_price' => $result['user_level_price'],
                'user_level_id' => $result['user_level_id']
            ];
        }

        return [];
    }




}
